@extends(frontView('layouts.app'))

@section('title', $userMealTime->category->name)

@section('content')
<style>
    .blur-background {
        filter: blur(5px); /* Adjust the blur value */
        transition: filter 0.3s ease-in-out;
    }
</style>
<div class="section nutrition-plan-hero pt-5 pb-3"> 
    <div class="container">
        <div class="row align-items-top">
            <div class="col-md-6 col-lg-5">
                <div class="nutrition-plan-text">
                    <h1> <span class="text-primary">Healthy {{ $userMealTime->category->title }} Meals</span></h1>
                    <p>Simple meals to perform, recover, and thrive.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 ms-lg-auto">
                
                <div class="mt-2 mealtime-btn-list">
                    <ul class="">
                       @if($userPlan->userCategories->where('user_plan_id', $userPlan->id)->count() > 0)
                            {{-- ❶  Keep only one UserCategory per category‑id --}}
                            @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory)

                                @php
                                    $hasValidMeal = $userCategory->userSubCategories()
                                        ->where('user_plan_id', $userPlan->id)
                                        ->whereHas('userMeals', function ($q) use ($userPlan, $userCategory) {
                                            $q->where('user_plan_id', $userPlan->id)
                                                ->where('user_category_id', $userCategory->id);
                                        })
                                        ->exists();
                                @endphp

                                @if ($hasValidMeal && $userCategory->category)
                                    <li class="m-2">
                                        <a class="@if($userMealTime->category->id == $userCategory->category->id) active btn btn-outline-primary btn-sm text-white @else bg-white btn btn-outline-secondary text-black @endif"
                                        aria-current="page"
                                        href="{{ route('front.meal-time.details', ['id' => $userCategory->category->id, 'plan_id' => $userPlan->id]) }}">
                                            {{ $userCategory->category->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>

            </div>
        </div>
        <div class="plan-buttons-link">
            <div class="d-flex flex-wrap align-items-center">
                <!-- <a href="{{ route('front.plans.details', ['id' => $userPlan->plan_id, 'user_id' => $userPlan->user_id]) }}">View Plan</a> -->
                <a href="{{ route('front.profile', ['id' => $userPlan->user_id]) }}">My Profile</a>
                <a href="javascript:void(0);" class="ms-0 print-plan-btn " data-user-id="{{ $userPlan->user_id}}" data-plan-id="{{ $userPlan->plan_id}}">Print Plan</a>
                <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ShoppingModal" id="fetchAllMeals">Shopping List</a>
                <a href="{{ route('front.plans.details', ['id' => $userPlan->plan_id, 'user_id' => $userPlan->user_id]) }}" class="btn btn-primary ms-auto text-white border-0">Back</a>
            </div>
        </div>
    </div>
    
</div>
    
<div class="section bg-white pt-3 mb-5" id="nextSection">
    <div class="container">
        <div class="main-category-list mt-4">
            <div class="row g-0">
                
                @foreach($userMealTime->userSubCategories->where('user_plan_id', $userPlan->id) as $item)
                <?php //dd($userMealTime); ?>
           
                    @if($item->userMeals->where('user_plan_id', $userPlan->id) && $item->userMeals->where('user_plan_id', $userPlan->id)->count() > 0)
                    <div class="col-md-3">
                        <div class="nutrition-plan-box h-100 d-flex flex-column">
                            <figure>
                                <img src="{!! asset('private/public/storage/' . $item->subCategory->image) !!} " alt="">
                            </figure>
                            <h5 class="mb-3">{{ $item->subCategory->title }}</h5>
                            <a href="javascript:void(0)" class="btn btn-primary view-details-btn mt-auto" data-sub-category-id="{{ $item->subCategory->id }}" data-user-category-id="{{ $userMealTime->id }}" data-sub-category-name="{{ $item->subCategory->title }}" data-user-plan-id="{{ $userPlan->id }}">View Details</a>
                            <!-- <button type="button" class="subcategoryItemsModalbtn btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal">View Details</button> -->
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="mealModel" tabindex="-1" aria-labelledby="subcategoryItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealModalLabel">Title</h5>
                    <button type="button" class="btn-close meal-modal-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mealModelLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="mealModelContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="mealItemModel" tabindex="-1" aria-labelledby="mealItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealItemsModalLabel">Title</h5>
                    <button type="button" class="btn-close meal-item-model-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mealItemsLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="mealItemsContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="itemSwapModal" tabindex="-1" aria-labelledby="swapItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsSwapModalLabel">Title</h5>
                    <button type="button" class="btn-close item-swap-modal-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="itemsSwapLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- items swap Content -->
                    <div id="itemsSwapContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="apply-changes-btn btn btn-primary" data-user-item-id="" data-user-meal-id="">Apply Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Preview Modal -->
    <div class="modal" id="planPreviewModal" tabindex="-1" aria-labelledby="planPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-preview-body">
                    <div class="text-center">Loading preview...</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="downloadPDF()">Download PDF</button>
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Item Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Description without label -->
                    <div id="modalDescription" class="mb-3"></div>

                    <!-- Note with label inline -->
                    <div><strong>Note:</strong> <span id="modalNote"></span></div>
                </div>
            </div>
        </div>
    </div>
<!-- </div>/#wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    window.logoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAO8AAAAiCAYAAAC3Bo7TAAAACXBIWXMAABYlAAAWJQFJUiTwAAAFGmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoTWFjaW50b3NoKSIgeG1wOkNyZWF0ZURhdGU9IjIwMjUtMDYtMzBUMDg6Mjc6MTIrMDU6MzAiIHhtcDpNb2RpZnlEYXRlPSIyMDI1LTA2LTMwVDA5OjI0OjM1KzA1OjMwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDI1LTA2LTMwVDA5OjI0OjM1KzA1OjMwIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgcGhvdG9zaG9wOkNvbG9yTW9kZT0iMyIgcGhvdG9zaG9wOklDQ1Byb2ZpbGU9InNSR0IgSUVDNjE5NjYtMi4xIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjJlOTY2MjBmLWU4YWQtNDk3ZC04YzRmLWRmYmJhNDU4MzY0ZiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyZTk2NjIwZi1lOGFkLTQ5N2QtOGM0Zi1kZmJiYTQ1ODM2NGYiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyZTk2NjIwZi1lOGFkLTQ5N2QtOGM0Zi1kZmJiYTQ1ODM2NGYiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjJlOTY2MjBmLWU4YWQtNDk3ZC04YzRmLWRmYmJhNDU4MzY0ZiIgc3RFdnQ6d2hlbj0iMjAyNS0wNi0zMFQwODoyNzoxMiswNTozMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKE1hY2ludG9zaCkiLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+ej3d8gAAE5JJREFUeJztXVFy00i3/k5LDgkw9xcrGLECzFvGNjXKChJWEAucqbzFrCDOChLeqJ8kMivArCCixmR4w6wAzQrQvQXIM7b73AdJtiy1bNlxwtz/8lWlIOrWOacldfc5X5/uUMMJLnAFEMhnKd/qgjov7A1PVWfvLKizwG7ympDy7b+f3mktqq9x9q0JQdtTNki8evl0o62qX6vVLGYuA3gAwFxUXxKlUsl2XddTle07gTmUqKvvZJ+F5v2ENffEJj9Z0jgLWkX1D8X6Sdsmf88J6izz2sI+BPVO7Q03XfLU+bpDUpSL6Dp9upGxq+EElgBtM2Ay2CCQD8lv8p79rLZJAU8AnsrOxtm3JkBG/DsL0Tuzb3VUclTPPX5O6bp157Ohy/VmkbppuSS0zkt7rZe1ddJGAjzVs2g6bHyRQZ2E+DX53DQBN9lnFnk/LERPB8MqUjlXCBgg2hkyjvecoK0BR+lOzICZ1sNCn6pTGETljCzgbbra5uamqWmaw8xWuuw6MARMEA7VpQRiia/oew0naJ/aG0eJopx7slhHvw3AZ2AXlPfeCGBg7zzwwDhKfkzEYgc0PYjOQCv+z57zpczQjsGwJHhcIXz32Nk7Dw4liWeZDjajbSISs3ceeBphK/nNsKAD4sngRMw+gGnZEUbAIWi688bPKV23hPUdTtmky29TbY2Rfp8MuQ3gYcaAZB2CC6CdLN5z+gdfOGiByGDmSFb43IYMNJygFX8Pi7wfAbRFkYpFwYz6iHGx5/xdXqXcRVGpVMqapn0ArjYwrRrRINYKZ5br18UE56q69pwvZWZxMWuQZ8Aklq+X0cWAOWT6MPubYeM35+tO+uq+E5jMnLk+Q1emYxBNe3H5N3O5cfa1VVQXADx1AoeZT5JehMKo1p4TOIvIjbHSzhvaApNZXuw7gblq2UWwublpEtEFAON76C8EEod1h41/uq59JzDB2uuZH9+ULjpebuBmgzE6nlVDQhykr40Aq6htYVuyAxCDyg0nyFxXgsRh0fY1nL8OifPCqIwRxfSnsPLOG4KNIbDUaHJVaJr2z+i4BFcn3NcJ90HYIsCbFLKxhm+W6rb4HtVPHqcA5mdJXQD7SV06+mW1ifw8TxcADCF2OcUTEFGHSHtINHoIpmcZU2Z1QsLWWH76XoY1c5BhWJlOxsVDjgFzM1f0QrO3nPtd153PBrGsJ68R4MXtn/4e2NcIWyo5s97PHaw904savTCih60iJK4LETll3pS+eUh0Nq9x9u05iMYfNkthzLmnMAjkJ3U9Pf/2ioDxTEU55BYz+S+e5OsjlnVO/k6i/dK+ZScu9RpnfYB40mEZ1r4TmKp26ICXuH7ScILt5Kyzjr4BRaw6sZd3ALhASJ4xFycgI6ItbAe4Fw5K4axNELsAmoUERe7z6QyytYT1nfSgl4rrPQD3n55/bRNTL+8dzHs/ejzKLosRYIFxmDYWACClhehh3wSYOS/Y94QQzzRN611Ffh7TvAwY6lkml6EVcIsOhMTwQMtaFmLP+bvMPDKT1zTIo3S906frJ43zb4dJ93UkYSFF3CjtBPkMnlctUV/s1h1utW3yCXRQ9N50R2fGKxCVMY6B2VhoogndZyX7HFXYRsI2ItF+Yd/y0rXOntypz9Qj8Kv6e2D/9OntE32ZkT6F9r4TuEPmD5n4Q4hfryh7IRBROWb0EvBKpdLWKjveMiBBD5KmCcGeumKOKygBFB0IhdhFQhmLpMueUEXYbThB5h2RxCvGaOoemp4103e8QYIMUg7kKew7gTkKl/DG6GPdVxjZA0sz/LbY0OW35r4TtIfTrq6LGeQkAbvJr0IX1BkCPfDE5mj2dfMtZn9q+Sp0n7PsM7IDM8vRx3y5M8CwVKsKBPIAnKzEbX5hb3iNs+B5+sOjBdyaVUDlMjPz0XfpuEzlmEUMSbzplyAgeivTRdjdizphyGhPd4oh1pW6IvbbVFx/K7F6QmQEOt5zAh8Aws436QwE7qnWWwH2GZiEAaQdjMA/T+6DNyK8EjmkT935bHCCOCJw74V926s7n30dt/zYBmbeqTv8TG0DAKbnELQ9frYLsM+EHJlXxMrejy6yblKREXjFMBTXejdsQwQ2mFFnRj3NJoZu1JU9nokmwMrTxeDnuR/kDAhMz9Y8i9Ul/JxblpTBvDO2My2P6XmueKJOoqIxRTCRcGfpLGF9J/k7M70BgLZ9z6epe9nQZVCfJYswtKcvCKWXlO6sDGnOkrssVtZ5R9CMVclaJdbW1vzvbUMSBOrdwVqGpY2Rxy7eFesnSyhzz57cbuYXwwPBTf+QgDdEvzddO4wL0zJUSzBCyF663kywPMrL0gKAU3sjtG1iuRH/TxWHT4lOre0S8XbDCS4aTnCR9lDSmXtpvLTv9sCYqQ8AJMs/p3UKJRez7wTmLIY9j22O2ekVss2jskK9vzr5heAjNfuORiMLBciT1YN9pkn7CfCiVMKTWXctNyOHuojZmHJFJV7NvIvx6vRJNg0yRsMJ3GTHJIaz7wRT2VAj8DGm2DHy/8ZtV6mPwtk8HU7pQrRn2QkAkPwGRNbUNQrTC584gfKWfScwhxlPhMoxl5RhR6LlqlmeyunTjVbD6W9nOn7SLKIOeML2M2D+5nw7/rd9ezxoN5zAGjGcNQo6AJSD+Vy2Oa9gETQdNr5yP+tCEPdWIb8oiMjj1EOVUh5YltVxXde/SVtA1DuzN5Trd7MwKx84L7+WmJ6dPtloN5zAAmOcq85Ex3WHO8u4zRGOkCCCGDBHjIvfnH5HSvknBG2nY3mGfJWnr4RwuaRxFrSS/EiUEzDzWQ3FX22dbx0uMjgNJeqLsu66/NaEIl0yCcLQZmgf8spP7Q03PfBJpubeebADghunCzMAZmo2nMCfSpmNkcs2h5PBld3mhhNYX9B/rYpvmXk5lm15ZHKcAZQHg8HrSqVSvmFblgPhMPdHqrybCbLuZcjOLmtKVl7YgSVzE0THmVge8H6i/Jk8xlD0T6YSSVQJGCm07Xs+Y9JZ8zYBTNlDaZeZOkRoJ3+QZpgLrJAUdJ8z5RFxmeElwNxUus8h26z8FlhgNzPzJndASHB5LmM8Y6ltmmhI3cay3jifTRAsgQ4SyQkJWET0oVqtFpXjMXMPwJu1tbWbn7WvgrR7SdpB3WHlzpkiGKL/WKeNbHyYAgGeRthK75pSoW3f8xvnX98AlOxch5izFCZItgHtXwAAqRyox0iv7RLgvbTXH6fr1Z3Phs7rn8cXCgwkwHz3+dTecPecwGaenWkY8g7ycdsmv3Gudv/zMDXzRjsgPoHomJl3rrTUQ8WTClaFbrfrYjVJISYR7RCRMxgMPlQqlfoKZN4IhuKvdiY98gqzb9u+55/a6w/BOCKo1ovZB+PoDq0/XChep1ScW6DTvLTv9l7aG/ZLe8OeO+umiCoJfqOq17bv+WnvIkoumosM+5yxd6MdpkNSL1s6eW4v7buK8vkYz7wN569DZtnCokGCEuzroJkNuy6MRiM72lFkrEikSUROpVL5+fLyMtdVOrU33H0nWDhbbZEMt3UKkxjuYv1xn/pG+joQfoxNh+/3MSkHhWP0XVpv9tFvqe6bh2h/b6vhBJaMQiSC9H/CbffkiXq2Tbct2blVz2s9StIoAVuLfIb/hfVOn/puUs++ExyBJq7rOjaUNgLZ5wkSsYyxfapn9dK+22s6fG/qWacQtfnhvhOYA8gyQxh5zy39fuaBAGDPCerzpvfiYJ+YniVHxjRBsXIwjpKbxyuVSp3CPGJjlWqklM/++OOPk1XK/IEfWBbhkLzA7oyZILhEcmueS3PduLy8bI9Go4dQunnLQwhxaFmWsUqZP/ADy0KPZl0zr0K8NpcHAvnE8i0TdW46xp2F9+/fewDuRzuN6kT08wI7jvLqGaPRaAeKdeOmw0bSfUrHf3nlRfc958WTyfvXse6rCKN5tqnqzLMlbXda5jy7Iva/rGmar2la73vnni8Dy7KM0WhkjUYjQwjhRZzLjUFP74CIwZCvfqLbzSLs4TJQbC8rhMZ50IbiRIQ8LENibW5umkKIYyLaSZdJKX+FovN+Qb+cXGNtOMFWcjD7H/R3RCI02XeC+y/sDW8IOEU2Y8f1k9caTmANEzq/ou8B2Rj6i+w3k2GLSla6zjxbknZHRNaU3gFwEROeX8J4dAuYHE+EaP1YSgkpJarVqps8IywadC+IyO12u5k14EjOJwB49+7dOEKuVqufkB18fQA9Zn51eXnZVslI12dmF8DR5eVlL0e3MxgMwvYTgZlRrVY9KeVzVWiVYxcQpu/2SqXSOAe/Wq22MecbJyJXqGZdAryzJ3fq19Vx/+l4//69d3l5+RjqvGjrZq3JR5pRZcAsfCrEd0J0WIIFwCeiHk2YWGswGLy+JrUGwuVCp1KpFBmgjGi14UO1Wp16xlHHjdsAAB4RuQgHCFMIcVxQR4wygPpgMLhYNCQTUCVNF8jf/H8CVQaPcdNG5EF1AsQip0LcNB49erSDcPbxSqXSw263+7Db7T4cjUb3Abxi5tzNCYuCiLZKpdL9Uql0n5kfInqXRNRSdZK4bqlUuk9EW8zciYpOkvWijmsi7LRb7969u9/tdrfevXt3j4iOYh2//PJLU2WXEOJxUpcQ4jFCz8UcDofNyJZmsk5UPtUmXddtPbO7AwDE1Tat/6eAiHqK/cHG9SmEqwPKUCLt5kZcRcaWhU6FSEAXaA+T4UXCHQfwFjRJGXxhb3iNnHziWYhTV5n5YzLGjfiJ+sICZ0DXdS8VR9er1erPCGf4OlKdMlXXsyyrF7nFRqVSKV9eXvai9X4TOXvEu91uq1arGcx8EJGb7XSCDzP7aV2VSsUgIkdKWY5s8ZE4USROLkq3SZnbrGHkq67/wPWjaKLD1G4ZQiKPdsFTIab1jnUns32IxKeX9q2F5KkgpfSICET0q2VZ5k2TVET0kZktovmH1rmu69dqNY+Zy5qmmQB6RGFG2Kw94t1utxm52rnkZhpCCI+ZIYT4V/HWrHRX0X8e0pscIvhF7iXG4Z4TTE6XKJKtxlRWHYKfPlResVvmCAQkiK+5qYarBIOM9PGlHO5wmqq3trbWGQwGxwCMwWDwqVKpdBCmoLo31JEL7TtOg3mcsWZF//Zm1SeiV8x8kPP9qOQXqpfGzXReATc6xmUMScttkmeSHZLCy8hfMSzLMgaDgSpP2lNcy4DDs70WBBvq40mn83iHkndAYccIc3Y33MZZMDkyhak8b2vbasFTp1UgtiwF13X9SqXyLE6gidj8ncFggGq1ehIxrv51WBi9zzIQhkPz6m9ubppxp5JSTtmkYqBT8AGAmWfOpJZlGcPhsMzMB5Gembuk0riRzhu5cO4qZJ3ZdzrIOT1/VahUKuVohjAVxb3r1F0ERHQwHhfi0yAE3EmyzfhUiJMbN24OLi8v25ubm66maTtEtJuYdZpR51p4G6UKw+Fwu1Kp/DcACCHMwWCwjSheVR1EmMxf1zTtgZTjo1u9dGe1LMtYdpBh5os4hh0MBskib21tzV1EVm7nrdVqLWa+vpTG/6MQQigT3NMId1RNNghEbrM156aeRJZx1WjUi/+vOvK0cRa0IAFQ4pC08FSIkyK2Xh3kE43ZWQAx662OLSOC6gTASbT00kIYw1u1Ws3qdruulNKP1k9NlQwhxkfn+qpyZj6hyDtJkI6+EOKZykUnorHbL+XYTfRKpVJyMPEQssJlzJiMmDneVlhoSywzd9bW1pR2zcKPmHcxeL///nunSEUGP0+SRk+coJ53SFriLv/cnr9bJumNM8vEhvOEq1rgVIhVgcD+S3tjiiV/6gQWKdjwNGKmuVarPWDmMhGZALC2tuZFM5OZM9OVgXwXOLruI0y48AF8LJVKGfY3Ud8FAObwVE9mfpveDkpEb6JYNpdT2NzcNBEN0qVSqZMuF0I8jmf+wWBwgXDji7FMzP+j8y4AIUTu2VOrADHMJ456j7PEeqdtkx/NaIXk5Z0KMQSc9FKPDtirPBRPhWiWfV0qlR6nP9aQ4AIQzaQR29tj5vJgMDhE4qiYKH6NvULl7KbrekbHLKiyuBToINwvblUqlcP0LrNEAgeIqKPSn1wqqtVqNjNfALAePXq0U3RiiPGj8xYEER0t+nAXBQOmyNndJajv7TmBmVzbJRJtltOHnYH4IHZXoz+i1VIosjLXVrETdA6itMjyYDD4VK1W23EIIqWM41Ek41Ep5fPInW1Wq1WDmd8CQEQkmgB8XddPrt/yEN1u163VakfMfEhErWq1asZtGI1GD4ioiTAPwNN1fe5AH8l7zswHUspjy7LcRWLpH513Pjwism866VyF5NpuyDJnc8Ofnn8zKDpNZPxHtGS61vdBtNc6zlCqJ0ghAOEAmZytLi8v27VazYzc1DoRJev7Qojcv5d8XYgSMRDbFLchjq8RnsJSeNbXdb01GAx2AZhpD2PuvXm7hqI4oZAB/2mI4qU/AXSKdlqC5jON3OTv0+XST54xHP91AGL+yLTAtBed+sA5x8BERw89mFSHCQGP57D9mb9WkDhdQnXif8puL10uQD2mMH4UCOPSeKdXpVKpCyG2AZjMbETP+7nqWXe73ValUvFS9d/oun6SQzz1Inv8dJkKcay7CLrdbmtzc7OtaVqLiB4k2vAxsiujO7Yrvezkuq5frVaPIi+prEpeyWvT/wKx6y4ZXYJC+AAAAABJRU5ErkJggg=='; // Replace with base64 logo image

    function downloadPDF() {
        const element = document.getElementById("pdf-content");
        const images = element.querySelectorAll("img");

        const promises = Array.from(images).map(img => {
            return toDataURL(img.src).then(dataUrl => {
                img.setAttribute("src", dataUrl);
            }).catch(err => {
                console.warn("Image failed to load as base64:", img.src);
            });
        });

        Promise.all(promises).then(() => {
            // Set margins (in inches: 1in = 25.4mm = 72pt)
            const topMargin = 0.3;    // ~15mm
            const bottomMargin = 1.0; // ~18mm (footer + buffer)
            const leftRightMargin = 0.3;

            html2pdf()
                .set({
                    margin: [topMargin, leftRightMargin, bottomMargin, leftRightMargin],
                    filename: 'print-plan.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                })
                .from(element)
                .toPdf()
                .get('pdf')
                .then(pdf => {
                    const totalPages = pdf.internal.getNumberOfPages();
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();

                    // Footer settings
                    const footerHeight = 0.5; // in inches
                    const footerY = pageHeight - bottomMargin + 0.15; // 0.15in above page bottom

                    // Logo
                    const logoWidth = 1.2;
                    const logoHeight = 0.2;
                    const logoX = 0.5;
                    const logoY = footerY + (footerHeight - logoHeight) / 2;

                    // Circle (page number)
                    const circleRadius = 0.11;
                    const circleCenterX = pageWidth / 2;
                    const circleCenterY = footerY + footerHeight / 2;

                    // Right-side text
                    const dateText = `Nutrition Training Plan | ${new Date().toLocaleDateString('en-GB')}`;
                    const dateFontSize = 9; // smaller font
                    const dateColor = "#649ef7"; // blue
                    const dateX = pageWidth - 0.5;
                    const dateY = circleCenterY + 0.04; // align with circle

                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);

                        // Draw a white rectangle to clear the footer area
                        pdf.setFillColor(255, 255, 255);
                        pdf.rect(
                            0,
                            pageHeight - bottomMargin,
                            pageWidth,
                            footerHeight,
                            'F'
                        );

                        // Add logo (left, vertically centered)
                        if (window.logoBase64) {
                            pdf.addImage(window.logoBase64, 'PNG', logoX, logoY, logoWidth, logoHeight);
                        }

                        // Draw blue circle for page number (center)
                        pdf.setDrawColor(0, 116, 217); // blue border (optional)
                        pdf.setFillColor(0, 116, 217); // blue fill
                        pdf.circle(circleCenterX, circleCenterY, circleRadius, 'F');

                        // Page number in white, centered in the circle
                        pdf.setTextColor(255, 255, 255);
                        pdf.setFontSize(9);
                        pdf.setFont(undefined, 'normal');
                        // Center vertically and horizontally
                        pdf.text(`${i}`, circleCenterX, circleCenterY, { align: 'center', baseline: 'middle' });

                        // Date text (right, blue, smaller font, vertically centered)
                        pdf.setTextColor(0, 116, 217); // blue
                        pdf.setFontSize(dateFontSize);
                        pdf.setFont(undefined, 'normal');
                        pdf.text(dateText, dateX, dateY, { align: 'right', baseline: 'middle' });
                    }
                })
                .save();
        });
    }

    // Helper to convert images to base64
    function toDataURL(url) {
        return fetch(url, { mode: 'cors' })
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    const user = @json($userPlan);
    const userId = user.user_id;    
    const userPlanId = user.id;
    console.log('user_plan_id:' , userPlanId);
    console.log('user_id:' , userId);
    $(document).ready(function () {
        $('body').on('click', '.meal-modal-close', function () {
            $('#mealModel').modal('hide');
        })
        $('body').on('click', '.meal-item-model-close', function () {
            $('#mealItemModel').modal('hide');
            $('#mealModel').modal('show');
        })

        $('.item-swap-modal-close').on('click', function () {
            $('#itemSwapModal').modal('hide');
            $('#mealItemModel').modal('show');

        })

        const $mealModel = $('#mealModel');
        const $mealModalLabel = $('#mealModalLabel');
        const $mealModelContainer = $('#mealModelContainer');
        const $mealModelLoadingSpinner = $('#mealModelLoadingSpinner');

        const $mealItemsModal = $('#mealItemModel');
        const $mealItemsModalLabel = $('#mealItemsModalLabel');
        const $mealItemsContainer = $('#mealItemsContainer');
        const $mealItemsLoadingSpinner = $('#mealItemsLoadingSpinner');

        const $itemSwapModel = $('#itemSwapModal');
        const $itemsSwapModalLabel = $('#itemsSwapModalLabel');
        const $itemsSwapContainer = $('#itemsSwapContainer');
        const $itemsSwapLoadingSpinner = $('#itemsSwapLoadingSpinner');

        $('body').on('click', '.view-details-btn', function () {
            const subCategoryId = $(this).data('sub-category-id');
            const subCategoryName = $(this).data('sub-category-name');
            const userCategoryId = $(this).data('user-category-id');
            const userPlanId = $(this).data('user-plan-id');
            if (!subCategoryId || !userPlanId) {
                console.error('Invalid category data.');
                return;
            }

            $mealModalLabel.text(subCategoryName);

            $mealModelContainer.empty().hide();
            $mealModelLoadingSpinner.show();

            $.ajax({
                url: '{{ route('front.category.meals', ':id') }}'.replace(':id', subCategoryId) + `?user_category_id=${userCategoryId}&user_plan_id=${userPlanId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.meals && data.meals.length > 0) {
                        
                        let mealCard = '';
                        $.each(data.meals, function (index, meal) {
                            mealCard = `
                            <div class="col-sm-6 col-lg-4">
                            <div class="nutrition-plan-box h-100 d-flex flex-column">
                                <figure>
                                    <img src="${meal.image}" alt="">
                                    <div class="nutrition-plan-box-overlay">
                                        <div class="nutrition-plan-box-text">
                                            <p>${meal.description}</p>
                                        </div>
                                    </div>
                                </figure>
                                <h5 class="mb-3">${meal.name}</h5>
                                <button type="button" class="view-items-btn btn btn-primary mt-auto" data-user-meal-id="${meal.id}" data-meal-id="${meal.id}" data-user-plan-id="${userPlanId}" data-category-id="${userCategoryId}" data-sub-category-id="${meal.user_sub_category_id}"
                                data-meal-name="${meal.name}">
                                    <svg class="me-2" width="25" height="25" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                        <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                    </svg>Smart Swaps
                                </button>
                            </div>
                        </div>`;
                            $mealModelContainer.append(mealCard);
                        });
                        
                        const purchaseMoreCard = `
                            <div class="col-sm-6 col-lg-4 d-none">
                                <div class="nutrition-plan-box h-100 d-flex flex-column justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary mt-auto purchase-more-meals-btn">
                                        Purchase More Meals
                                    </button>
                                </div>
                            </div>`;
                        $mealModelContainer.append(purchaseMoreCard);
                        
                    } else {
                        $mealModelContainer.html('<p class="text-center">No meals available.</p>');
                    }

                    // Hide loading spinner and show subcategories
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meals:', error);
                    $mealModelContainer.html('<p class="text-center text-danger">Failed to load meals.</p>');
                    $mealModelLoadingSpinner.hide();
                    $mealModelContainer.show();
                }
            });

            $mealModel.modal('show');
        });

        // Handle click event to fetch meal items
        $('body').on('click', '.view-items-btn', function () {
            const mealId = $(this).data('meal-id');
            const mealName = $(this).data('meal-name');
            const userMealId = $(this).data('user-meal-id');
            const userPlanId = $(this).data('user-plan-id');
            const userSubCategoryId = $(this).data('sub-category-id');
            const userCategoryId = $(this).data('category-id');

            $.ajax({
                url: '{{ route("front.track.click") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    meal_id: mealId,
                    meal_name: mealName,
                    user_meal_id: userMealId,
                    user_plan_id: userPlanId,
                    user_sub_category_id: userSubCategoryId,
                    user_category_id: userCategoryId,
                    user_id: userId
                },
                success: function (response) {
                    console.log('Click tracked successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('Error tracking click:', error);
                }
            });
            
            if (!mealId || !mealName) {
                console.error('Invalid meal data.');
                return;
            }

            currentMealId = mealId;
            currentMealName = mealName;

            $mealItemsModalLabel.text(mealName);
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            $.ajax({
                url: '{{ route('front.meals.items', ':mealId') }}'.replace(':mealId', mealId) + `?user_meal_id=${userMealId}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function (index, item) {
                            let displayQty = '';

                            // Normalize selected_qty_unit
                            let selectedUnits = [];
                            try {
                                if (typeof item.selected_qty_unit === 'string') {
                                    selectedUnits = JSON.parse(item.selected_qty_unit);
                                } else if (Array.isArray(item.selected_qty_unit)) {
                                    selectedUnits = item.selected_qty_unit;
                                }
                            } catch (e) {
                                console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                            }

                            if (Array.isArray(selectedUnits)) {
                                const checkedUnits = selectedUnits.filter(u => {
                                    const isChecked = u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1";
                                    return isChecked;
                                });

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').toString().trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                        // Check if qtyText is a valid number, otherwise preserve as-is (e.g., "1/4")
                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }
                            }

                            // Fallback
                            if (!displayQty && item.qty && item.unit) {
                                const unit = item.unit.toString();
                                const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
                                displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                            }

                            let infoButton = '';
                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1 info-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}"
                                    data-description="${item.description}"
                                    data-note="${item.note}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }

                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `<button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}" data-user-plan-id="${userPlanId}" data-sub-category-id="${userSubCategoryId}" data-user-category-id="${userCategoryId}">
                                    <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                        <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                    </svg>
                                    Swap
                                </button>`
                                : '';

                            const itemCard = `
                                <div class="category-swap-list-box">
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${item.image}" alt="">
                                        </figure>
                                        <div class="info-tootlip">
                                            <p>Food Details</p>
                                            <ul>
                                                <li>Protein: ${item.protein}g</li>
                                                <li>Carbs: ${item.carbs}g</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="align-items-center d-flex m-0 mt-2">
                                            <strong class="me-2 text-nowrap">Qty :</strong>
                                            <span class="m-0">${displayQty}</span>
                                        </p>
                                    </div>
                                    <div class="category-swap-btn">
                                        ${infoButton}
                                        ${swapButton}
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                    }

                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meal items:', error);
                    $mealItemsContainer.html('<p class="text-center text-danger">Failed to load foods.</p>');
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                }
            });

            $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        });

        function formatQty(qty) {
            if (typeof qty === 'string' && /^\d+\/\d+$/.test(qty)) {
                return qty; // keep fraction as-is
            }

            const floatVal = parseFloat(qty);
            if (isNaN(floatVal)) return qty;

            return floatVal % 1 === 0 ? floatVal.toFixed(0) : floatVal.toFixed(1);
        }

        // Handle click event to feth swap items
        $('body').on('click', '.item-swap-btn', function () {
            $('#mealItemModel').modal('hide');
            const itemId = $(this).data('item-id');
            const itemName = $(this).data('item-name');
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
            const userPlanId = $(this).data('user-plan-id');
            const userSubCategoryId = $(this).data('sub-category-id');
            const userCategoryId = $(this).data('user-category-id');

            if (!itemId || !itemName) {
                console.error('Invalid item data.');
                return;
            }
            
            $('.apply-changes-btn').attr('data-user-item-id', userItemId);
            $('.apply-changes-btn').attr('data-user-meal-id', userMealId);
            $('.apply-changes-btn').attr('data-user-plan-id', userPlanId);
            $('.apply-changes-btn').attr('data-user-sub-category-id', userSubCategoryId);
            $('.apply-changes-btn').attr('data-user-category-id', userCategoryId);

            // Update modal title
            $itemsSwapModalLabel.text(itemName);

            // Clear previous subcategories and show loading spinner
            $itemsSwapContainer.empty().hide();
            $itemsSwapLoadingSpinner.show();

            // Fetch subcategories via AJAX
            $.ajax({
                url: '{{ route('front.items.swap-items', ':id') }}'.replace(':id', itemId) + `?user_meal_id=${userMealId}&user_item_id=${userItemId}&user_plan_id=${userPlanId}&sub_category_id=${userSubCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    const $itemsSwapContainer = $('#itemsSwapContainer');
                    $itemsSwapContainer.empty();

                    if (data.items && data.items.length > 0) {
                        // MAIN ITEM HTML
                        let mainItemHTML = `
                            <div class="main-item-box category-swap-list-box">
                                <div class="category-swap-img">
                                    <figure>
                                        <img class="img-thumbnail main-item-img" data-main-id="${data.item_id}" src="${data.item_image}" alt="">
                                    </figure>
                                    <figcaption>${data.item_name}</figcaption>
                               
                                    ${data.item.description ? `
                                        <button class="btn btn-primary rounded-pill py-1 px-4 d-flex align-items-center m-1"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="${data.item.description}"
                                            data-item-id="${data.item_id}"
                                            data-item-name="${data.item_name}"
                                            data-description="${data.item.description}">
                                            <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                <path d="M8 7.8C7.6 7.8 7.3 8.1 7.3 8.5V11.4C7.3 11.8 7.6 12.1 8 12.1C8.4 12.1 8.7 11.8 8.7 11.4V8.5C8.7 8.1 8.4 7.8 8 7.8Z" fill="white"/>
                                                <path d="M8 4.9C7.6 4.9 7.3 5.2 7.3 5.6C7.3 6 7.6 6.3 8 6.3C8.4 6.3 8.7 6 8.7 5.6C8.7 5.2 8.4 4.9 8 4.9Z" fill="white"/>
                                            </svg>
                                            Info
                                        </button>
                                    ` : ''
                                    } </div>
                            </div>`;

                        // SWAP ITEMS HTML
                        let swapItemsHTML = `<div class="swap-items-container">`;

                        $.each(data.items, function (index, swapitem) {
                            swapItemsHTML += `
                                <div class="category-item-swap category-swap-list-box swap-item" data-swap-id="${swapitem.swap_item_id}">
                                    <div class="category-swap-btn mx-auto">
                                        <button class="swap-button btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" 
                                            data-swap-item-id="${swapitem.swap_item_id}" 
                                            data-swap-item-name="${swapitem.swap_item_name}" 
                                            data-swap-item-img="${swapitem.swap_item_image}" 
                                            data-swap-item-protein="${swapitem.swap_item_protein}" 
                                            data-swap-item-carbs="${swapitem.swap_item_carbs}"
                                            data-user-item-id="${userItemId}">
                                            <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                                <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                            </svg>
                                            Swap
                                        </button>
                                    </div>
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${swapitem.swap_item_image}" alt="">
                                        </figure>
                                        <figcaption>${swapitem.swap_item_name}</figcaption>
                                   
                                        ${swapitem.swap_item_description ? `
                                            <button class="btn btn-primary rounded-pill py-1 px-4 d-flex align-items-center m-1"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="${swapitem.swap_item_description}"
                                                data-item-id="${swapitem.swap_item_id}"
                                                data-item-name="${swapitem.swap_item_name}">
                                                <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                                    <path d="M8 7.8C7.6 7.8 7.3 8.1 7.3 8.5V11.4C7.3 11.8 7.6 12.1 8 12.1C8.4 12.1 8.7 11.8 8.7 11.4V8.5C8.7 8.1 8.4 7.8 8 7.8Z" fill="white"/>
                                                    <path d="M8 4.9C7.6 4.9 7.3 5.2 7.3 5.6C7.3 6 7.6 6.3 8 6.3C8.4 6.3 8.7 6 8.7 5.6C8.7 5.2 8.4 4.9 8 4.9Z" fill="white"/>
                                                </svg>
                                                Info
                                            </button>
                                            ` : ''
                                        } 
                                    </div>
                                </div>`;
                        });

                        swapItemsHTML += `</div>`;

                        $itemsSwapContainer.append(`
                            <div class="d-flex justify-content-between row">
                                <div class="col-4 p-2">${mainItemHTML}</div>
                                <div class="col-8 p-2">${swapItemsHTML}</div>
                            </div>
                        `);
                    } else {
                        $itemsSwapContainer.html('<p class="text-center">No swap items available.</p>');
                    }

                    $('#itemsSwapLoadingSpinner').hide();
                    $itemsSwapContainer.show();
                    $('[data-bs-toggle="tooltip"]').tooltip(); // Initialize Bootstrap tooltip
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching swap items:', error);
                    $itemsSwapContainer.html('<p class="text-center text-danger">Failed to load swap items.</p>');
                    $('#itemsSwapLoadingSpinner').hide();
                    $itemsSwapContainer.show();
                }
            });

            // Show the modal
            $itemSwapModel.modal('show');
        });

        // Store the swaps in memory for applying changes

        // // When the swap button is clicked
        // $('body').on('click', '.swap-button', function () {

        //     // Reset the swaps array to an empty array
        //     swaps = [];
        //     // Get the current container of the swap button
        //     const categoryItemSwap = $(this).closest('.category-item-swap');
            
        //     // Extract the data attributes (main and swap item ids)
        //     const mainItemId = categoryItemSwap.data('main-id');
        //     const swapItemId = categoryItemSwap.data('swap-id');

        //     // Displaying the current IDs for debugging (you can remove this later)
        //     console.log('Main Item ID:', mainItemId);
        //     console.log('Swap Item ID:', swapItemId);

        //     // Assuming you're storing the item data in a swaps array or similar object for later use:
        //     const existingSwapIndex = swaps.findIndex(swap => swap.main_id === mainItemId);
        //     console.log('Existing Swap Index:', existingSwapIndex);
        //     //If the swap exists, update it. Otherwise, push a new swap.
        //     // if (existingSwapIndex !== -1) {
        //     //     swaps[existingSwapIndex] = { main_id: swapItemId, swap_id: mainItemId };
        //     // } else {
        //     //     swaps.push({ main_id: swapItemId, swap_id: mainItemId });
        //     // }
        //     swaps.push({ main_id: swapItemId, swap_id: mainItemId });

        //     // Update the UI (swap images and names)
        //     const mainItemName = categoryItemSwap.find('figcaption:first').text();
        //     const mainItemImage = categoryItemSwap.find('img:first').attr('src');
        //     const swapItemName = categoryItemSwap.find('figcaption:last').text();
        //     const swapItemImage = categoryItemSwap.find('img:last').attr('src');

        //     // Swapping the content dynamically
        //     categoryItemSwap.find('figcaption:first').text(swapItemName);
        //     categoryItemSwap.find('img:first').attr('src', swapItemImage);
        //     categoryItemSwap.find('figcaption:last').text(mainItemName);
        //     categoryItemSwap.find('img:last').attr('src', mainItemImage);

        //     // Optional: Close the modal if it's used for this action
        // //    $('#swapModal').modal('hide');
        // });

        // Store the current swap in memory
        let activeSwap = null;
        let swaps = [];

        $('body').on('click', '.swap-button', function () {
            swaps = [];

            if (activeSwap) {
                resetPreviousSwap(activeSwap);
                activeSwap = null;
            }

            const $btn = $(this);
            const $swapItem = $btn.closest('.category-item-swap');
            const $mainItem = $('.main-item-box');

            // Get item details
            const mainImage = $mainItem.find('.main-item-img');
            const mainName = $mainItem.find('figcaption');
            const mainProtein = $mainItem.find('.main-item-protein');
            const mainCarbs = $mainItem.find('.main-item-carbs');

            const swapImage = $swapItem.find('img');
            const swapName = $swapItem.find('figcaption');
            const swapProtein = $swapItem.find('.swap-item-protein');
            const swapCarbs = $swapItem.find('.swap-item-carbs');

            // Info buttons
            const $mainInfoBtn = $mainItem.find('button[data-bs-toggle="tooltip"]');
            const $swapInfoBtn = $swapItem.find('button[data-bs-toggle="tooltip"]');

            // Save old content
            const oldMain = {
                src: mainImage.attr('src'),
                name: mainName.text(),
                protein: mainProtein.text(),
                carbs: mainCarbs.text(),
                infoHtml: $mainInfoBtn.length ? $mainInfoBtn.prop('outerHTML') : null
            };

            const newSwap = {
                src: swapImage.attr('src'),
                name: swapName.text(),
                protein: swapProtein.text(),
                carbs: swapCarbs.text(),
                infoHtml: $swapInfoBtn.length ? $swapInfoBtn.prop('outerHTML') : null
            };

            // Swap core info
            mainImage.attr('src', newSwap.src);
            mainName.text(newSwap.name);
            mainProtein.text(newSwap.protein);
            mainCarbs.text(newSwap.carbs);

            swapImage.attr('src', oldMain.src);
            swapName.text(oldMain.name);
            swapProtein.text(oldMain.protein);
            swapCarbs.text(oldMain.carbs);

            // Swap Info buttons
            if (newSwap.infoHtml) {
                $mainInfoBtn.remove();
                $mainItem.find('.category-swap-img').append(newSwap.infoHtml);
            } else {
                $mainInfoBtn.remove();
            }

            if (oldMain.infoHtml) {
                $swapInfoBtn.remove();
                $swapItem.find('.category-swap-img').append(oldMain.infoHtml);
            } else {
                $swapInfoBtn.remove();
            }

            // Re-initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Store for reset
            activeSwap = {
                mainImage, mainName, mainProtein, mainCarbs,
                swapImage, swapName, swapProtein, swapCarbs,
                originalMain: oldMain,
                originalSwap: newSwap
            };

            swaps.push({
                main_id: $btn.data('swap-item-id'),
                swap_id: mainImage.data('main-id'),
                user_item_id: $btn.data('user-item-id')
            });

            console.log('Swaps:', swaps);
        });

        function resetPreviousSwap(swapData) {
            if (!swapData) return;

            console.log('Resetting previous swap:', swapData);

            // Restore original main item details
            swapData.mainImage.attr('src', swapData.originalMain.src);
            swapData.mainName.text(swapData.originalMain.name);
            swapData.mainProtein.text(swapData.originalMain.protein);
            swapData.mainCarbs.text(swapData.originalMain.carbs);

            // Remove and restore info button on main item
            swapData.mainImage.closest('.category-swap-img').find('button[data-bs-toggle="tooltip"]').remove();
            if (swapData.originalMain.infoHtml) {
                swapData.mainImage.closest('.category-swap-img').append(swapData.originalMain.infoHtml);
            }

            // Restore original swap item details
            swapData.swapImage.attr('src', swapData.originalSwap.src);
            swapData.swapName.text(swapData.originalSwap.name);
            swapData.swapProtein.text(swapData.originalSwap.protein);
            swapData.swapCarbs.text(swapData.originalSwap.carbs);

            // Remove and restore info button on swap item
            swapData.swapImage.closest('.category-swap-img').find('button[data-bs-toggle="tooltip"]').remove();
            if (swapData.originalSwap.infoHtml) {
                swapData.swapImage.closest('.category-swap-img').append(swapData.originalSwap.infoHtml);
            }

            // Re-initialize tooltips after restoring
            $('[data-bs-toggle="tooltip"]').tooltip();
        }


        // $('body').on('click', '.swap-button', function () {
        //     swaps = [];

        //     // Reset previous swap if exists
        //     if (activeSwap) {
        //         resetPreviousSwap(activeSwap);
        //         activeSwap = null; // Clear active swap after reset
        //     }

        //     // Get the clicked swap item container
        //     const swapItemContainer = $(this).closest('.category-item-swap');

        //     // Extract swap item details
        //     const swapItemId = $(this).data('swap-item-id');
        //     const swapItemName = $(this).data('swap-item-name');
        //     const swapItemImage = $(this).data('swap-item-img');
        //     const swapItemProtein = $(this).data('swap-item-protein');
        //     const swapItemCarbs = $(this).data('swap-item-carbs');

        //     let user_item_id = $(this).data('user-item-id');

        //     // Get the main item container
        //     const mainItemContainer = $('.main-item-box');
        //     const mainItemImageElem = mainItemContainer.find('.main-item-img');
        //     const mainItemNameElem = mainItemContainer.find('figcaption');
        //     const mainItemProteinElem = mainItemContainer.find('.main-item-protein');
        //     const mainItemCarbsElem = mainItemContainer.find('.main-item-carbs');

        //     // Store current main item details
        //     const mainItemId = mainItemImageElem.attr('data-main-id');
        //     const mainItemName = mainItemNameElem.text();
        //     const mainItemImage = mainItemImageElem.attr('src');
        //     const mainItemProtein = mainItemProteinElem.text();
        //     const mainItemCarbs = mainItemCarbsElem.text();

        //     console.log('Main Item Before Swap:', mainItemName, mainItemImage);
        //     console.log('Swap Item Before Swap:', swapItemName, swapItemImage);

        //     // Swap main item with the selected swap item
        //     mainItemImageElem.attr('src', swapItemImage);
        //     mainItemNameElem.text(swapItemName);
        //     mainItemProteinElem.text(`Protein: ${swapItemProtein}g`);
        //     mainItemCarbsElem.text(`Carbs: ${swapItemCarbs}g`);

        //     // Store original swap item details before replacing it
        //     const originalSwapItemImage = swapItemContainer.find('img').attr('src');
        //     const originalSwapItemName = swapItemContainer.find('figcaption').text();
        //     const originalSwapItemProtein = swapItemContainer.find('.info-tootlip ul li:nth-child(1)').text();
        //     const originalSwapItemCarbs = swapItemContainer.find('.info-tootlip ul li:nth-child(2)').text();

        //     // Swap the selected swap item with the previous main item
        //     swapItemContainer.find('img').attr('src', mainItemImage);
        //     swapItemContainer.find('figcaption').text(mainItemName);
        //     swapItemContainer.find('.info-tootlip ul').html(`
        //         <li>${mainItemProtein}</li>
        //         <li>${mainItemCarbs}</li>
        //     `);

        //     console.log('Main Item After Swap:', swapItemName, swapItemImage);
        //     console.log('Swap Item After Swap:', mainItemName, mainItemImage);

        //     // Store swap data for tracking & reset handling
        //     activeSwap = {
        //         mainContainer: mainItemContainer,
        //         swapContainer: swapItemContainer,
        //         mainItemId: mainItemId,
        //         swapItemId: swapItemId,
        //         mainItemName: mainItemName,
        //         mainItemImage: mainItemImage,
        //         mainItemProtein: mainItemProtein,
        //         mainItemCarbs: mainItemCarbs,
        //         originalSwapItemImage: originalSwapItemImage,
        //         originalSwapItemName: originalSwapItemName,
        //         originalSwapItemProtein: originalSwapItemProtein,
        //         originalSwapItemCarbs: originalSwapItemCarbs
        //     };

        //     swaps.push({
        //         main_id: swapItemId,
        //         swap_id: mainItemId,
        //         user_item_id: user_item_id
        //     });

        //     console.log('Swaps:', swaps);
        // });

        // Function to reset previous swap properly
        

        // Apply Changes functionality
        $('body').on('click', '.apply-changes-btn', function () {
            // Send all swaps to the server
            const userItemId = $(this).data('user-item-id');
            const userMealId = $(this).data('user-meal-id');
            const userPlanId = $(this).data('user-plan-id');
            const userSubCategoryId = $(this).data('user-sub-category-id');
            const userCategoryId = $(this).data('user-category-id');
            console.log(userItemId);
            console.log('123');
            console.log(swaps);
            $.ajax({
                url: "{{ route('front.items.swaps') }}", // Laravel route to handle the request
                method: "GET",
                data: {
                    swaps: swaps,
                    meal_id: currentMealId,
                    user_item_id: userItemId,
                    user_meal_id: userMealId,
                    user_category_id: userCategoryId,
                    user_sub_category_id: userSubCategoryId,
                    user_plan_id: userPlanId,
                    user_id: userId,
                    // headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                },
                success: function (response) {
                    // Handle success response
                    console.log(response);
                    swaps = [];
                    $('#itemSwapModal').modal('hide');
                    if(response.success){
                        // alert("Swaps applied successfully!");
                        var meal_id = response.data['meal_id'];
                        var meal_name = response.data['meal_name'];
                        var user_meal_id = response.data['user_meal_id'];
                        mealItemModelReload(meal_id, meal_name, user_meal_id, userSubCategoryId, userPlanId, userCategoryId);
                    }
                    
                    // $('#mealItemModel').modal('show');
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error("Failed to apply swaps:", error);
                    alert("Failed to apply changes. Please try again.");
                }
            });
        });

        function mealItemModelReload(meal_id, meal_name, userMealId, userSubCategoryId, userPlanId, userCategoryId){
            console.log(meal_id, meal_name);
            // Update modal title
            $mealItemsModalLabel.text(meal_name);
            console.log('122');
            // Clear previous items and show loading spinner
            $mealItemsContainer.empty().hide();
            $mealItemsLoadingSpinner.show();

            // Fetch subcategory items via AJAX
            $.ajax({
                url: '{{ route('front.meals.items', ':meal_id') }}'.replace(':mealId', meal_id) + `?user_meal_id=${userMealId}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function (index, item) {
                            let displayQty = '';

                            // Normalize selected_qty_unit
                            let selectedUnits = [];
                            try {
                                if (typeof item.selected_qty_unit === 'string') {
                                    selectedUnits = JSON.parse(item.selected_qty_unit);
                                } else if (Array.isArray(item.selected_qty_unit)) {
                                    selectedUnits = item.selected_qty_unit;
                                }
                            } catch (e) {
                                console.warn('Failed to parse selected_qty_unit for item:', item.name, e);
                            }

                            if (Array.isArray(selectedUnits)) {
                                const checkedUnits = selectedUnits.filter(u => {
                                    const isChecked = u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1";
                                    return isChecked;
                                });

                                if (checkedUnits.length > 0) {
                                    const formattedUnits = checkedUnits.map(u => {
                                        let qtyText = u.qty?.toString().trim() || '';
                                        const unitText = (u.unit || '').toString().trim();
                                        const needsSpace = !["g", "ml", "mL"].includes(unitText.toLowerCase());

                                        // Check if qtyText is a valid number, otherwise preserve as-is (e.g., "1/4")
                                        const numericQty = Number(qtyText);
                                        if (!isNaN(numericQty)) {
                                            qtyText = numericQty % 1 === 0 ? numericQty.toFixed(0) : numericQty.toFixed(1);
                                        }

                                        return `${qtyText}${needsSpace ? ' ' : ''}${unitText}`;
                                    });

                                    displayQty = formattedUnits.join(' or ');
                                }
                            }

                            // Fallback
                            if (!displayQty && item.qty && item.unit) {
                                const unit = item.unit.toString();
                                const needsSpace = !["g", "ml", "mL"].includes(unit.toLowerCase());
                                displayQty = `${item.qty}${needsSpace ? ' ' : ''}${unit}`;
                            }

                            let infoButton = '';
                            if (item.description) {
                                infoButton = `<button class="btn btn-primary rounded-pill py-2 d-flex align-items-center m-1 info-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${item.description}" 
                                    data-item-id="${item.id}" 
                                    data-item-name="${item.name}"
                                    data-description="${item.description}"
                                    data-note="${item.note}">
                                    <svg class="me-2" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0.5C3.6 0.5 0 4.1 0 8.5C0 12.9 3.6 16.5 8 16.5C12.4 16.5 16 12.9 16 8.5C16 4.1 12.4 0.5 8 0.5ZM8 15C4.4 15 1.5 12.1 1.5 8.5C1.5 4.9 4.4 2 8 2C11.6 2 14.5 4.9 14.5 8.5C14.5 12.1 11.6 15 8 15Z" fill="white"/>
                                        <path d="M7.99999 7.79999C7.59999 7.79999 7.29999 8.09999 7.29999 8.49999V11.4C7.29999 11.8 7.59999 12.1 7.99999 12.1C8.39999 12.1 8.69999 11.8 8.69999 11.4V8.49999C8.69999 8.09999 8.39999 7.79999 7.99999 7.79999Z" fill="white"/>
                                        <path d="M7.99999 4.89999C7.59999 4.89999 7.29999 5.19999 7.29999 5.59999C7.29999 5.99999 7.59999 6.29999 7.99999 6.29999C8.39999 6.29999 8.69999 5.99999 8.69999 5.59999C8.69999 5.19999 8.39999 4.89999 7.99999 4.89999Z" fill="white"/>
                                    </svg>
                                    Info
                                </button>`;
                            }

                            const swapButton = item.swapItems && item.swapItems.length > 0
                                ? `<button class="item-swap-btn btn-swap btn btn-primary rounded-pill py-2 d-flex align-items-center m-1" data-bs-toggle="modal" data-bs-target="#subcategoryItemsModal3" data-item-id="${item.id}" data-item-name="${item.name}" data-user-item-id="${item.user_item_id}" data-user-meal-id="${item.user_meal_id}" data-user-plan-id="${userPlanId}" data-sub-category-id="${userSubCategoryId}" data-user-category-id="${userCategoryId}">
                                    <svg class="me-2" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.666667 8.5C1.06667 8.5 1.33333 8.23333 1.33333 7.83333V6.5C1.33333 5.36667 2.2 4.5 3.33333 4.5H11.0667L9.53333 6.03333C9.26666 6.3 9.26666 6.7 9.53333 6.96667C9.66666 7.1 9.8 7.16667 10 7.16667C10.2 7.16667 10.3333 7.1 10.4667 6.96667L13.1333 4.3C13.2 4.23333 13.2667 4.16667 13.2667 4.1C13.3333 3.96667 13.3333 3.76667 13.2667 3.56667C13.2 3.5 13.2 3.43333 13.1333 3.36667L10.4667 0.7C10.2 0.433333 9.8 0.433333 9.53333 0.7C9.26666 0.966667 9.26666 1.36667 9.53333 1.63333L11.0667 3.16667H3.33333C1.46667 3.16667 0 4.63333 0 6.5V7.83333C0 8.23333 0.266667 8.5 0.666667 8.5Z" fill="white"/>
                                        <path d="M12.6667 8.5C12.2667 8.5 12 8.76667 12 9.16667V10.5C12 11.6333 11.1333 12.5 9.99999 12.5H2.26666L3.79999 10.9667C4.06666 10.7 4.06666 10.3 3.79999 10.0333C3.53333 9.76667 3.13333 9.76667 2.86666 10.0333L0.199996 12.7C0.133329 12.7667 0.0666626 12.8333 0.0666626 12.9C-4.06429e-06 13.0333 -4.06429e-06 13.2333 0.0666626 13.4333C0.133329 13.5 0.133329 13.5667 0.199996 13.6333L2.86666 16.3C3 16.4333 3.13333 16.5 3.33333 16.5C3.53333 16.5 3.66666 16.4333 3.79999 16.3C4.06666 16.0333 4.06666 15.6333 3.79999 15.3667L2.26666 13.8333H9.99999C11.8667 13.8333 13.3333 12.3667 13.3333 10.5V9.16667C13.3333 8.76667 13.0667 8.5 12.6667 8.5Z" fill="white"/>
                                    </svg>
                                    Swap
                                </button>`
                                : '';

                            const itemCard = `
                                <div class="category-swap-list-box">
                                    <div class="category-swap-img">
                                        <figure>
                                            <img class="img-thumbnail" src="${item.image}" alt="">
                                        </figure>
                                        <div class="info-tootlip">
                                            <p>Food Details</p>
                                            <ul>
                                                <li>Protein: ${item.protein}g</li>
                                                <li>Carbs: ${item.carbs}g</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="category-swap-content">
                                        <h5 class="m-0">${item.name}</h5>
                                        <p class="align-items-center d-flex m-0 mt-2">
                                            <strong class="me-2 text-nowrap">Qty :</strong>
                                            <span class="m-0">${displayQty}</span>
                                        </p>
                                    </div>
                                    <div class="category-swap-btn">
                                        ${infoButton}
                                        ${swapButton}
                                    </div>
                                </div>`;
                            $mealItemsContainer.append(itemCard);
                        });
                    } else {
                        $mealItemsContainer.html('<p class="text-center">No foods available in this meal.</p>');
                    }

                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching meal items:', error);
                    $mealItemsContainer.html('<p class="text-center text-danger">Failed to load foods.</p>');
                    $mealItemsLoadingSpinner.hide();
                    $mealItemsContainer.show();
                }
            });

            // Show the modal
            // $('#mealModel').modal('hide');
            $mealItemsModal.modal('show');
        }
        $('#swapModal').on('hidden.bs.modal', function () {
            swaps = [];
            console.log('Swaps array cleared on modal close:', swaps);
        });

        // $('#swapModal').on('hidden.bs.modal', function () {
        //     $('#mealModel').modal('show');

        // });
    });

    $('body').on('click', '.info-btn', function () {
        const itemName = $(this).data('item-name'); 
        const itemDescription = $(this).data('description');
        const itemNote = $(this).data('note');
        console.log(itemName, itemDescription, itemNote);
        $('#infoModal .modal-title').text(itemName);
        $('#infoModal #modalDescription').text(itemDescription);
        $('#infoModal #modalNote').text(itemNote);
        $('#mealItemModel').addClass('blur-background');
        $('#infoModal').modal('show');
    });

    $('#infoModal').on('hidden.bs.modal', function () {
        $('#mealItemModel').removeClass('blur-background');
    });
    
    $(document).ready(function () {
        $(".print-plan-btn").click(function () {
            const planId = $(this).data("plan-id");
            const userId = $(this).data("user-id");
            // Set form action for download button
            $("#downloadPdfForm").attr("action", "{{ route('plans.generatePdf', ':id') }}".replace(':id', planId));

            $("#downloadPdfForm input[name='user_id']").val(userId);

            // Load the preview content from the controller
            $("#plan-preview-body").html('<div class="text-center">Loading preview...</div>');
            fetch("{{ route('plans.preview', ':id') }}".replace(':id', planId) + "?user_id=" + userId)
            .then(res => res.text())
                .then(html => {
                    console.log(html);
                    $("#plan-preview-body").html(html);
                    $("#planPreviewModal").modal("show"); // ✅ show modal
                    console.log('modal show');
                })
                .catch(err => {
                    $("#plan-preview-body").html('<div class="text-danger">Error loading preview</div>');
                });
        });
    });

    $(document).off('change', '#selectAllCheckbox').on('change', '#selectAllCheckbox', function () {
        let isChecked = $(this).is(':checked'); // Check if "Select All" is checked

        // Toggle all checkboxes based on the state of "Select All"
        $('.meal-item-checkbox').prop('checked', isChecked);
        $('.meal-checkbox').prop('checked', isChecked);
    });

        $(document).on('change', '.meal-item-checkbox', function () {
            let allItems = $('.meal-item-checkbox'); // All item checkboxes
            let allChecked = allItems.length === allItems.filter(':checked').length; // Check if all are selected

            // Set the global "Select All" checkbox state
            $('#selectAllCheckbox').prop('checked', allChecked);
        });

     $(document).on('click', '#fetchAllMeals', function () {
        $('#ShoppingModal .modal-body').html('<p>Loading...</p>');
        $('#ShoppingModal').modal('show');

        // let userPlanId = $(this).data('user-plan-id');
        console.log('userPlanId - :', userPlanId);
        $.ajax({
            url: '{{ route("front.get.meals.items") }}' + `?user_id=${userId}&user_plan_id=${userPlanId}`,
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                let modalContent = `
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                        <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                    </div>
                `;

                meals.forEach(meal => {
                    modalContent += `<h5 class="ms-3">${meal.category_title}</h5>`;
                    modalContent += `
                        <div class="ingredient-list">
                            <input type="checkbox" class="form-check-input mt-3 mx-3 meal-checkbox" id="mealCheckbox${meal.meal_id}">
                            <h2 class="d-inline-block px-0" style="border-bottom:none;">${meal.meal_title}</h2>
                            <hr class="m-0">
                            <ul>
                    `;

                    meal.items.forEach(item => {
                        let selectedQtyUnits = [];
                        try {
                            if (item.selected_qty_unit) {
                                selectedQtyUnits = JSON.parse(item.selected_qty_unit);
                            }
                            if (!Array.isArray(selectedQtyUnits)) {
                                selectedQtyUnits = [];
                            }
                        } catch (e) {
                            console.error(`Invalid selected_qty_unit JSON for item ${item.id}:`, e);
                            selectedQtyUnits = [];
                        }

                        const checkedUnits = selectedQtyUnits.filter(u =>
                            u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                        );

                        let qtyCheckboxes = '';
                        if (checkedUnits.length > 0) {
                            const qtyText = checkedUnits.map(unitObj => {
                                let rawQty = unitObj.qty;
                                let unit = unitObj.unit;
                                let qty = 0;

                                // Handle fractional quantities
                                if (!isNaN(rawQty)) {
                                    qty = parseFloat(rawQty);
                                } else if (typeof rawQty === 'string' && rawQty.includes('/')) {
                                    const parts = rawQty.split('/');
                                    if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                                        qty = parseFloat(parts[0]) / parseFloat(parts[1]);
                                    } else {
                                        qty = rawQty; // fallback
                                    }
                                }

                                if (['g', 'ml', 'mL'].includes(unit)) {
                                    return `${Math.round(qty)}${unit}`;
                                } else {
                                    const rounded = Math.round(qty * 100) / 100;
                                    let displayQty = rawQty;

                                    if (rounded === 0.25) displayQty = '¼';
                                    else if (rounded === 0.5) displayQty = '½';
                                    else if (rounded === 0.75) displayQty = '¾';

                                    return `${displayQty} ${unit}`;
                                }
                            }).join(' or ');
                            qtyCheckboxes = `<span>${qtyText}</span>`;
                        } else {
                            let qty = parseFloat(item.qty);
                            let unit = item.unit;
                            let displayQty = qty;

                            if (['g', 'ml', 'mL'].includes(unit)) {
                                qtyCheckboxes = `<span>${Math.round(qty)}${unit}</span>`;
                            } else {
                                const rounded = Math.round(qty * 100) / 100;
                                if (rounded === 0.25) displayQty = '¼';
                                else if (rounded === 0.5) displayQty = '½';
                                else if (rounded === 0.75) displayQty = '¾';

                                qtyCheckboxes = `<span>${displayQty} ${unit}</span>`;
                            }
                        }

                        modalContent += `
                            <li class="mb-3">
                                <div class="d-flex align-items-center ingredient-info">
                                    <div class="m-0">
                                        <input class="form-check-input meal-item-checkbox" type="checkbox" value="${item.id}" id="Check${item.id}">
                                        <input type="hidden" id="category" value="${item.category || ''}">
                                    </div>
                                    <div class="me-3 ingredient-img">
                                        <figure>
                                            <img src="{{ asset('private/public/storage') }}/${item.image || ''}" alt="">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span><strong>${item.title}</strong></span>
                                        <div class="mt-1 d-flex flex-wrap align-items-center">
                                            <strong class="me-2">QTY:</strong> ${qtyCheckboxes}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                    modalContent += `</ul></div>`;
                });

                $('#ShoppingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShoppingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
            const listItem = $(this).closest('li');
            const itemName = listItem.find('.ingredient-info span strong').text().trim() || "Unknown Item";
            const category = listItem.find('input[type="hidden"]#category').val()?.trim() || "Uncategorized";

            const qtyContainer = listItem.find('.ingredient-info .flex-grow-1 > div.d-flex');
            if (qtyContainer.length === 0) return;

            const fullText = qtyContainer.text().trim();
            const qtyTextMatch = fullText.match(/QTY:\s*(.+)/i);
            if (!qtyTextMatch) return;

            const qtyText = qtyTextMatch[1];
            const qtyParts = qtyText.split(" or ").map(part => part.trim());

            qtyParts.forEach(part => {
                // Match values like: ½ piece OR 1/2 piece OR 1 piece
                const match = part.match(/^([\d¼½¾/.]+)\s*([a-zA-Z\s]+)$/);
                if (!match) return;

                let qtyRaw = match[1].trim();
                let unit = match[2].trim();

                // Convert unicode fraction to numeric
                const unicodeFractions = {
                    '¼': 0.25,
                    '½': 0.5,
                    '¾': 0.75
                };

                let qty = unicodeFractions[qtyRaw] ?? null;

                // If still null, try numeric or x/y string
                if (qty === null) {
                    if (qtyRaw.includes('/')) {
                        const parts = qtyRaw.split('/');
                        if (parts.length === 2) {
                            const numerator = parseFloat(parts[0]);
                            const denominator = parseFloat(parts[1]);
                            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                                qty = numerator / denominator;
                            }
                        }
                    } else {
                        qty = parseFloat(qtyRaw);
                    }
                }

                if (!qty || isNaN(qty)) return;

                if (!aggregatedItems[category]) aggregatedItems[category] = {};
                if (!aggregatedItems[category][itemName]) aggregatedItems[category][itemName] = {};
                if (!aggregatedItems[category][itemName][unit]) aggregatedItems[category][itemName][unit] = 0;

                aggregatedItems[category][itemName][unit] += qty;
            });
        });

        // Generate HTML content
        let printListContent = '';
        for (let [category, items] of Object.entries(aggregatedItems)) {
            printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;

            for (let [itemName, unitMap] of Object.entries(items)) {
                const qtyText = Object.entries(unitMap).map(([unit, total]) => {
                    if (['g', 'ml', 'mL'].includes(unit)) {
                        return `${Math.round(total)}${unit}`;
                    } else {
                        const roundedTotal = Math.round(total * 100) / 100;
                        let fraction = '';

                        // Convert to nearest known fraction
                        if (roundedTotal === 0.25) fraction = '¼';
                        else if (roundedTotal === 0.5) fraction = '½';
                        else if (roundedTotal === 0.75) fraction = '¾';
                        else fraction = roundedTotal;

                        return `${fraction} ${unit}`;
                    }
                }).join(' or ');

                printListContent += `
                    <li style="margin: 0; padding: 4px 0; font-size: 16px; line-height: 1.8; display: flex; align-items: center;">
                        <span style="display: inline-block; width: 26px; font-size: 40px; line-height: 1;">&#9633;</span>
                        <span style="flex: 1;">${itemName} <strong>QTY:</strong> ${qtyText}</span>
                    </li>
                `;
            }

            printListContent += `</ul><br/>`;
        }

        if (printListContent === '') {
            printListContent = '<p>No items selected.</p>';
        }

        $('#ShippingPrintModal .print-list').html(printListContent);
    });

    $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
        let pdfContent = '';

        $('#ShippingPrintModal .print-list h6').each(function () {
            const categoryTitle = $(this).text().trim();
            const itemList = $(this).next('ul');

            let itemsHtml = '';

            itemList.find('li').each(function () {
                const itemHtml = $(this).html(); // ✅ Keep the existing square + text
                itemsHtml += `<li style="list-style-type: none;">${itemHtml}</li>`;
            });

            pdfContent += `
                <div>
                    <h6 style="margin-bottom: 5px;">${categoryTitle}</h6>
                    <ul style="padding-left: 20px;">
                        ${itemsHtml}
                    </ul>
                </div><br/>
            `;
        });

        if (pdfContent.trim() === '') {
            pdfContent = '<p>No items found.</p>';
        }

        const pdfContainer = `
            <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto;">
                <h3 style="text-align: center;">Shopping List</h3><hr>
                ${pdfContent}
            </div>
        `;

        const options = {
            margin: 1,
            filename: 'shopping_list.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(pdfContainer).save();
    });

    $(document).on('change', '.meal-checkbox', function () {
        const mealContainer = $(this).closest('.ingredient-list'); // Find the relevant meal container
        const isChecked = $(this).is(':checked'); // Check if "Meal Checkbox" is selected

        // Select/Deselect all meal items within this meal's container
        mealContainer.find('.meal-item-checkbox').prop('checked', isChecked);
    });
   
    // $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
    //     let aggregatedItems = {};

    //     // Collect all checked items
    //     $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
    //         const listItem = $(this).closest('li');  // Correct reference for each item
    //         const itemName = listItem.find('.ingredient-info span').text().trim() || "Unknown Item";
    //         const quantityText = listItem.find('.quantity').text().trim() || "QTY: 0";
    //         const category = listItem.find('input[type="hidden"]#category').val().trim() || "Uncategorized";

    //         // Extract quantity and unit with better regex logic
    //         const quantityMatch = quantityText.match(/QTY:\s*([\d\/.]+)\s*([a-zA-Z]*)/i);
    //         let rawQuantity = quantityMatch && quantityMatch[1] ? quantityMatch[1] : "0";
    //         let unit = quantityMatch && quantityMatch[2] ? quantityMatch[2].trim() : '';

    //         // Correct conversion for fractional values
    //         let quantity = 0;
    //         if (rawQuantity.includes('/')) {
    //             const [numerator, denominator] = rawQuantity.split('/').map(Number);
    //             quantity = numerator / denominator;
    //         } else {
    //             quantity = parseFloat(rawQuantity);
    //         }

    //         // Ensure category exists in the aggregated structure
    //         if (!aggregatedItems[category]) {
    //             aggregatedItems[category] = {};
    //         }

    //         // Aggregate quantities within the category
    //         if (aggregatedItems[category][itemName]) {
    //             aggregatedItems[category][itemName].quantity += quantity;
    //             aggregatedItems[category][itemName].unit = unit;
    //         } else {
    //             aggregatedItems[category][itemName] = { quantity, unit };
    //         }
    //     });

    //     // Generate the HTML for the aggregated list by category
    //     let printListContent = '';
    //     for (let [category, items] of Object.entries(aggregatedItems)) {
    //         printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;  // Removed dot style
    //         for (let [itemName, data] of Object.entries(items)) {
    //             printListContent += `
    //                 <li style="margin: 0;">
    //                     <!-- Right tick mark icon added here -->
    //                     <span style="margin-right: 2px; font-size: 18px; color: green;">&#10003;</span>  
    //                     ${itemName} <strong>| QTY:</strong> ${data.quantity} ${data.unit}
    //                 </li>
    //             `;
    //         }
    //         printListContent += `</ul><br/>`;
    //     }

    //     // Populate the print modal with the aggregated list
    //     $('#ShippingPrintModal .print-list').html(printListContent);
    // });

    // $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
    //     // Get the content of the print list
    //     const content = $('#ShippingPrintModal .print-list').html();
    //     // Create a container to format the content for PDF
    //     const pdfContainer = `
    //         <div style="font-family: Arial, sans-serif; padding: 10px; max-width: 600px; margin: auto;">
    //             <h3 style="text-align: center;">Shopping List</h3><hr>
    //             <ul style="list-style: none; padding: 0;">
    //                 ${content}
    //             </ul>
    //         </div>
    //     `;

    //     // Use html2pdf to generate the PDF
    //     const options = {
    //         margin: 1,
    //         filename: 'shopping_list.pdf',
    //         html2canvas: { scale: 2 },
    //         jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    //     };

    //     html2pdf().set(options).from(pdfContainer).save();
    // });

    function updateQuantity(inputElement) {
        const newQty = inputElement.value; // Get the new quantity value
        const itemId = inputElement.getAttribute('data-item-id'); // Get the item ID from the data attribute
        const userItemId = inputElement.getAttribute('data-user-item-id'); // Get the item ID from the data attribute

        // Make an AJAX request to update the quantity in the backend
        fetch('{{ route("front.food-quantity-update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                item_id: itemId,
                qty: newQty,
                user_item_id: userItemId,
                user_id: userId
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Quantity updated successfully!');
            } else {
                alert('Failed to update quantity. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            alert('An error occurred. Please try again.');
        });
    }

</script>

@endsection