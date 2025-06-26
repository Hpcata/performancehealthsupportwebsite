<!DOCTYPE html>
<html>
<head>
    <title>New Sports Interest Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .details {
            margin-bottom: 15px;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">New Sports Interest Submission</div>
        
        <div class="details">
            <p><strong>Name:</strong> {{ $interest->name }}</p>
            <p><strong>Email:</strong> {{ $interest->email }}</p>
            <p><strong>Sport:</strong> {{ $interest->sport }}</p>
            <p><strong>Game/Event:</strong> {{ $interest->sport_game }}</p>
            <p><strong>State:</strong> {{ $interest->state }}</p>
            <p><strong>Submitted At:</strong> {{ $interest->created_at->format('F j, Y, g:i A') }}</p>
        </div>

        <p>You have received a new sports interest submission. Please review and follow up as needed.</p>

        <div class="footer">
            <p>Regards,</p>
            <p>Performancehealthsupport System</p>
        </div>
    </div>
</body>
</html>
