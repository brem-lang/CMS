<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Your File — {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f5; padding: 20px; }
        .gate { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); max-width: 420px; width: 100%; }
        .gate__icon { text-align: center; font-size: 48px; color: #e53637; margin-bottom: 10px; }
        .gate__title { text-align: center; margin: 0 0 5px; font-size: 1.25rem; font-weight: 600; color: #111; }
        .gate__product { text-align: center; margin: 0 0 25px; font-size: 0.9rem; color: #666; }
        .gate label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #333; }
        .gate label span { color: #e53637; }
        .gate input[type="text"] { width: 100%; padding: 14px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; }
        .gate input:focus { outline: none; border-color: #e53637; }
        .gate .btn { width: 100%; padding: 14px; margin-top: 20px; background: #e53637; color: #fff; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .gate .btn:hover { background: #c42d2e; }
        .gate .hint { text-align: center; margin-top: 20px; font-size: 13px; color: #666; }
        .alert { padding: 12px 16px; margin-bottom: 20px; border-radius: 5px; font-size: 14px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="gate">
        <div class="gate__icon">&#8681;</div>
        <h1 class="gate__title">Enter your receipt ID to download</h1>
        <p class="gate__product">{{ $productTitle }}</p>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->has('receipt_id'))
            <div class="alert alert-danger">{{ $errors->first('receipt_id') }}</div>
        @endif

        <form action="{{ route('digital-product.download.verify', $orderItem) }}" method="POST">
            @csrf
            <label for="receipt_id">Receipt ID <span>*</span></label>
            <input type="text"
                id="receipt_id"
                name="receipt_id"
                value="{{ old('receipt_id') }}"
                placeholder="e.g., RCP-ORD-20260125-123"
                required
                autocomplete="off">

            <button type="submit" class="btn">Download file</button>
        </form>

        <p class="hint">Your receipt ID was sent to your email. Too many attempts may temporarily limit access.</p>
    </div>
</body>
</html>
