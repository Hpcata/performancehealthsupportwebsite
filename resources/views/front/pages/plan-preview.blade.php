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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</head>
<body>
@if($printAllmeal)
    <div id="pdf-content" style="background: #fff; max-width: 940px; width: 100%; font-family: 'Inter', Arial, sans-serif; padding-bottom: 60px; position: relative;">
        @foreach($userPlans as $userPlan)
            <!-- Hero Section -->
            @php
            $backgroundUrl = $sportImagePath ? asset('storage/' . $sportImagePath) : frontAssets('/images/banner-img.jpg');
            @endphp
            <div style="margin-bottom: 24px;">
                <div style="position: relative; width: 100%; min-height: 200px; border-radius: 18px; overflow: hidden; background-color: #3b3b3b;">
                    <img src="{{ $backgroundUrl }}" alt="Hero Banner"
                    style="width: 340px; height: 200px; object-fit: cover; display: block; position: absolute; right: 0; border-radius: 81px 0 0 0;" />
                    <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.15) 100%);"></div>
                    <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: flex; align-items: center; padding: 0 32px;">
                        <div>
                            <img src="{{ frontAssets('images/logo.svg') }}" alt="Logo" style="height: 36px; margin-bottom: 50px;" />
                            <div style="color: #fff; font-size: 1.1rem; font-weight: 500; margin-bottom: 12px;">
                            {{ $userPlan->user->first_name }}’s
                            </div>
                            <div style="color: #fff; font-size: 20px; font-weight: 700; line-height: 1.1;">
                                <span style="font-weight: 400; color: #e0e0e0;">Nutrition Plan | {{ $userPlan->plan->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userMealTime)
                @php
                    $hasMeals = $userMealTime->userSubCategories->where('user_plan_id', $userPlan->id)->some(function($sub) use ($userMealTime, $userPlan) {
                    return $sub->userMeals
                        ->where('user_plan_id', $userPlan->id)
                        ->where('user_category_id', $userMealTime->id)
                        ->where('user_sub_category_id', $sub->id)
                        ->count() > 0;
                    });
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

                    $sortedMeals = $allMeals->sortBy(fn($userMeal) => (int) $userMeal->id);
                    $groupedMeals = $sortedMeals->groupBy('user_sub_category_id');
                    @endphp

                    @foreach ($groupedMeals as $subCategoryId => $mealsGroup)
                        @php
                            $subCategory = $userMealTime->userSubCategories
                            ->where('user_plan_id', $userPlan->id)
                            ->where('id', $subCategoryId)
                            ->first();
                        @endphp

                        <!-- Category Label -->
                        <div class="" style="margin-bottom: 18px;">
                            <span style="color: #3b82f6; font-weight: 600; font-size: 1.1rem;">{{ $userMealTime->category->title }}</span>
                            @if ($subCategory)
                            <span style="color: #3b82f6; font-weight: 400; font-size: 1.1rem;"> | </span>
                            <span style="color: #3b82f6; font-weight: 500; font-size: 1.1rem;">{{ $subCategory->subCategory->title ?? 'Subcategory' }}</span>
                            @endif
                        </div>

                        @foreach ($mealsGroup as $userMeal)
                            @php
                            $userItems = $userMeal->userItems
                                ->where('user_plan_id', $userPlan->id)
                                ->where('user_meal_id', $userMeal->id)
                                ->where('user_category_id', $userMealTime->id)
                                ->where('user_sub_category_id', $userMeal->user_sub_category_id);

                            $carbs = $protein = $fat = $energy = 0;
                            foreach ($userItems as $userItem) {
                                $item = $userMeal->meal->userMealItems->firstWhere('id', $userItem->id);
                                $carbs += round(floatval($item->pivot->carbs ?? 0));
                                $protein += round(floatval($item->pivot->protein ?? 0));
                                $fat += round(floatval($item->pivot->fat ?? 0));
                                $energy += round(floatval($item->pivot->energy ?? 0));
                            }
                            @endphp

                            <!-- Food Card -->
                            <div style="display: flex; gap: 18px; background: #f5f5f5; border-radius: 16px; padding: 18px; margin-bottom: 16px; align-items: flex-start;">
                            <img src="https://performancehealthsupport.com/private/public/storage/meal_times/tUKBVYgJ0jG8DdTjmGLtnQR8anCvRMkcZUaY6Ono.jpg" alt="{{ $userMeal->meal->title }}"
                                style="width: 190px; min-height: 190px; object-fit: cover; border-radius: 12px;" />
                            <div class="meal-block page-break-margin">
                                <div style="font-size: 1.1rem; font-weight: 700; color: #222;">{{ $userMeal->meal->title }}</div>
                                @if ($userMeal->meal->description)
                                <div style="font-size: 14px; color: #444; margin-bottom: 4px;">{{ $userMeal->meal->description }}</div>
                                @endif
                                @if ($userMeal->meal->note)
                                <div style="font-size: 14px; color: #222; margin-bottom: 12px;"><b>Note:</b> {{ $userMeal->meal->note }}</div>
                                @endif
                                @if ($userPlan->nutrition_info_flag == 1)
                                <div style="font-size: 14px; display: flex; flex-wrap: wrap; gap: 12px;">
                                    <span style="color: #967500; font-weight: 600;">● Energy: {{ $energy }}kJ</span>
                                    <span style="color: #a60015; font-weight: 600;">● Protein: {{ $protein }}g</span>
                                    <span style="color: #3e8e00; font-weight: 600;">● Carb: {{ $carbs }}g</span>
                                    <span style="color: #0077b6; font-weight: 600;">● Fat: {{ $fat }}g</span>
                                </div>
                                @endif
                            </div>

                            <div style="min-width: 310px;">
                                <div style="font-size: 1.05rem; font-weight: 700; color: #222; margin-bottom: 4px;">Ingredients</div>
                                <ul style="font-size: 14px; color: #444; margin: 0; padding-left: 18px;">
                                @foreach ($userItems as $userItem)
                                    @php
                                    $matchedItem = $userMeal->meal->userMealItems
                                        ->where('id', $userItem->id)
                                        ->where('pivot.user_id', $userPlan->user_id)
                                        ->first();

                                    $displayQty = '';
                                    $selectedQty = json_decode($matchedItem->pivot->selected_qty_unit ?? '[]', true);

                                    if (is_array($selectedQty) && count($selectedQty)) {
                                        $units = collect($selectedQty)->filter(fn($u) => isset($u['checked']) && $u['checked']);
                                        $displayQty = $units->map(function($unit) {
                                            return (is_numeric($unit['qty']) ? rtrim(rtrim(number_format($unit['qty'], 2, '.', ''), '0'), '.') : $unit['qty']) . ' ' . $unit['unit'];
                                        })->implode(' or ');
                                    } elseif ($matchedItem) {
                                        $qty = $matchedItem->pivot->qty;
                                        $unit = $matchedItem->pivot->unit;
                                        $displayQty = (is_numeric($qty) ? rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') : $qty) . ' ' . $unit;
                                    }
                                    @endphp
                                    <li style="margin-bottom: 4px;">{{ $displayQty }} {{ $userItem->item->title ?? '' }}</li>
                                @endforeach
                                </ul>
                            </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            @endforeach
        @endforeach

        <div id="pdf-footer" style="height: 40px; visibility: hidden;"></div>
    </div>
@else
    <div id="pdf-content" style="padding-bottom:60px; position:relative;">
    @foreach($userPlans as $userPlan)
        @php
            $planGroupedData = $groupedData[$userPlan->id] ?? [];
        @endphp

        <div class="header-box">
            <figure class="logo">
                <img src="{{ frontAssets('images/logo.svg') }}" alt="">
            </figure>
            <h5 class="text-white">{{ $userPlan->user->first_name }}’s</h5>
            <h1 class="text-white">Nutrition Plan <span>| {{ $userPlan->plan->name }}</span></h1>
            @php
                $backgroundUrl = $sportImagePath ? asset('storage/' . $sportImagePath) : frontAssets('/images/banner-img.jpg');
            @endphp
            <div class="header-img" style="background-image: url('{{ $backgroundUrl }}');"></div>
        </div>

        @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userMealTime)
            @php
                $categoryGroupedData = $planGroupedData[$userMealTime->id] ?? [];
                $allMeals = collect();
                $hasMeals = false;

                foreach ($userMealTime->userSubCategories->where('user_plan_id', $userPlan->id) as $subCategory) {
                    $allowedMealIds = $categoryGroupedData[$subCategory->id] ?? [];
                    if (empty($allowedMealIds)) continue;

                    $filteredMeals = $subCategory->userMeals
                        ->where('user_plan_id', $userPlan->id)
                        ->where('user_category_id', $userMealTime->id)
                        ->where('user_sub_category_id', $subCategory->id)
                        ->whereIn('id', $allowedMealIds);
                    if ($filteredMeals->isNotEmpty()) {
                        $hasMeals = true;
                        $allMeals = $allMeals->merge($filteredMeals);
                    }
                }
                if (!$hasMeals) continue;

                $sortedMeals = $allMeals->sortBy(fn($m) => (int) $m->id);
                $groupedMeals = $sortedMeals->groupBy('user_sub_category_id');
            @endphp

            @foreach ($groupedMeals as $subCategoryId => $mealsGroup)
                @php
                    $subCategory = $userMealTime->userSubCategories
                        ->where('user_plan_id', $userPlan->id)
                        ->where('id', $subCategoryId)
                        ->first();

                    $allowedMealIds = $categoryGroupedData[$subCategoryId] ?? [];
                    $mealsGroup = $mealsGroup->whereIn('id', $allowedMealIds);
                    if ($mealsGroup->isEmpty()) continue;
                @endphp

                <div class="meal-block page-break-margin">
                    <h5 class="text-primary mt-20">
                        {{ $userMealTime->category->title }}
                        @if ($subCategory)
                            <span>| {{ $subCategory->subCategory->title ?? 'Subcategory' }}</span>
                        @endif
                    </h5>

                    @foreach ($mealsGroup as $userMeal)
                        <div class="card-box bg-light">
                            <div class="row g-4">
                                <div class="col-xl-3">
                                    <figure class="img-square">
                                        <img src="{{ url('storage/'.$userMeal->meal->image ?? '') }}" alt="">
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
                                                    <span class="d-inline-block w-3 h-3 rounded-circle bg-amber-400 me-1"></span>
                                                    Energy: {{ round($energyTotal) }}kJ
                                                </div>
                                                <div class="d-flex align-items-center me-2 mb-2">
                                                    <span class="d-inline-block w-3 h-3 rounded-circle bg-rose-400 me-1"></span>
                                                    Protein: {{ round($proteinTotal) }}g
                                                </div>
                                                <div class="d-flex align-items-center me-2 mb-2">
                                                    <span class="d-inline-block w-3 h-3 rounded-circle bg-emerald-500 me-1"></span>
                                                    Carb: {{ round($carbsTotal) }}g
                                                </div>
                                                <div class="d-flex align-items-center me-2 mb-2">
                                                    <span class="d-inline-block w-3 h-3 rounded-circle bg-sky-500 me-1"></span>
                                                    Fat: {{ round($fatTotal) }}g
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
        @endforeach
    @endforeach

    <div id="pdf-footer" style="height:40px; visibility:hidden;"></div>
</div>                                          
@endif
	<!-- <footer>
      <div class="row g-3 align-items-center">
				<div class="col-md-4">
					<figure class="f-logo mx-auto ms-md-0 mb-0">
						<img src="images/logo-02.png" alt="">
					</figure>
				</div>
				<div class="col-md-4">
					<div class="text-center">
						<span class="page-number text-white">1</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="text-center text-md-end">
						<p class="text-primary"><strong>05/06/2025</strong></p>
					</div>
				</div>
			</div>
    </footer> -->
</body>
</html>