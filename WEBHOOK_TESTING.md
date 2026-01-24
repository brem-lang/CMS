# Webhook Testing Guide

This guide explains how to test PayMongo webhooks in local development.

## Problem

PayMongo webhooks cannot reach `localhost` directly because it's not accessible from the internet. External services like PayMongo need a public URL to send webhook requests.

## Solutions

### 1. Manual Testing Command (Recommended for Local Development)

Use the Artisan command to simulate webhook events:

```bash
# Test successful payment
php artisan paymongo:test-webhook {order_id} paid

# Test failed payment
php artisan paymongo:test-webhook {order_id} failed

# With custom source ID
php artisan paymongo:test-webhook {order_id} paid --source-id=src_test123

# With custom payment intent ID
php artisan paymongo:test-webhook {order_id} paid --intent-id=pi_test123
```

**Example:**
```bash
php artisan paymongo:test-webhook 1 paid
```

This will:
- Find the order by ID
- Simulate a `payment.paid` webhook event
- Update the order status to 'paid' and 'processing'
- Clear the cart
- Display the updated order status

### 2. Browser-Based Testing Route (Local Only)

Visit this URL in your browser (only works in local environment):

```
http://localhost:8000/test-webhook/{order_id}?event=paid
```

**Query Parameters:**
- `event` - Event type: `paid` or `failed` (default: `paid`)
- `source_id` - Optional source ID (uses order's source_id if not provided)
- `intent_id` - Optional payment intent ID (uses order's intent_id if not provided)

**Example:**
```
http://localhost:8000/test-webhook/1?event=paid
http://localhost:8000/test-webhook/1?event=failed&source_id=src_test123
```

### 3. Using ngrok for Real Webhook Testing

For end-to-end testing with real PayMongo webhooks:

1. **Install ngrok:**
   ```bash
   # Download from https://ngrok.com/download
   # Or using package manager
   ```

2. **Start your Laravel application:**
   ```bash
   php artisan serve
   ```

3. **Start ngrok tunnel:**
   ```bash
   ngrok http 8000
   ```

4. **Copy the ngrok URL** (e.g., `https://abc123.ngrok.io`)

5. **Configure PayMongo webhook:**
   - Go to PayMongo Dashboard
   - Navigate to Webhooks section
   - Add webhook endpoint: `https://abc123.ngrok.io/webhooks/paymongo`
   - Select events: `payment.paid` and `payment.failed`

6. **Test with real payments:**
   - Create an order through checkout
   - Complete payment in PayMongo test mode
   - Webhook will be sent to your ngrok URL
   - Check logs: `storage/logs/laravel.log`

## Webhook Payload Structure

PayMongo webhooks follow this structure:

```json
{
  "data": {
    "type": "payment.paid",
    "attributes": {
      "data": {
        "attributes": {
          "payment_intent_id": "pi_xxx",
          "source": {
            "id": "src_xxx"
          }
        }
      }
    }
  }
}
```

## Logging

All webhook requests are logged to `storage/logs/laravel.log` with:
- Request headers
- Payload data
- Processing status
- Order updates
- Errors (if any)

**View logs:**
```bash
tail -f storage/logs/laravel.log
```

## Testing Flow

1. **Create an order:**
   - Add items to cart
   - Go to checkout
   - Fill in details
   - Place order (this creates an order record)

2. **Test webhook:**
   ```bash
   php artisan paymongo:test-webhook {order_id} paid
   ```

3. **Verify results:**
   - Check order status updated to 'processing'
   - Check payment_status updated to 'paid'
   - Verify cart is cleared
   - Check logs for processing details

## CSRF Protection

The webhook route (`/webhooks/paymongo`) is excluded from CSRF protection in `bootstrap/app.php` because webhooks come from external services and don't include CSRF tokens.

## Production Notes

- **Signature Verification:** Currently skipped for local development. In production, implement proper PayMongo signature verification using `PAYMONGO_WEBHOOK_SECRET`.
- **Security:** The test route (`/test-webhook`) is only available in local environment.
- **Error Handling:** All webhook errors are logged. Monitor logs in production.

## Troubleshooting

**Webhook not processing:**
- Check logs: `storage/logs/laravel.log`
- Verify order exists and has payment IDs
- Check webhook payload structure matches PayMongo format

**Order not found:**
- Ensure order has `payment_intent_id` or `payment_source_id` set
- Check order ID is correct
- Verify order was created through checkout

**Cart not clearing:**
- Check logs for cart clearing actions
- Verify user_id is set correctly
- Check CartService is working properly
