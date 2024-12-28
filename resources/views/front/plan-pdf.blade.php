<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Plan</title>
</head>
<body>
    @foreach($userPlans as $userPlan)
        <h1 style="">{{ $userPlan->plan->name }}</h1>
        <hr>
        @foreach ($userPlan->userMealTimes as $userMealTime)
            <h2>{{ $userMealTime->mealTime->title }} <small>(Meal Time)</small></h2>

            @foreach ($userMealTime->userCategories as $userCategory)
                <h3>{{ $userCategory->category->title }} <small>(Category)</small></h3>
                @foreach ($userCategory->userMeals as $userMeal)
                    <h5>{{ $userMeal->meal->title }} <small>(Meal)</small></h5>
                    <img src="{{ asset('private/public/storage/'.$userMeal->meal->image) }}" alt="No image" style="width: 150px; height: auto;">
                    <ul>
                        @foreach ($userMeal->userItems as $userItem)
                            <li>{{ $userItem->item->title }}</li>
                        @endforeach
                    </ul>
                @endforeach
            @endforeach
        @endforeach
    @endforeach
</body>
</html>
