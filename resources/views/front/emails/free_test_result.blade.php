<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Plan Free Test</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    
    <p>Thank you for completing your free nutrition test!</p>

    <!-- <p>We appreciate your submission. Our team will review it shortly!</p> -->

    <p>Nutrition Score : {{ $quiz->nutrition_score}}</p>
    <p>Nutrition Feedback : {{ $quiz->nutrition_feedback }}</p>

    <p>Supplement Score : {{ $quiz->supplements_score}}</p>
    <p>Supplement Feedback : {{ $quiz->supplements_feedback }}</p>

    <p>Sports Score : {{ $quiz->sports_score}}</p>
    <p>Sports Feedback : {{ $quiz->sports_feedback }}</p>

    <p>Please review the details above.</p>

    <p style="margin-top: 30px;">Best regards,<br><strong>The Performance Health Team</strong></p>

</body>
</html>
