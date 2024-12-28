<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your plan purchase!</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    
    <p>Thank you for purchasing the {{ $planName }} plan.</p>

    <p>We appreciate your submission. Our team will review it shortly!</p>

    <p>Your plan will be finalized and ready within the next 24 hours.</p>

    <p>Best regards, <br> The Performancehealthsupport Team</p>
</body>
</html>
