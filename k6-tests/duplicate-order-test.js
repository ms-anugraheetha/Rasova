import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = 'http://localhost:8000';
const PRODUCT_VARIANT_ID = 4; // valid variant with an existing product (variant 8 was orphaned data — see notes)

export const options = {
  vus: 1,
  iterations: 1,
};

// Pulls the value of a named hidden input out of raw HTML.
// Works for Blade's @csrf output: <input type="hidden" name="_token" value="...">
function extractHiddenInput(html, name) {
  const re = new RegExp(`name=["']${name}["'][^>]*value=["']([^"']+)["']`);
  const match = html.match(re);
  if (!match) {
    const re2 = new RegExp(`value=["']([^"']+)["'][^>]*name=["']${name}["']`);
    const match2 = html.match(re2);
    return match2 ? match2[1] : null;
  }
  return match[1];
}

// Pulls the CSRF token from <meta name="csrf-token" content="...">
function extractMetaCsrf(html) {
  const match = html.match(/name=["']csrf-token["'][^>]*content=["']([^"']+)["']/);
  return match ? match[1] : null;
}

// Tries the meta tag first (present on every page layout), falls back to a
// hidden @csrf input if the page happens to have one but no meta tag.
function extractCsrfToken(html) {
  return extractMetaCsrf(html) || extractHiddenInput(html, '_token');
}

export default function () {
  // jar carries the Laravel session cookie across all requests in this VU
  const jar = http.cookieJar();

  // 1. Hit the gateway page to get a CSRF token, then choose "Continue as Guest"
  //    (required by EnsureGatewayChoiceMade middleware before any storefront route works)
  let res = http.get(`${BASE_URL}/welcome`, { redirects: 0 });
  console.log(`Gateway page: status ${res.status}`);
  let gatewayToken = extractCsrfToken(res.body);
  check(gatewayToken, { 'got gateway CSRF token': (t) => t !== null });

  res = http.post(`${BASE_URL}/welcome/guest`, { _token: gatewayToken }, { redirects: 0, headers: { Referer: `${BASE_URL}/welcome` } });
  console.log(`Continue-as-guest POST: status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  check(res, { 'guest cookie set': (r) => r.status === 302 });

  // 2. Now hit homepage to establish a session with the guest cookie active
  res = http.get(`${BASE_URL}/`, { redirects: 0 });
  console.log(`Homepage: status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  check(res, { 'homepage loaded': (r) => r.status === 200 || r.status === 302 });

  // Follow manually if it redirected, so we still get a session cookie set
  if (res.status === 302 && res.headers['Location']) {
    res = http.get(res.headers['Location'], { redirects: 0 });
    console.log(`Homepage redirect target: status ${res.status}`);
  }

  // 2. Grab a CSRF token from any page with a @csrf form (homepage layout should have one,
  //    but cart add form is safest — fetch cart page for a fresh token)
  res = http.get(`${BASE_URL}/cart`, { redirects: 0 });
  console.log(`Cart GET (before add): status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  if (res.status === 302 && res.headers['Location']) {
    res = http.get(res.headers['Location'], { redirects: 0 });
    console.log(`Cart GET redirect followed: status ${res.status}`);
  }
  let token = extractCsrfToken(res.body);
  check(token, { 'got initial CSRF token': (t) => t !== null });

  // 3. Add product variant to cart
  res = http.post(`${BASE_URL}/cart`, {
    _token: token,
    product_variant_id: PRODUCT_VARIANT_ID,
    quantity: '1',
  }, { redirects: 0, headers: { Referer: `${BASE_URL}/cart` } });
  console.log(`Cart-add POST: status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  check(res, { 'added to cart': (r) => r.status === 200 || r.status === 302 });

  // 3b. Verify the cart actually has the item by loading the cart page
  res = http.get(`${BASE_URL}/cart`, { redirects: 0 });
  console.log(`Cart page GET (after add): status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  if (res.status === 302 && res.headers['Location']) {
    res = http.get(res.headers['Location'], { redirects: 0 });
  }
  console.log(`Cart page body (first 800 chars): ${res.body ? res.body.substring(0, 800) : 'N/A'}`);

  // 4. Load checkout page, grab a fresh CSRF token from the actual checkout form
  res = http.get(`${BASE_URL}/checkout`, { redirects: 0 });
  console.log(`Checkout GET: status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  if (res.status === 302 && res.headers['Location']) {
    res = http.get(res.headers['Location'], { redirects: 0 });
  }
  token = extractCsrfToken(res.body);
  check(token, { 'got checkout CSRF token': (t) => t !== null });
  check(res, { 'checkout page loaded': (r) => r.status === 200 });

  // 5. Build the exact payload CheckoutController@store expects
  const payload = {
    _token: token,
    address_type: 'home',
    full_name: 'K6 Test User',
    phone: '9876543210',
    address_line_1: 'Test Address Line 1',
    address_line_2: '',
    city: 'Kochi',
    district: 'Ernakulam',
    state: 'Kerala',
    postal_code: '682001',
    email: `k6test${Date.now()}@example.com`, // guest checkout needs an email
  };

  // 6. Fire TWO identical POST requests to /checkout at the same time,
  //    simulating a double-click or a retried request on a slow connection.
  const reqOpts = { redirects: 0, headers: { Referer: `${BASE_URL}/checkout` } };
  const responses = http.batch([
    ['POST', `${BASE_URL}/checkout`, payload, reqOpts],
    ['POST', `${BASE_URL}/checkout`, payload, reqOpts],
  ]);

  const [res1, res2] = responses;

  console.log(`Request 1 -> status ${res1.status}, Location: ${res1.headers['Location']}`);
  console.log(`Request 2 -> status ${res2.status}, Location: ${res2.headers['Location']}`);

  // Laravel redirects (302) to /checkout/{orderId}/payment on success.
  // Extract order IDs from the Location header of each response.
  const orderId1 = res1.headers['Location'] ? res1.headers['Location'].match(/checkout\/(\d+)\/payment/) : null;
  const orderId2 = res2.headers['Location'] ? res2.headers['Location'].match(/checkout\/(\d+)\/payment/) : null;

  if (orderId1 && orderId2) {
    const id1 = orderId1[1];
    const id2 = orderId2[1];
    console.log(`Order ID from request 1: ${id1}`);
    console.log(`Order ID from request 2: ${id2}`);

    check(null, {
      'BUG CHECK: only one order was created (order IDs should match)': () => id1 === id2,
    });

    if (id1 !== id2) {
      console.log('*** DUPLICATE ORDER BUG CONFIRMED: two different order IDs were created from one cart. ***');
    } else {
      console.log('OK: both requests resolved to the same order — no duplicate.');
    }
  } else {
    console.log('Could not extract order IDs — check response bodies/status manually.');
    console.log(`Response 1 body (first 500 chars): ${res1.body ? res1.body.substring(0, 500) : 'N/A'}`);
    console.log(`Response 2 body (first 500 chars): ${res2.body ? res2.body.substring(0, 500) : 'N/A'}`);
  }
}