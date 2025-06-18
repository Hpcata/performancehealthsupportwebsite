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

        /* Preview Container */
        #previewContainer {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            display: none;
        }

        /* Generate PDF Button */
        #generatePdfBtn {
            display: none;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        #generatePdfBtn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    @foreach($userPlans as $userPlan)
        <div class="header">
            <!-- Sport-specific image based on the sport the user is training for -->
            @if($userPlan->user_id == 66)
                <img src="{{ url('private/public/front/images/plan-67.png') }}" alt="Sport Image">
            @else
                <img src="{{ url('front/images/about-new.png') }}" alt="Sport Image">
            @endif

            <!-- Sport name with color and the plan headline -->
            <h2 style="color: #333">{{ $userPlan->plan->name }}</h2>
            <!-- <div class="headline">High Load Training Day</div> -->
        </div>

        <div class="meal-plan">
            @foreach ($userPlan->userMealTimes as $userMealTime)
                @if($userMealTime->userMeals && $userMealTime->userMeals->count())
                    <div class="meal-time">
                        <h5>{{ $userMealTime->mealTime->title }}</h5>
                        <table>
                            <tbody>
                                @php
                                    // Combine all meals from all categories into one collection
                                    $allMeals = collect();
                                    foreach ($userMealTime->userCategories as $userCategory) {
                                        $allMeals = $allMeals->merge($userCategory->userMeals);
                                    }
                                    // Sort combined meals by meal_id as integer ascending
                                    $sortedMeals = $allMeals->sortBy(function($userMeal) {
                                        return (int) $userMeal->meal_id;
                                    });
                                @endphp

                                @foreach ($sortedMeals as $userMeal)
                                    <tr>
                                        <td>
                                            <img src="{{ url('private/public/storage/'.$userMeal->meal->image) }}" alt="Meal image">
                                        </td>
                                        <td>{{ $userMeal->meal->title }}
                                            @if ($userMeal->meal->description)
                                                <br>
                                                <span style="font-size: 12px; color: #666;">{{ $userMeal->meal->description }}</span>
                                            @endif
                                            @if ($userMeal->meal->note)
                                                <br>
                                                <span class="mt-3" style="font-size: 12px; color: #666;"><strong>Note: </strong> {{ $userMeal->meal->note }}</span>
                                            @endif
                                            @php
                                                $carbsTotal = 0;
                                                $proteinTotal = 0;
                                                $fatTotal = 0;
                                                $energyTotal = 0;

                                                foreach ($userMeal->userItems as $userItem) {
                                                    $item = $userItem->item;

                                                    $carbsTotal += round(floatval($item->carbs ?? 0));
                                                    $proteinTotal += round(floatval($item->protein ?? 0));
                                                    $fatTotal += round(floatval($item->fat ?? 0));
                                                    $energyTotal += round(floatval($item->energy ?? 0));
                                                }
                                            @endphp
                                            @if ($userPlan->nutrition_info_flag == 1)
                                                <br>
                                                <span class="mt-3 d-none" style="font-size: 12px; color: #666;"><strong>Meal Total: 
                                                    Energy: {{ (int) $energyTotal }}kJ |
                                                    Protein: {{ (int) $proteinTotal }}g |
                                                    Carb: {{ (int) $carbsTotal }}g |
                                                    Fat: {{ (int) $fatTotal }}g</strong>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($userMeal->userItems as $userItem)
                                                    @php
                                                        $matchedItem = $userMeal->meal->userMealItems->firstWhere('id', $userItem->item_id);
                                                        $selectedQty = $matchedItem->pivot->selected_qty_unit ?? null;

                                                        if (is_string($selectedQty)) {
                                                            $decoded = json_decode($selectedQty, true);
                                                            $selectedQty = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
                                                        }

                                                        $checkedUnits = [];
                                                        if (is_array($selectedQty)) {
                                                            $checkedUnits = array_filter($selectedQty, function($u) {
                                                                return isset($u['checked']) && ($u['checked'] === true || $u['checked'] === "true" || $u['checked'] === 1 || $u['checked'] === "1");
                                                            });
                                                        }
                                                    @endphp

                                                    <li style="font-size: 12px; color: #666;">
                                                        {{ $userItem->item->title ?? '' }} |
                                                        QTY:
                                                        @if (!empty($checkedUnits))
                                                            {{ collect($checkedUnits)->map(function($unit) {
                                                                $qtyRaw = $unit['qty'];
                                                                $qty = 0;
                                                                $isFraction = false;

                                                                if (preg_match('/^(\d+)\s*\/\s*(\d+)$/', trim($qtyRaw), $matches)) {
                                                                    $numerator = (float) $matches[1];
                                                                    $denominator = (float) $matches[2];
                                                                    if ($denominator != 0) {
                                                                        $qty = $numerator / $denominator;
                                                                        $isFraction = true;
                                                                    }
                                                                } elseif (is_numeric($qtyRaw)) {
                                                                    $qty = (float) $qtyRaw;
                                                                }

                                                                $unitText = strtolower($unit['unit']);

                                                                if (in_array($unitText, ['g', 'ml', 'mL'])) {
                                                                    return round($qty) . $unit['unit'];
                                                                }

                                                                if ($isFraction) {
                                                                    return trim($qtyRaw) . ' ' . $unit['unit'];
                                                                }

                                                                return rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') . ' ' . $unit['unit'];
                                                            })->implode(' or ') }}
                                                        @elseif ($matchedItem)
                                                            @php
                                                                $qtyRaw = $matchedItem->pivot->qty ?? 0;
                                                                $unitText = strtolower($matchedItem->pivot->unit ?? '');
                                                                $qty = 0;
                                                                $isFraction = false;

                                                                if (preg_match('/^(\d+)\s*\/\s*(\d+)$/', trim($qtyRaw), $matches)) {
                                                                    $numerator = (float) $matches[1];
                                                                    $denominator = (float) $matches[2];
                                                                    if ($denominator != 0) {
                                                                        $qty = $numerator / $denominator;
                                                                        $isFraction = true;
                                                                    }
                                                                } elseif (is_numeric($qtyRaw)) {
                                                                    $qty = (float) $qtyRaw;
                                                                }
                                                            @endphp
                                                            @if (in_array($unitText, ['g', 'ml', 'mL']))
                                                                {{ round($qty) }}{{ $matchedItem->pivot->unit ?? '' }}
                                                            @elseif ($isFraction)
                                                                {{ trim($qtyRaw) . ' ' . $matchedItem->pivot->unit }}
                                                            @else
                                                                {{ rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') . ' ' . $matchedItem->pivot->unit }}
                                                            @endif
                                                        @else
                                                            {{ round((float) ($userItem->pivot->qty ?? 0)) }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach

    <!-- Preview Container for Plan -->
    <div id="previewContainer">
        <h3>Preview of the Plan</h3>
        <div id="previewContent"></div>
        <button id="generatePdfBtn">Generate PDF</button>
    </div>

    <!-- Footer with the logo -->
    <div class="footer">
        <img src="{{ url('private/public/front/images/logo.svg') }}" alt="Logo">
    </div>

    
</body>
</html>
