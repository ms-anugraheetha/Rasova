import http from 'k6/http';
import crypto from 'k6/crypto';
import { check } from 'k6';

const BASE_URL = 'http://localhost:8000';

// Target order 27 — confirmed pending with a real gateway_order_id via tinker.
const GATEWAY_ORDER_ID = 'order_TM8P7VcMrl9VqM';
const AMOUNT_MINOR = 8000;

const WEBHOOK_SECRET = __ENV.RAZORPAY_WEBHOOK_SECRET;

export const options = {
  vus: 1,
  iterations: 1,
};

export default function () {
  if (!WEBHOOK_SECRET) {
    console.error('RAZORPAY_WEBHOOK_SECRET was not passed in. Run with: k6 run -e RAZORPAY_WEBHOOK_SECRET="<secret>" k6-tests/webhook-duplicate-test.js');
    return;
  }

  // Build the exact payload Razorpay would send for a payment.captured event.
  // Built as a fixed JSON string (not re-serialized per request) so the
  // signature we compute matches byte-for-byte what we send in both requests.
  const payload = JSON.stringify({
    event: 'payment.captured',
    payload: {
      payment: {
        entity: {
          id: `pay_k6test${Date.now()}`,
          order_id: GATEWAY_ORDER_ID,
          amount: AMOUNT_MINOR,
        },
      },
    },
  });

  const signature = crypto.hmac('sha256', WEBHOOK_SECRET, payload, 'hex');

  const params = {
    headers: {
      'Content-Type': 'application/json',
      'X-Razorpay-Signature': signature,
    },
  };

  console.log(`Sending two simultaneous webhook deliveries for gateway_order_id=${GATEWAY_ORDER_ID}`);

  // Fire two IDENTICAL webhook deliveries at the same time — simulating
  // Razorpay retrying a delivery it didn't get a fast-enough ack for,
  // which is a documented, expected real-world behavior on their end.
  const responses = http.batch([
    ['POST', `${BASE_URL}/webhooks/razorpay`, payload, params],
    ['POST', `${BASE_URL}/webhooks/razorpay`, payload, params],
  ]);

  const [res1, res2] = responses;

  console.log(`Webhook 1 -> status ${res1.status}, body: ${res1.body}`);
  console.log(`Webhook 2 -> status ${res2.status}, body: ${res2.body}`);

  check(res1, { 'webhook 1 accepted (200)': (r) => r.status === 200 });
  check(res2, { 'webhook 2 accepted (200)': (r) => r.status === 200 });

  console.log('');
  console.log('Both webhooks should return 200 regardless of duplicate — that is correct Razorpay ack behavior.');
  console.log('The real test is DB state: run the tinker check below to confirm only ONE payment_transaction was created.');
}