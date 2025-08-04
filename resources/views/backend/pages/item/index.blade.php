@extends('backend.layouts.app')

@section('title', 'Foods List')

@section('content')
    <style>
        .score-lock-ico .st0 {
            fill: green !important;
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
                <div
                    class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                    <h3 class="fw-bold mb-0">Foods List</h3>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary py-2 px-2 btn-set-task">
                        <i class="icofont-plus-circle me-2 fs-6"></i> Add Food
                    </a>
                </div>
            </div>
        </div> <!-- Row end -->

        <div class="row g-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Protein (g)</th>
                                    <th>Carbs (g)</th>
                                    <th>Fat (g)</th>
                                    <th>Energy (kJ)</th>
                                    <th>Description</th>
                                    <th>Is Locked</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td><strong>{{ $item->id }}</strong></td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if($item->image)
                                                <img src="{{ webAssets('storage/' . $item->image) }}" alt="" width="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->protein }}</td>
                                        <td>{{ $item->carbs }}</td>
                                        <td>{{ $item->fat }}</td>
                                        <td>{{ $item->energy }}</td>
                                        <td>{{ Str::limit($item->description, 50, '...') }}</td>
                                        <td class="text-center">
                                            @if($item->is_locked == '1')
                                                <svg class="score-lock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800"
                                                    style="enable-background:new 0 0 800 800;" xml:space="preserve" width="20"
                                                    height="20">
                                                    <g>
                                                        <circle class="st0" cx="400" cy="567.2" r="54.7"></circle>
                                                        <path class="st0"
                                                            d="M621.2,326.9V219.3c0-120.2-97.8-217.9-217.9-217.9C279.5,1.3,178.8,102,178.8,225.7v101.2c-59.5,1.2-107.3,49.7-107.3,109.5v255.5c0,60.5,49,109.5,109.5,109.5h438c60.5,0,109.5-49,109.5-109.5V436.4C728.5,376.6,680.6,328.1,621.2,326.9z M255.5,225.7c0-81.5,66.3-147.8,147.8-147.8c77.9,0,141.3,63.4,141.3,141.3v104H255.5V225.7z M655.5,691.8c0,20.2-16.3,36.5-36.5,36.5H181c-20.2,0-36.5-16.3-36.5-36.5V436.4c0-20.2,16.3-36.5,36.5-36.5h42.8h352.3H619c20.2,0,36.5,16.3,36.5,36.5V691.8z">
                                                        </path>
                                                    </g>
                                                </svg>

                                            @else
                                                <svg class="score-unlock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800"
                                                    style="enable-background:new 0 0 800 800;" xml:space="preserve" width="20"
                                                    height="20">
                                                    <g>
                                                        <circle cx="400" cy="566.5" r="54.4"></circle>
                                                        <path
                                                            d="M617.8,327.5H271.9c-7.3-18-14.2-37.7-19.4-58.2c-9.4-37.5-12.3-74.3-3.9-105.5c7.9-29.6,26.4-56.8,65.8-76.5c39.4-19.8,72.2-18.4,100.6-7.1c30.1,12,57.9,36.2,82.3,66.1c5,6.1,9.8,12.4,14.3,18.7c12.7,17.6,37.6,22.4,54.6,8.9c14.4-11.3,18.1-31.7,7.6-46.7c-6.3-9-13.1-18-20.3-26.8C525.2,65.5,487.9,31,441.9,12.7c-47.7-19-102.1-19.5-160.1,9.7c-58,29.1-90.1,73.1-103.3,122.7c-12.8,47.9-7.3,98.4,3.7,142c3.5,13.9,7.7,27.5,12.2,40.4h-12.1c-60.1,0-108.9,48.8-108.9,108.9v254.1c0,60.1,48.8,108.9,108.9,108.9h435.6c60.1,0,108.9-48.8,108.9-108.9V436.4C726.7,376.2,677.9,327.5,617.8,327.5zM654.1,690.5c0,20-16.3,36.3-36.3,36.3H182.2c-20,0-36.3-16.3-36.3-36.3V436.4c0-20,16.3-36.3,36.3-36.3h435.6c20,0,36.3,16.3,36.3,36.3V690.5z">
                                                        </path>
                                                    </g>
                                                </svg>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                <a href="{{ route('admin.items.edit', $item->id) }}"
                                                    class="btn btn-outline-secondary">
                                                    <i class="icofont-edit text-success"></i>
                                                </a>
                                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-secondary"
                                                        onclick="return confirm('Are you sure you want to delete this food?');">
                                                        <i class="icofont-ui-delete text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection