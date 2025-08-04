<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<title>PHS - nutrition plan print</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="description" content="">
	<meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        button { display: block; margin: 20px auto; }

        /* =Box Sizing
        ========================================================================================*/
        * { -webkit-box-sizing:border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; }
        *:before, *:after { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; }
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"], input[type="search"], textarea, select, input[type="button"], input[type="submit"], button { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;  }


        /* =Deafult Tag & General Classes
        ========================================================================================*/
        html, body { -webkit-font-smoothing:antialiased; -moz-font-smoothing:antialiased; -ms-font-smoothing:antialiased; font-smoothing:antialiased; /* Fix for webkit rendering */ -webkit-text-size-adjust:100%; height: 100%; }
        html { overflow-y: inherit !important; }
        body { font-size:10px; line-height:1.45; font-weight: 400; font-family: 'Noto Sans', Arial, Helvetica, sans-serif; color:#222222; letter-spacing: 0.025rem; background: #fff; padding: 0; margin: 0; }
        img { vertical-align:top; border:0; }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { line-height:1.3; margin:0 0 0.8rem; font-weight: 700; }
        h1 span, h2 span, h3 span, h4 span, h5 span, h6 span, .h1 span, .h2 span, .h3 span, .h4 span, .h5 span, .h6 span { font-weight: 500; }
        h1, .h1 { font-size:22px; }
        h2, .h2 { font-size:1.5rem; }
        h3, .h3 { font-size:1.25rem; }
        h4, .h4 { font-size:1.525rem; }
        h5, .h5 { font-size:12px; }
        h6, .h6 { font-size:10px; }
        ul:last-child { margin: 0; }
        ul, ol { padding-left: 0.6rem; }
        ul li, ol li { line-height:1.45; margin-bottom: 0.35rem; }
        p { margin:0 0 0.8rem 0; }
        p:last-child { margin:0 0 0 0; }
        .text-white { color: #fff; }  
        .text-primary { color:#4078DD !important; }
        .mt-20 { margin-top: 20px; }
        .fw-500 { font-weight: 500; }
        .mb-2 { margin-bottom: .5rem !important; }
        .me-2 { margin-right: .5rem !important; }
        .me-1 { margin-right: .3rem !important; }
        .align-items-center { align-items: center !important; }
        .d-flex { display: flex !important; }
        .flex-wrap { flex-wrap: wrap !important; }
        .rounded-circle { border-radius: 50% !important; }
        .d-inline-block { display: inline-block !important; }
        .pl-2 { padding-left: 0.5rem; }
        
        /* =Header Css
        ========================================================================================*/
        #header { margin-bottom: 2rem; }
        .header-box { padding: 1.5rem; background: #3B3B3B; border-radius: 1.5rem; position: relative; z-index: 1; overflow: hidden; margin-bottom: 20px; }
        .header-img { position: absolute; right: 0; top: 0; bottom: 0; width: 59%; z-index: -1; background-repeat: no-repeat; background-size: cover; background-position: 50% 32%; border-top-left-radius: 90px; }
        .logo { max-width: 119px; width: 100%; margin: 0 0 32px 0; }
        .logo img { width: 100%; height: auto; }
        .header-box h5 { margin-bottom: 10px; }
        .header-box h1 { margin: 0; }
        .bg-light { background-color: #F5F5F5 !important; }
        .row { display: flex; flex-wrap: wrap; margin-left: -6px; margin-right: -6px; }
        .row > * { flex-shrink: 0; width: 100%; max-width: 100%; padding-right: 6px; padding-left: 6px; }
        .col-xl-3 { flex: 0 0 auto; width: 25%; }
        .col-xl-9 { flex: 0 0 auto; width: 75%; }
        .col-md-6 { flex: 0 0 auto; width: 50%; }
        .col-md-4 { flex: 0 0 auto; width: 33.3333333333%; }
        .w-3 { width: 5px; }
        .h-3 { height: 5px; }
        .bg-amber-400 { background: #8CC900; }
        .bg-sky-500 { background: #0D99FF; }
        .bg-emerald-500 { background:#F1B020 ; }
        .bg-rose-400 { background: #EB4C60; }
        .img-square { border-radius: 15px; overflow: hidden; padding-top: 100%; position: relative; margin: 0; }
        .img-square img { position: absolute; left: 0; right: 0; top: 0; bottom: 0; margin: auto; width: 100%; height: 100%; object-fit: cover; object-position: center center; }
        .card-box { padding: 12px; border-radius: 18px; margin:0 0 8px 0;}
        .card-box h6 { margin-bottom: 0.35rem; }
        footer { padding-top: 1rem; padding-bottom: 1rem; margin-top: 2rem; }

        /* =Footer page Css
        ========================================================================================*/
        .f-logo { max-width: 120px; width: 100%; margin: 0; }
        .f-logo img { width: 100%; height: auto; }
        .text-center { text-align: center !important; }
        .text-md-end { text-align: right !important; }
        .page-number { width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50px; background: #709EF1; font-size: 10px; font-weight: 600; }

        #pdf-content { width: 720px; margin:auto;
             /* padding: 2rem;  */
            font-family: Noto Sans, sans-serif; 
        }
        /* .meal-block {
            page-break-inside: avoid;
            break-inside: avoid;
        } */
       
        /* .meal-block:first-child {
            padding-top: 0;
        } */

        .meal-block-page-break {
            break-inside: avoid;
            page-break-inside: avoid;
            margin-top: 15px;
        }
        .page-break {
            page-break-before: always;
            break-before: page;
            margin-top: 15px;
        }
    
    </style>
</head>
<body>
@if($printAllmeal)
    <div id="pdf-content" style="padding-bottom:0px; position:relative;">
        @foreach($userPlans as $userPlan)
            <div class="header-box">
                <figure class="logo">
                    <img src="{{ frontAssets('images/logo.svg') }}" alt="">
                </figure>
                <h5 class="text-white">{{ $userPlan->user->first_name }}’s</h5>
                <h1 class="text-white">Nutrition Plan <span>| {{ $userPlan->plan->name }}</span></h1>
                @php
                    $backgroundUrl = $sportImagePath ? webAssets('storage/' . $sportImagePath) : frontAssets('/images/bannerimg.png');
                @endphp
                <div class="header-img" style="background-image: url('{{ $backgroundUrl }}');"></div>
            </div>
                
            @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userMealTime)
                @php
                    $hasMeals = false;
                    foreach ($userMealTime->userSubCategories->where('user_plan_id', $userPlan->id) as $subCategory) {
                        if ($subCategory->userMeals
                            ->where('user_plan_id', $userPlan->id)
                            ->where('user_category_id', $userMealTime->id)
                            ->where('user_sub_category_id', $subCategory->id)
                            ->count()) {
                            $hasMeals = true;
                            break;
                        }
                    }
                @endphp

                @if($hasMeals)
                    @php
                        $allMeals = collect();

                        foreach ($userMealTime->userSubCategories->where('user_plan_id', $userPlan->id) as $subCategory) {
                            $subMeals = $subCategory->userMeals
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_category_id', $userMealTime->id)
                                ->where('user_sub_category_id', $subCategory->id);

                            $allMeals = $allMeals->merge($subMeals);
                        }

                        $sortedMeals = $allMeals->sortBy(function($userMeal) {
                            return (int) $userMeal->id;
                        });
                    
                        $groupedMeals = $sortedMeals->groupBy('user_sub_category_id');
                        
                        $totalMeals = $sortedMeals->count();
                        $mealCount = 0; 

                    @endphp

                    @foreach ($groupedMeals as $subCategoryId => $mealsGroup)
                        @php
                            $subCategory = $userMealTime->userSubCategories
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $subCategoryId)
                                ->first();
                        @endphp
                        <div class="meal-block" >
                            <h5 class="text-primary mt-20">
                                {{ $userMealTime->category->title }}
                                @if ($subCategory)
                                    <span>| {{ $subCategory->subCategory->title ?? 'Subcategory' }}</span>
                                @endif
                            </h5>
                            @foreach ($mealsGroup as $userMeal)
                                @php $mealCount++; 
                                @endphp

                            <div class="card-box bg-light meal-block-page-break">
                                <div class="row g-4">
                                    <div class="col-xl-3">
                                        <figure class="img-square">
                                            <img src="{{ webAssets('storage/'.$userMeal->meal->image ?? '') }}" alt="">
                                        </figure>
                                    </div>
                                    <div class="col-xl-9">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <h6>{{ $userMeal->meal->title }}</h6>
                                                @if ($userMeal->meal->description)
                                                <p class="fw-500">{{ $userMeal->meal->description }}</p>
                                                @endif
                                                @if ($userMeal->meal->note)
                                                <p><strong>Note:</strong> {{ $userMeal->meal->note }}</p>
                                                @endif

                                                @php
                                                    $carbsTotal = 0;
                                                    $proteinTotal = 0;
                                                    $fatTotal = 0;
                                                    $energyTotal = 0;

                                                    $userItems = $userMeal->userItems
                                                        ->where('user_plan_id', $userPlan->id)
                                                        ->where('user_meal_id', $userMeal->id)
                                                        ->where('user_category_id', $userMealTime->id)
                                                        ->where('user_sub_category_id', $userMeal->user_sub_category_id);
                                                    foreach ($userItems as $userItem) {
                                                        $item = $userMeal->meal->userMealItems->firstWhere('id', $userItem->id);
                                                        $carbsTotal += round(floatval($item->pivot->carbs ?? 0));
                                                        $proteinTotal += round(floatval($item->pivot->protein ?? 0));
                                                        $fatTotal += round(floatval($item->pivot->fat ?? 0));
                                                        $energyTotal += round(floatval($item->pivot->energy ?? 0));
                                                    }
                                                @endphp

                                                @if ($userPlan->nutrition_info_flag == 1)
                                                <div class="mt-4 d-flex flex-wrap text-slate-700">
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span  style="color: #a60015; font-weight: 600;">
                                                        ● Protein: {{ round($proteinTotal) }}g</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span  style="color: #3e8e00; font-weight: 600;">
                                                        ● Carb: {{ round($carbsTotal) }}g</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span  style="color: #0077b6; font-weight: 600;">
                                                        ● Fat: {{ round($fatTotal) }}g</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span  style="color: #967500; font-weight: 600;">
                                                        ● Energy: {{ round($energyTotal) }}kJ</span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <h6>Ingredients</h6>
                                                <ul>
                                                @foreach ($userMeal->userItems->where('user_plan_id', $userPlan->id) as $userItem)
                                                    @php
                                                        $matchedItem = $userMeal->meal->userMealItems
                                                            ->filter(fn ($item) => $item->id == $userItem->id && $item->pivot->user_id == $userPlan->user_id)
                                                            ->first();

                                                        $selectedQty = $matchedItem->pivot->selected_qty_unit ?? null;
                                                        if (is_string($selectedQty)) {
                                                            $decoded = json_decode($selectedQty, true);
                                                            $selectedQty = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
                                                        }

                                                        $checkedUnits = [];
                                                        if (is_array($selectedQty)) {
                                                            $checkedUnits = array_filter($selectedQty, fn($u) => isset($u['checked']) && ($u['checked'] === true || $u['checked'] === "true" || $u['checked'] === 1 || $u['checked'] === "1"));
                                                        }
                                                    @endphp
                                                    <li class="pl-2">
                                                        @if (!empty($checkedUnits))
                                                            {{ collect($checkedUnits)->map(function($unit) {
                                                                $qtyRaw = $unit['qty'];
                                                                $qty = 0;
                                                                $isFraction = false;
                                                                if (preg_match('/^(\d+)\s*\/\s*(\d+)$/', trim($qtyRaw), $matches)) {
                                                                    $qty = (float) $matches[1] / (float) $matches[2];
                                                                    $isFraction = true;
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
                                                                $qty = is_numeric($qtyRaw) ? (float) $qtyRaw : 0;
                                                                $isFraction = preg_match('/^(\d+)\s*\/\s*(\d+)$/', trim($qtyRaw), $m);
                                                                if ($isFraction) {
                                                                    $qty = (float) $m[1] / (float) $m[2];
                                                                }
                                                            @endphp
                                                            @if (in_array($unitText, ['g', 'ml', 'mL']))
                                                                {{ round($qty) }}{{ $unitText }}
                                                            @elseif ($isFraction)
                                                                {{ trim($qtyRaw) . ' ' . $unitText }}
                                                            @else
                                                                {{ rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') . ' ' . $unitText }}
                                                            @endif
                                                        @else
                                                            {{ round((float) ($userItem->pivot->qty ?? 0)) }}
                                                        @endif
                                                        {{ $userItem->item->title ?? '' }}
                                                    </li>
                                                @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if(!$loop->parent->last)
                        <div class="page-break"></div>
                    @endif
                @endif
            @endforeach
        @endforeach

        <div id="pdf-footer" style="height:40px; visibility:hidden;"></div>
	</div>
@endif
</body>
</html>