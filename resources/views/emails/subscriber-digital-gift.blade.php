<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Free Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #e53637;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background-color: #e53637;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
        }

        .btn:hover {
            background-color: #c42d2e;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Your Free Download</h1>
    </div>
    <div class="content">
        @if (!empty($body))
            <p>{{ $body }}</p>
        @endif

        <div class="product-name">{{ $productTitle }}</div>

        <p>Click the link below to download your file. This link will expire in 7 days.</p>
        <a href="{{ $downloadUrl }}" class="btn">Download</a>

        <p style="margin-top: 20px; font-size: 13px; color: #666;">If the button does not work, copy and paste this link into your browser:</p>
        <p style="word-break: break-all; font-size: 12px;">{{ $downloadUrl }}</p>
    </div>
    <div class="footer">
        <p>This email was sent from {{ config('app.name') }}.</p>
    </div>
</body>

</html>
