<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Activated</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>We are excited to inform you that your {{ $planName }} plan has been successfully activated!</p>

    <p>Thank you for choosing us. Your plan is now active and ready to use. Please <a href="{{ url('action-sport-nutrition-plan') }}" target="_blank">check your account</a> for the details and enjoy the benefits.</p>

    <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>

    <p>Best regards, <br> The Performancehealthsupport Team</p>
</body>
</html>
