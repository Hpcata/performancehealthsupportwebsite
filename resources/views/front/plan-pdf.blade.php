<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3 {
            color: #333;
        }

        .meal-plan {
            margin-bottom: 3px;
        }

        .meal-time {
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        td img {
            width: 80px;
            height: 80px;
        }

        td:first-child {
            text-align: center;
            width: 15%;
        }

        td:nth-child(2) {
            padding-left: 10px;
            width: 42%;
        }

        td:nth-child(3) {
            width: 43%;
            padding-left: 10px;
        }

        ul {
            padding-left: 10px;
            margin: 0;
        }

        li {
            margin: 5px 0;
        }

        /* Header Style */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100%; /* Full width for sport image */
            height: 200px;
            object-fit: cover;
        }

        .header h2 {
            color: #333;
            font-size: 28px;
            margin-top: 10px;
        }

        .header .headline {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Footer Style */
        .footer {
            text-align: center;
            position: fixed;
            bottom: 20px;
            width: 100%;
        }

        .footer img {
            width: 150px; /* Adjust size of the logo */
        }
    </style>
</head>
<body>
    @foreach($userPlans as $userPlan)
        <div class="header">
            <!-- Sport-specific image based on the sport the user is training for -->
            <img src="{{ url('private/public/front/images/about-new.png') }}" alt="Sport Image">

            <!-- Sport name with color and the plan headline -->
            <h2 style="color: #333">{{ $userPlan->plan->name }}</h2>
            <!-- <div class="headline">High Load Training Day</div> -->
        </div>

        <div class="meal-plan">

            @foreach ($userPlan->userMealTimes as $userMealTime)
                <div class="meal-time">
                    <h3>{{ $userMealTime->mealTime->title }}</h3>

                    <table>
                        <tbody>
                            @foreach ($userMealTime->userCategories as $userCategory)
                                @foreach ($userCategory->userMeals as $userMeal)
                                    <tr>
                                        <td><img src="{{ url('private/public/storage/'.$userMeal->meal->image) }}" alt="Meal image"></td>
                                        <td>{{ $userMeal->meal->title }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($userMeal->userItems as $userItem)
                                                    <li>{{ ($userItem->item) ? $userItem->item->title : '' }} | QTY : {{ $userItem->item->qty ?? '0' }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach

    <!-- Footer with the logo -->
    <div class="footer">
        <img src="{{ url('private/public/front/images/logo.svg') }}" alt="Logo">
    </div>
</body>
</html>
