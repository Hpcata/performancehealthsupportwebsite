@extends(backendView('layouts.app'))

@section('title', 'Tags List')

@section('content')
<style>
.select2 .select2-selection__choice {
    display: flex !important;
    align-items: center !important;
    height: 35px !important;
    padding: 5px 10px !important;
    font-size: 14px !important;
}

.select2 .select2-selection__choice img {
    width: 25px !important;
    height: 25px !important;
    object-fit: cover !important;
    margin-right: 8px !important;
}
@media only screen and (max-width: 767px) {
td.child li .dtr-data{
  display: flex;
  justify-content: center;
  align-items: start;
  flex-direction: column;
  gap: 5px;
}
td.child li .dtr-data span{
  font-size: 12px;
}
}
</style>
<div class="container-xxl">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
	<div class="row align-items-center">
		<div class="border-0 mb-4">
			<div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
				<h3 class="fw-bold mb-0">Preferences List</h3>
				<a href="{!! route('admin.flags.create') !!}" class="btn btn-primary py-2 px-2 btn-set-task"><i class="icofont-plus-circle me-2 fs-6"></i> Add Preferences</a>
			</div>
		</div>
	</div> <!-- Row end  -->
	<div class="row g-3 mb-3">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
						<thead>
							<tr>
                                <th>#</th>
                                <th>Preferences Name</th>
                                <th>Foods</th>
                                <th>Actions</th>
							</tr>
						</thead>
						<tbody>
                            @foreach ($flags as $flag)
                                <tr>
                                    <td>{{ $flag->id }}</td>
                                    <td>{{ $flag->name }}</td>
                                    <td class="chips">
                                        <!-- Loop through foods and display as badges with remove button -->
                                        @foreach ($flag->items as $item)
                                            <span class="badge bg-primary me-2 position-relative w-fit">
                                                {{ $item->title }}
                                                <button type="button" class="btn-close btn-remove-food" data-flag-id="{{ $flag->id }}" data-food-id="{{ $item->id }}"     data-url="{{ route('admin.flags.removeFood', ['flag' => $flag->id, 'food' => $item->id]) }}" aria-label="Close"></button>
                                            </span>
                                        @endforeach
                                        <!-- Add Button to open Modal -->
                                        <button class="btn btn-outline-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#foodModal_{{ $flag->id }}">+ Add Food</button>
                                    </td>
                                    <!-- <td>
                                        @if($flag->icon)
                                        <img src="{{ asset('private/public/storage/' . $flag->icon) }}" alt="" width="50">
                                        @else
                                        <span class="text-muted">No Image</span>
                                        @endif
                                    </td> -->
                                    <td>
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <a href="{{ route('admin.flags.edit', $flag->id) }}" class="btn btn-outline-secondary"><i class="icofont-edit text-success"></i></a>
                                        <form action="{{ route('admin.flags.destroy', $flag->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="icofont-ui-delete text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal for adding foods to the flag -->
                                <div class="modal" id="foodModal_{{ $flag->id }}" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="foodModalLabel">Select Foods for {{ $flag->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <select class="form-select dynamic-food-select" id="selectFood_{{$flag->id}}" data-flag-id="{{ $flag->id }}" multiple></select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary save-foods" data-flag-id="{{ $flag->id }}">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush

@push('custom_scripts')
<script>
    // Remove food from the flag
    $(document).on('click', '.btn-remove-food', function() {
        const removeFoodUrl = $(this).data('url');

        // AJAX request to remove the food from the flag
        $.ajax({
            url: removeFoodUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error removing food.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('AJAX error.');
            }
        });
    });

    // Save selected foods from the modal
    $(document).on('click', '.save-foods', function() {
        const flagId = $(this).data('flag-id');
        const selectedFoods = $('#selectFood_' + flagId).val();
        const addFoodUrl = "{{ route('admin.flags.addFoods', ['flag' => '__FLAG_ID__']) }}".replace('__FLAG_ID__', flagId);

        if (selectedFoods.length > 0) {
            $.ajax({
                url: addFoodUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    foods: selectedFoods,
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Reload page to reflect the changes
                    }
                }
            });
        } else {
            alert('Please select at least one food.');
        }
    });

    $('.dynamic-food-select').each(function () {
        let selectElement = $(this);
        let flagId = selectElement.data('flag-id');

        selectElement.select2({
            placeholder: "Search and select foods",
            minimumInputLength: 1,
            width: '100%',
            dropdownParent: $('#foodModal_' + flagId),  // This is the key fix
            ajax: {
                url: '{{ route("admin.items.index") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let selectedIds = selectElement.val() || [];

                    return {
                        query: params.term,
                        exclude_ids: selectedIds,
                        flag_id: flagId
                    };
                },
                processResults: function (response) {
                    const selectedIds = new Set(selectElement.val() || []);

                    return {
                        results: response.items
                            .filter(item => !selectedIds.has(item.id.toString()))
                            .map(item => ({
                                id: item.id,
                                text: item.title,
                                image: item.image
                                    ? `{{ webAssets('storage') }}/${item.image}`
                                    : '{{ asset("default.png") }}'
                            }))
                    };
                },
                cache: true
            },
            templateResult: formatFood,
            templateSelection: formatFoodSelection
        });
    });

    function formatFood(food) {
        if (!food.id) return food.text;

        const image = food.image || '{{ asset("default.png") }}';

        const $food = $(`
            <div style="display: flex; align-items: center;">
                <img src="${image}" style="width: 30px; height: 30px; margin-right: 10px; object-fit: cover;" />
                <span>${food.text}</span>
            </div>
        `);

        return $food;
    }

    function formatFoodSelection(food) {
        if (!food.id) return food.text;

        const image = food.image || '{{ asset("default.png") }}';

        const $selected = $(`
            <div style="display: flex; align-items: center;">
                <img src="${image}" style="width: 25px; height: 25px; margin-right: 5px; object-fit: cover;" />
                <span>${food.text}</span>
            </div>
        `);

        return $selected;
    }
    $(document).ready(function () {
        $('.modal').on('hidden.bs.modal', function () {
            // Reset all select fields inside the closed modal
            $(this).find('select').val([]).trigger('change');

            // Optionally reset other inputs, checkboxes, etc.
            $(this).find('input[type="text"], input[type="number"], textarea').val('');
            $(this).find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        });
    });

</script>
@endpush
@endsection
