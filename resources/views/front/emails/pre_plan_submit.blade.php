<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Plan Purchased</title>
</head>
<body>
    <h2>Hello,</h2>
    
    <p>A new plan has been purchased by {{ $user->name }}</p>

    <p>Plan Name : {{ $planName }}</p>

    <p>User Details : </p>
    <p>Name : {{ $user->name }}</p>
    <p>Email : {{ $user->email }}</p>
    <p>Phone : {{ $user->phone }}</p>

    <p>Please review the details above.</p>
</body>
</html>
