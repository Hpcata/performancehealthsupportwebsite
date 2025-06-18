<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Plan Free Test</title>
</head>
<body>
    <h2>Hello {{$user->name}},</h2>
    
    <p>Thank you for submitting your free test results.</p>

    <!-- <p>We appreciate your submission. Our team will review it shortly!</p> -->

    <p>Score : {{$user->nutrition_score}}</p>
    <p>Feedback : {{ $user->nutrition_feedback }}</p>

    <p>Please review the details above.</p>
</body>
</html>
