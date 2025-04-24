@extends('backend.layouts.app')

@section('content')
<style>
#loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    display: none;
}
#loader img {
    width: 50px; /* Adjust size */
    height: 50px;
}

</style>

<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($item) ? 'Edit Food' : 'Create Food' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a href="{{ route('woolworths-product-search') }}" class="btn btn-primary btn-set-task w-sm-100">Search Woolworths Shop</a>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-primary btn-set-task w-sm-100 mx-3">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($item)) 
                            @method('PUT') 
                        @endif
                        <input type="hidden" name="id" class="form-control" id="id" value="{{ $item->id ?? '' }}" >

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
                            </div>

                            <!-- Short Description Field -->
                        {{--<div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2">{{ $item->short_description ?? '' }}</textarea>
                            </div>
                        --}}
                            <!-- Full Description Field -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
                            </div>

                            <!-- category Field -->
                            <div class="col-md-12">
                                <label for="category" class="form-label">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ isset($item) && $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantity Field -->
                            <div class="col-md-4">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" name="qty" id="qty" class="form-control" value="{{ $item->qty ?? ''}}"  placeholder="Enter quantity" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="qty" class="form-label">Measurement</label>
                                <select name="unit" class="form-control" id="measurement">
                                    <option value="">Select Measurement</option>
                                    <option value="g"{{ isset($item) && $item->measurement == 'g' ? 'selected' : ''}}>gm</option>
                                    <option value="ml"{{ isset($item) && $item->measurement == 'ml' ? 'selected' : ''}}>ml</option>
                                    <!-- <option value="cup"{{ isset($item) && $item->measurement == 'g' ? 'selected' : ''}}>cup</option> -->
                                    <option value="tbsp"{{ isset($item) && $item->measurement == 'tbsp' ? 'selected' : ''}}>tbsp</option>
                                    <option value="piece"{{ isset($item) && $item->measurement == 'piece' ? 'selected' : ''}}>piece</option> 

                                    <!-- <option value="bar">bar</option> -->
                                    <!-- <option value="reguler">measurement</option>  -->
                                </select>
                            </div>

                            <div id="nutritionResult"></div>
                           <!-- Nutrition Information Section -->
                           <div class="col-md-12 border rounded p-3">
                                <h5 class="mb-3">Nutrition Information :</h5>

                                <div class="row">
                                    <!-- Protein Field -->
                                    <div class="col-md-6">
                                        <label for="protein" class="form-label">Protein</label>
                                        <input type="number" name="protein" class="form-control" id="protein"
                                            value="{{ $item->protein ?? '0' }}" 
                                            step="0.01" min="0" 
                                            placeholder="Enter Protein">
                                        <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                    </div>

                                   <!-- Serving Size Field -->
                                   <div class="col-md-2 mt-3">
                                        <label for="serving_size" class="form-label">Serving Size</label>
                                        <input type="number" name="serving_size" class="form-control" id="serving_size"
                                            value="{{ $item->serving_size ?? '0' }}" 
                                            step="0.01" min="0" 
                                            placeholder="Enter Serving Size">
                                        <small class="text-muted">Please enter the serving size in grams or milliliters.</small>
                                    </div>

                                    <div class="col-md-2 mt-3">
                                        <label for="serving_size" class="form-label">Serving Size Unit</label>
                                        <select name="serving_size_unit" class="form-control" id="serving_size_unit">
                                            <option value="">Select unit</option>
                                            <option value="g" {{ isset($item) && $item->serving_size_unit == 'g' ? 'selected' : ''}}>gm</option>
                                            <option value="ml" {{ isset($item) && $item->serving_size_unit == 'ml' ? 'selected' : ''}}>ml</option>
                                            <!-- <option value="piece"{{ isset($item) &&  $item->serving_size_unit == 'piece' ? 'selected' : ''}}>piece</option> -->
                                        </select>
                                        <!-- <input type="text" name="serving_size_unit" class="form-control d-inline-block d-flex" id="serving_size_unit" value="{{ isset($item) &&  $item->serving_size_unit ?? 'gm' }}" placeholder="Enter Serving Size"> -->
                                    </div>

                                    <!-- Fat Field -->
                                    <div class="col-md-6 mt-3">
                                        <label for="fat" class="form-label">Fat</label>
                                        <input type="number" name="fat" class="form-control" id="fat"
                                            value="{{ $item->fat ?? '0' }}" 
                                            step="0.01" min="0" 
                                            placeholder="Enter Fat">
                                        <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                    </div>

                                    <!-- Serving Per Pack Field -->
                                    <div class="col-md-6 mt-3">
                                        <label for="serving_per_pack" class="form-label">Serving Per Pack</label>
                                        <input type="number" name="serving_per_pack" class="form-control" id="serving_per_pack"
                                            value="{{ $item->serving_per_pack ?? '' }}" 
                                            step="1" min="1" 
                                            placeholder="Enter Serving Per Pack">
                                        <small class="text-muted">Please enter the total number of servings per pack.</small>
                                    </div>
                                     <!-- Carbohydrate Field -->
                                     <div class="col-md-6">
                                        <label for="carbs" class="form-label">Carbohydrate</label>
                                        <input type="number" name="carbs" class="form-control" id="carbs"
                                            value="{{ $item->carbs ?? '0' }}" 
                                            step="0.01" min="0" 
                                            placeholder="Enter Carbohydrate">
                                        <small class="text-muted">Please enter the value in grams (e.g., 5, 10.5).</small>
                                    </div>

                                    
                                </div>
                            </div>

                            <!-- Is Swapped Field -->
                            <div class="col-md-12">
                                <label for="is_swiped" class="form-label">Is Swapped? &nbsp;</label>
                                <small class="form-text text-muted">(Is this item used in the swapped list?)</small>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_yes" value="1"
                                        {{ (isset($item) && $item->is_swiped == 1) || !isset($item) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_swiped" id="is_swiped_no" value="0"
                                        {{ (isset($item) && $item->is_swiped == 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_swiped_no">No</label>
                                </div>
                            </div>

                            <?php //dd($item->itemSwaps); ?>
                            <!-- Swap Items Selection (Visible only if 'Is Swapped' is Yes) -->
                            <div class="col-md-12" id="swapItemsContainer" style="display: none;">
                                <label for="swap_item_ids" class="form-label">Swap Items</label>
                                <select name="swap_item_ids[]" class="form-control" id="swap_item_ids" multiple>
                                    @foreach ($allItems as $swapItem)
                                        <option value="{{ $swapItem->id }}" 
                                            {{ isset($item) && $item->items->contains($swapItem->id) ? 'selected' : '' }}>
                                            {{ $swapItem->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12" id="swapFoods">

                            </div>
                            <!-- Image Field -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($item) && $item->image)
                                    <img src="{{ asset('private/public/storage/' . $item->image) }}" alt="Item Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                                @endif
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="loader" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading..." />
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

@push('custom_scripts')
    <script>
            $(document).ready(function() {
            // Initially hide swap item dropdown if is_swiped is no
            if ($('input[name="is_swiped"]:checked').val() == '1') {
                $('#swapItemsContainer').show();
            } else {
                $('#swapItemsContainer').hide();
            }

            // Show/hide the swap item dropdown based on is_swiped selection
            $('input[name="is_swiped"]').on('change', function() {
                if ($(this).val() == '1') {
                    $('#swapItemsContainer').show();
                } else {
                    $('#swapItemsContainer').hide();
                }
            });
        });

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });

            $('#swap_item_ids').select2({
                placeholder: "Search and select swap items",
                minimumInputLength: 1,  // Trigger API call after typing 1 character
                width: '100%',
                ajax: {
                    url: '{{ route("admin.items.index") }}',  // API endpoint to fetch items
                    dataType: 'json',
                    delay: 250,  // Delay to optimize API calls
                    data: function(params) {
                        return {
                            query: params.term  // Send the search term as 'query'
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response.items.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.title
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            @if (isset($item))
                const preselectedFoods = @json($item->items->pluck('id'));
                $('#swap_item_ids').val(preselectedFoods).trigger('change');
            @endif

            $('#qty').on('input', function () {
                $('#measurement').val(''); // Reset measurement dropdown
            });
            const loader = $('#loader');
            $('#measurement').on('change', function (e) {
                e.preventDefault();
                const data = {
                    id: $('input[name="id"]').val(),
                    title: $('input[name="title"]').val(),
                    carbs: $('input[name="carbs"]').val(),
                    protein: $('input[name="protein"]').val(),
                    fat: $('input[name="fat"]').val(),
                    qty: $('input[name="qty"]').val(),
                    measurement: $('select[name="unit"]').val(),
                    serving_size : $('input[name="serving_size"]').val(),
                    serving_per_pack : $('input[name="serving_per_pack"]').val(),
                };

                const resultDiv = $('#nutritionResult');
                
                loader.show();

                $.ajax({
                    url: "{{ route('nutrition.calculate') }}",
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data) {
                            $('#carbs').val(data.carbs);
                            $('#protein').val(data.protein);
                            $('#fat').val(data.fat);
                            // $('#serving_per_pack').val(data.servings_per_pack);
                            // $('#serving_size').val(data.serving_size);

                            let alternateSizesHtml = '';
                            if (data.alternate_serving_sizes && Object.keys(data.alternate_serving_sizes).length > 0) {
                                alternateSizesHtml = `<p><strong>Alternate Serving Sizes:</strong></p><ul>`;
                                Object.values(data.alternate_serving_sizes).forEach(size => {
                                    alternateSizesHtml += `<li>${size}</li>`;
                                });
                                alternateSizesHtml += `</ul>`;
                            }
                            resultDiv
                            .html(`
                                ${alternateSizesHtml}
                            `)
                            .removeClass('error')
                            .show();
                            
                            // Update swapFoods div with a table
                            // if (data.swaps && data.swaps.length > 0) {
                            //     let swapsTable = `
                            //         <p><strong>Swap Foods:</strong></p>
                            //         <table border="1" cellpadding="5" cellspacing="0" class="table">
                            //             <thead>
                            //                 <tr>
                            //                     <th>Food</th>
                            //                     <th>Protein (g)</th>
                            //                     <th>Carbs (g)</th>
                            //                     <th>Fat (g)</th>
                            //                 </tr>
                            //             </thead>
                            //             <tbody>`;

                            //     data.swaps.forEach(swap => {
                            //         swapsTable += `
                            //             <tr>
                            //                 <td>${swap.food}</td>
                            //                 <td>${swap.protein.toFixed(2)}</td>
                            //                 <td>${swap.carbs.toFixed(2)}</td>
                            //                 <td>${swap.fat.toFixed(2)}</td>
                            //             </tr>`;
                            //     });

                            //     swapsTable += `</tbody></table>`;

                            //     $('#swapFoods').html(swapsTable).show();
                            // } else {
                            //     $('#swapFoods').html(`<p>No swap foods available.</p>`).show();
                            // }
                            
                            loader.hide();
                        } else {
                            resultDiv
                                .html(`<p class="error">Error: Could not calculate.</p>`)
                                .addClass('error')
                                .show();
                                loader.hide();
                        }
                    },
                    error: function () {
                        resultDiv
                            .html(`<p class="error">Error: Unable to connect to the server.</p>`)
                            .addClass('error')
                            .show();
                            loader.hide();
                    },
                    complete: function () {
                        loader.hide();
                    }
                });
            });

        });
    </script>
@endpush
@endsection
