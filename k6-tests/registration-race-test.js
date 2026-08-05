import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = 'http://localhost:8000';

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
  // 1. Load the registration page to get a CSRF token
  let res = http.get(`${BASE_URL}/register`, { redirects: 0 });
  console.log(`Register page GET: status ${res.status}`);
  check(res, { 'register page loaded': (r) => r.status === 200 });

  const token = extractCsrfToken(res.body);
  check(token, { 'got CSRF token': (t) => t !== null });

  // 2. Build one shared email — both requests race to register it.
  const raceEmail = `k6race${Date.now()}@example.com`;
  console.log(`Racing both registrations for email: ${raceEmail}`);

  const payload = {
    _token: token,
    first_name: 'K6',
    last_name: 'RaceTest',
    email: raceEmail,
    password: 'Password123!',
    password_confirmation: 'Password123!',
  };

  const reqOpts = { redirects: 0, headers: { Referer: `${BASE_URL}/register` } };

  // 3. Fire two IDENTICAL registration attempts at the same time.
  const responses = http.batch([
    ['POST', `${BASE_URL}/register`, payload, reqOpts],
    ['POST', `${BASE_URL}/register`, payload, reqOpts],
  ]);

  const [res1, res2] = responses;

  console.log(`Request 1 -> status ${res1.status}, Location: ${res1.headers['Location'] || 'none'}`);
  console.log(`Request 2 -> status ${res2.status}, Location: ${res2.headers['Location'] || 'none'}`);

  // Success looks like a 302 to /verify-email. A gracefully-handled duplicate
  // looks like a 302 back to /register (validation error). A 500 means the
  // unique-email race crashed the app instead of being handled cleanly.
  check(res1, { 'request 1 did not crash (not 500)': (r) => r.status !== 500 });
  check(res2, { 'request 2 did not crash (not 500)': (r) => r.status !== 500 });

  if (res1.status === 500 || res2.status === 500) {
    console.log('*** BUG: one of the concurrent registrations crashed with a 500 instead of a graceful duplicate-email error. ***');
    const crashed = res1.status === 500 ? res1 : res2;
    console.log(`Crash response body (first 800 chars): ${crashed.body ? crashed.body.substring(0, 800) : 'N/A'}`);
  } else {
    console.log('No 500 crash — both requests were handled without an unhandled exception.');
  }

  console.log('');
  console.log('Verify manually: exactly ONE user row should exist for this email in the DB.');
}