<!DOCTYPE html>
<html>
<head>
    <title>Nutrition Guide for {{ $interest->sport_game }}</title>
</head>
<body>
    <h2>Hello {{ $interest->name }},</h2>
    <p>Thank you for your interest in {{ $interest->sport_game }} under {{ $interest->sport }}.</p>
    <p>Here is some nutrition information tailored to your sport:</p>

    <ul>
        <li><strong>Hydration:</strong> Stay hydrated before, during, and after the game.</li>
        <li><strong>Protein Intake:</strong> Ensure proper protein intake for muscle recovery.</li>
        <li><strong>Carbs:</strong> Consume complex carbohydrates for sustained energy.</li>
        <li><strong>Fats:</strong> Include healthy fats for endurance sports.</li>
    </ul>

    <p>We will continue to share helpful resources with you.</p>
    <p>Best regards,<br>The Performancehealthsupport Team</p>
</body>
</html>
