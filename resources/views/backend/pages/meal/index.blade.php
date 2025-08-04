@extends('backend.layouts.app')

@section('title', 'Meal List')

@section('content')
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
                <h3 class="fw-bold mb-0">Meal List</h3>
                <a href="{{ route('admin.meals.create') }}" class="btn btn-primary py-2 px-2 btn-set-task">
                    <i class="icofont-plus-circle me-2 fs-6"></i> Add Meal
                </a>
            </div>
        </div>
    </div> <!-- Row end -->

    <div class="row g-3 mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <!-- 🔹 Category Filter Dropdown -->
                    <div class="row mb-3">
                        <div class="col-md-12 d-flex justify-content-end align-items-center mob-border-bottom">
                            <label for="categoryFilter" class="form-label mb-0 me-2">Filter by Sub Category:</label>
                            <select id="categoryFilter" class="form-control w-auto">
                                <option value="">All Sub Categories</option>
                                @foreach($subCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <table id="mealDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Total Energy (kJ)</th>
                                <th>Total Protein (g)</th>
                                <th>Total Carbs (g)</th>
                                <th>Total Fat (g)</th>
                                <th>Description</th>
                                <!-- <th>Created At</th> -->
                                <th>Sub Categories</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody> <!-- Empty initially, data will be loaded via AJAX -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{!! backendAssets('dist/assets/bundles/dataTables.bundle.js') !!}"></script>
@endpush
@push('custom_scripts')
<script>
    $(document).ready(function () {
        let dataTable = $('#mealDataTable').DataTable({
        pageLength: 100,
        processing: false,
        serverSide: false, // Use true if handling data via Laravel DataTables
        ajax: {
            url: "{{ route('admin.meals.index') }}",
                type: "GET",
                dataSrc: function (json) {
                    return json.meals;
                }
            },
            columns: [
                { data: "id" },
                { data: "title" },
                {
                    data: "image",
                    render: function (data) {
                        return data
                            ? `<img src="{{ webAssets('storage/') }}/${data}" width="50"/>`
                            : '<span class="text-muted">No Image</span>';
                    }
                },
                {
                    data: "items",
                    render: function (data) {
                        let totalEnergy = data.reduce((sum, item) => sum + parseFloat(item.pivot.energy || 0), 0);
                        return totalEnergy.toFixed(2) + " kJ";
                    }
                },
                {
                    data: "items",
                    render: function (data) {
                        let totalProtein = data.reduce((sum, item) => sum + parseFloat(item.pivot.protein || 0), 0);
                        return totalProtein.toFixed(2) + " g";
                    }
                },
                {
                    data: "items",
                    render: function (data) {
                        let totalCarbs = data.reduce((sum, item) => sum + parseFloat(item.pivot.carbs || 0), 0);
                        return totalCarbs.toFixed(2) + " g";
                    }
                },
                {
                    data: "items",
                    render: function (data) {
                        let totalfats = data.reduce((sum, item) => sum + parseFloat(item.pivot.fat || 0), 0);
                        return totalfats.toFixed(2) + " g";
                    }
                },
                {
                    data: "description",
                    render: function (data) {
                        return data ? data.substring(0, 50) + "..." : "";
                    }
                },
                {
                    data: "subCategories",
                    render: function (data, type, row) {
                        const categories = row.sub_categories;
                        // console.log(categories);
                        if (Array.isArray(categories) && categories.length > 0) {
                            return categories.map(cat => cat.title).join(", ");
                        }
                        return "No Sub Category";
                    }
                },
                {
                    data: "id",
                    render: function (data) {
                        let editUrl = `{{ url('admin/meals/${data}/edit') }}`;
                        let deleteUrl = `{{ url('admin/meals/${data}') }}`;

                        return `
                            <div class="btn-group">
                                <a href="${editUrl}" class="btn btn-outline-secondary">
                                    <i class="icofont-edit text-success"></i>
                                </a>
                                <form action="${deleteUrl}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this meal?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="icofont-ui-delete text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        `;
                    }
                }
            ]
        });

        // 🔹 Reload DataTable when category is changed
        $('#categoryFilter').on('change', function () {
            let selectedCategory = $(this).val();
            dataTable.ajax.url("{{ route('admin.meals.index') }}?category_id=" + selectedCategory).load();
        });
    });

</script>
@endpush
@endsection