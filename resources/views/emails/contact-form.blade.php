<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
        .field {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #e53637;
            margin-bottom: 5px;
            display: block;
        }
        .field-value {
            background-color: white;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
        .message-box {
            background-color: white;
            padding: 15px;
            border-radius: 3px;
            border: 1px solid #ddd;
            white-space: pre-wrap;
            min-height: 100px;
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
        <h1>New Contact Form Submission</h1>
    </div>
    <div class="content">
        <p>You have received a new contact form submission from your website.</p>
        
        <div class="field">
            <span class="field-label">Name:</span>
            <div class="field-value">{{ $name }}</div>
        </div>

        <div class="field">
            <span class="field-label">Email:</span>
            <div class="field-value">
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </div>
        </div>

        @if($phone)
        <div class="field">
            <span class="field-label">Phone:</span>
            <div class="field-value">{{ $phone }}</div>
        </div>
        @endif

        <div class="field">
            <span class="field-label">Message:</span>
            <div class="message-box">{{ $contactMessage }}</div>
        </div>
    </div>
    <div class="footer">
        <p>This email was sent from {{ config('app.name') }} contact form.</p>
        <p>You can reply directly to this email to respond to {{ $name }}.</p>
    </div>
</body>
</html>
