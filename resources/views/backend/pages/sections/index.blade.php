@extends(backendView('layouts.app'))

@section('title', 'Section List')

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
    @if (request('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ request('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

	<div class="row align-items-center">
		<div class="border-0 mb-4">
			<div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
				<h3 class="fw-bold mb-0">Section List</h3>
                <div class="d-flex gap-2 ms-auto">
                    <a href="{{ route('pages.index') }}" class="btn btn-outline-primary">Back</a>
                    <a href="{!! route('sections.create', ['page' => $page->id]) !!}" class="btn btn-primary btn-set-task">
                        <i class="icofont-plus-circle me-2 fs-6"></i> Add Section
                    </a>
                </div>
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
                                <th class="no-sort">#</th>
								<th>Title</th>
                                <th>Section Type</th>
                                <th>Content</th>
                                <th>Enabled</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $section)
                                <tr data-id="{{ $section->id }}">
                                    <td>{{ $section->id }}</td>
                                    <td>{{ $section->title }}</td>
                                    <td>
                                        @if($section->section_type)
                                            <span class="badge bg-primary">{{ \App\Models\Section::getSectionTypes()[$section->section_type] ?? $section->section_type }}</span>
                                        @else
                                            <span class="badge bg-secondary">Not Set</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit(strip_tags($section->content), 50) }}</td>
                                    <td>{{ $section->enabled ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <a href="{{ route('sections.edit', ['page'=> $page->id ,'section' => $section]) }}" class="btn btn-outline-success">
                                                <i class="icofont-edit"></i>
                                            </a>
                                            <form action="{{ route('sections.destroy', $section) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this section?');"><i class="icofont-ui-delete "></i></button>
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
@push('scripts')
    <!-- No scripts needed for sections list -->
@endpush
