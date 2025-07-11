@extends(backendView('layouts.app'))

@section('title', 'Tags List')

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
				<h3 class="fw-bold mb-0">Tags List</h3>
				<a href="{!! route('admin.tags.create') !!}" class="btn btn-primary py-2 px-2 btn-set-task"><i class="icofont-plus-circle me-2 fs-6"></i> Add Tag</a>
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
                                <th>Tag Name</th>
                                <th>Icon</th>
                                <th>Actions</th>
							</tr>
						</thead>
						<tbody>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td>{{ $tag->id }}</td>
                                    <td>{{ $tag->name }}</td>
                                    <td>
                                        @if($tag->icon)
                                        <img src="{{ webAssets('storage/' . $tag->icon) }}" alt="" width="50">
                                        @else
                                        <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">

                                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-outline-secondary"><i class="icofont-edit text-success"></i></a>
                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="icofont-ui-delete text-danger"></i>
                                            </button>
                                        </form>
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
