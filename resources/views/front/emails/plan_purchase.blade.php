<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your plan purchase!</title>
</head>
<body>
    <h2>Hey {{ $user->name }},</h2>
    
    <p>Thank you for purchasing Sports Nutrition Plans - {{ $planName }} .</p>

    <p>Our team will review your submission shortly and we will email you when your plan is ready.</p>

    <p>Best regards, <br> The Performance Health Support Team</p>
</body>
</html>
