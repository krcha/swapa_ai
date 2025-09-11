<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Inquiry - Marketplace</title>
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
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #374151;
        }
        .value {
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #3B82F6;
        }
        .priority-high {
            border-left-color: #EF4444;
        }
        .priority-medium {
            border-left-color: #F59E0B;
        }
        .priority-low {
            border-left-color: #10B981;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Inquiry</h1>
        <p>Marketplace Support System</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="label">Name:</div>
            <div class="value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <div class="label">Email:</div>
            <div class="value">{{ $email }}</div>
        </div>
        
        @if($phone)
        <div class="field">
            <div class="label">Phone:</div>
            <div class="value">{{ $phone }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="label">Category:</div>
            <div class="value">{{ ucfirst($category) }}</div>
        </div>
        
        <div class="field">
            <div class="label">Priority:</div>
            <div class="value priority-{{ $priority }}">{{ ucfirst($priority) }}</div>
        </div>
        
        <div class="field">
            <div class="label">Subject:</div>
            <div class="value">{{ $subject }}</div>
        </div>
        
        <div class="field">
            <div class="label">Message:</div>
            <div class="value">{{ $message }}</div>
        </div>
        
        <div class="field">
            <div class="label">Submitted:</div>
            <div class="value">{{ $created_at->format('F d, Y \a\t g:i A') }}</div>
        </div>
        
        <div class="field">
            <div class="label">IP Address:</div>
            <div class="value">{{ $ip_address }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>This inquiry was submitted through the Marketplace contact form.</p>
        <p>Please respond to the customer at: {{ $email }}</p>
        <p>Support Team - Marketplace</p>
    </div>
</body>
</html>
