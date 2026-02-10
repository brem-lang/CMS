<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download started — {{ config('app.name') }}</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f5; }
        .box { text-align: center; padding: 40px; }
        .box h1 { font-size: 1.25rem; color: #111; margin-bottom: 8px; }
        .box p { color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <iframe name="download-frame" style="display: none;" title="Download"></iframe>

    <div class="box">
        <h1>Download started</h1>
        <p>Redirecting you to the homepage...</p>
    </div>

    <form id="download-form" action="{{ $verifyUrl }}?stream=1" method="POST" target="download-frame">
        @csrf
        <input type="hidden" name="receipt_id" value="{{ $receiptId }}">
    </form>

    <script>
        document.getElementById('download-form').submit();
        setTimeout(function () {
            window.location.href = "{{ $homeUrl }}";
        }, 2500);
    </script>
</body>
</html>
