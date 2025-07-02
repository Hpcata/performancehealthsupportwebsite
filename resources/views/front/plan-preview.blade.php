<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<title>PHS - nutrition plan print</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="{{ frontAssets('print-plan/css/vendor/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ frontAssets('print-plan/css/style.css') }}">
	<link rel="stylesheet" href="{{ frontAssets('print-plan/css/responsive.css') }}">
</head>
<body>
<div id="wrapper" class="print-plan">
    @foreach($userPlans as $userPlan)
	<div id="header">
		<div class="container">
			<div class="header-box">
				<div class="row">
					<div class="col-md-6">
						<figure class="logo">
							<img src="{{ frontAssets('images/logo.svg') }}" alt="">
						</figure>
						<h5 class="text-white mt-4 pt-4">{{ $userPlan->user->first_name }}’s</h5>
						<h1 class="text-white mb-0">Nutrition Plan <span>| {{ $userPlan->plan->name }}</span></h1>
					</div>
				</div>
                    <div class="header-img" style="background-image: url('{{ frontAssets('print-plan/images/header-bg.jpg') }}');">
				</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="container">
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
                    @endphp

                    @foreach ($groupedMeals as $subCategoryId => $mealsGroup)
                        @php
                            $subCategory = $userMealTime->userSubCategories
                                ->where('user_plan_id', $userPlan->id)
                                ->where('id', $subCategoryId)
                                ->first();
                        @endphp

                        <div class="section-row p-0">
                            <h4 class="text-primary">
                                {{ $userMealTime->category->title }}
                                @if ($subCategory)
                                    <span>| {{ $subCategory->subCategory->title ?? 'Subcategory' }}</span>
                                @endif
                            </h4>

                            @foreach ($mealsGroup as $userMeal)
                            <div class="card-box bg-light mb-3">
                                <div class="row g-4">
                                    <div class="col-md-4 col-xl-3">
                                        <figure class="img-square">
                                            <img src="{{ url('private/public/storage/'.$userMeal->meal->image ?? '') }}" alt="">
                                        </figure>
                                    </div>
                                    <div class="col-md-8 col-xl-9">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <h5>{{ $userMeal->meal->title }}</h5>
                                                @if ($userMeal->meal->description)
                                                <p>{{ $userMeal->meal->description }}</p>
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
                                                        <span class="d-inline-block w-3 h-3 rounded-circle bg-amber-400 me-2"></span>
                                                        Energy: {{ round($energyTotal) }}kJ
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span class="d-inline-block w-3 h-3 rounded-circle bg-rose-400 me-2"></span>
                                                        Protein: {{ round($proteinTotal) }}g
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span class="d-inline-block w-3 h-3 rounded-circle bg-emerald-500 me-2"></span>
                                                        Carb: {{ round($carbsTotal) }}g
                                                    </div>
                                                    <div class="d-flex align-items-center me-2 mb-2">
                                                        <span class="d-inline-block w-3 h-3 rounded-circle bg-sky-500 me-2"></span>
                                                        Fat: {{ round($fatTotal) }}g
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <h5>Ingredients</h5>
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
                                                    <li>
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

                @endif
            @endforeach
		</div>
	</div>
    @endforeach

	<!-- <div id="footer" class="mt-4 mt-md-5 py-3 py-md-4">
		<div class="container">
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
		</div>
	</div> -->
</div><!--/#wrapper-->
<script src="{{ frontAssets('print-plan/js/jquery.js') }}"></script>
<script src="{{ frontAssets('print-plan/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ frontAssets('print-plan/js/general.js') }}"></script>
</body>
</html>