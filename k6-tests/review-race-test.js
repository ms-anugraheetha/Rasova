import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = 'http://localhost:8000';
const PRODUCT_ID = 11;
const PRODUCT_SLUG = 'curd-chilli-pickle-IS9san';

export const options = {
  vus: 1,
  iterations: 1,
};

function extractMetaCsrf(html) {
  const match = html.match(/name=["']csrf-token["'][^>]*content=["']([^"']+)["']/);
  return match ? match[1] : null;
}

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

function extractCsrfToken(html) {
  return extractMetaCsrf(html) || extractHiddenInput(html, '_token');
}

export default function () {
  // 1. Pass the storefront gateway (required before any storefront route works)
  let res = http.get(`${BASE_URL}/welcome`, { redirects: 0 });
  let token = extractCsrfToken(res.body);
  res = http.post(`${BASE_URL}/welcome/guest`, { _token: token }, { redirects: 0, headers: { Referer: `${BASE_URL}/welcome` } });
  console.log(`Continue-as-guest: status ${res.status}`);

  // 2. Register a fresh test user (auto-logs-in on success)
  res = http.get(`${BASE_URL}/register`, { redirects: 0 });
  token = extractCsrfToken(res.body);

  const testEmail = `k6review${Date.now()}@example.com`;
  res = http.post(`${BASE_URL}/register`, {
    _token: token,
    first_name: 'K6',
    last_name: 'ReviewTest',
    email: testEmail,
    password: 'Password123!',
    password_confirmation: 'Password123!',
  }, { redirects: 0, headers: { Referer: `${BASE_URL}/register` } });
  console.log(`Register: status ${res.status}, Location: ${res.headers['Location'] || 'none'}`);
  check(res, { 'registered and logged in': (r) => r.status === 302 });

  // 3. Load the product page to get a fresh CSRF token as this authenticated user
  res = http.get(`${BASE_URL}/products/${PRODUCT_SLUG}`, { redirects: 0 });
  console.log(`Product page GET: status ${res.status}`);
  if (res.status === 302 && res.headers['Location']) {
    res = http.get(res.headers['Location'], { redirects: 0 });
  }
  token = extractCsrfToken(res.body);
  check(token, { 'got review CSRF token': (t) => t !== null });

  // 4. Fire two simultaneous review submissions for the same product, same user
  const payload = {
    _token: token,
    rating: '5',
    review: 'This is a k6 test review submitted twice at the same time to check for race conditions.',
    is_anonymous: '0',
  };
  const reqOpts = { redirects: 0, headers: { Referer: `${BASE_URL}/products/${PRODUCT_SLUG}` } };

  const responses = http.batch([
    ['POST', `${BASE_URL}/products/${PRODUCT_ID}/reviews`, payload, reqOpts],
    ['POST', `${BASE_URL}/products/${PRODUCT_ID}/reviews`, payload, reqOpts],
  ]);

  const [res1, res2] = responses;
  console.log(`Review submit 1 -> status ${res1.status}, Location: ${res1.headers['Location'] || 'none'}`);
  console.log(`Review submit 2 -> status ${res2.status}, Location: ${res2.headers['Location'] || 'none'}`);

  check(res1, { 'review submit 1 did not crash (not 500)': (r) => r.status !== 500 });
  check(res2, { 'review submit 2 did not crash (not 500)': (r) => r.status !== 500 });

  if (res1.status === 500 || res2.status === 500) {
    console.log('*** Unhandled 500 during duplicate review submission — fix did not fully work. ***');
  } else {
    console.log('No 500 crash on either request.');
  }

  console.log('');
  console.log(`Verify manually: exactly ONE review row should exist for product_id=${PRODUCT_ID}, email=${testEmail}`);
  console.log(`Test user email: ${testEmail}`);
}