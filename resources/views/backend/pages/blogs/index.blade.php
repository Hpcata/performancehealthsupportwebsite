@extends(backendView('layouts.app'))

@section('title', 'Blogs List')

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
			<div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
				<h3 class="fw-bold mb-0">Blogs List</h3>
				<a href="{!! backendRoutePut('blogs.create') !!}" class="btn btn-primary py-2 px-5 btn-set-task w-sm-100"><i class="icofont-plus-circle me-2 fs-6"></i> Add Blog</a>
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
								<th>Id</th>
								<th>Title</th>
								<th>Image</th>
								<th>Content</th>
								<!-- <th>Author</th> -->
								<th>Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
                            @foreach ($blogs as $blog)
							<tr>
								<td><strong>{{ $blog->id }}</strong></td>
								<td>{{ $blog->title }}</td>
                                <td><img src="{!! asset($blog->image) !!}" alt="" width="50"></td>
                                <td>{{ $blog->content }}</td>
                                <!-- <td></td> -->
								<td>{{ $blog->created_at }}</td>
								<td>@if($blog->is_published == '1')
                                    <span class="badge bg-success">Published</span>
                                    @else
                                    <span class="badge bg-danger">Draft</span>
                                    @endif
								<td>
									<div class="btn-group" role="group" aria-label="Basic outlined example">
										<a href="{!! backendRoutePut('blogs.edit', $blog->id) !!}" class="btn btn-outline-secondary"><i class="icofont-edit text-success"></i></a>
                                        <form action="{{ route('backend.blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary">
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

@push('styles')
<!-- plugin css file  -->
<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/datatables/responsive.dataTables.min.css') !!}">
<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/datatables/dataTables.bootstrap5.min.css') !!}">
@endpush

@push('custom_styles')
@endpush

@push('scripts')
<!-- Plugin Js -->
<script src="{!! backendAssets('dist/assets/bundles/dataTables.bundle.js') !!}"></script>
@endpush

@push('custom_scripts')
<script>
	$('#myDataTable')
		.addClass('nowrap')
		.dataTable({
			responsive: true,
			columnDefs: [{
				targets: [-1, -3],
				className: 'dt-body-right'
			}]
		});
	$('.deleterow').on('click', function() {
		var tablename = $(this).closest('table').DataTable();
		tablename
			.row($(this)
				.parents('tr'))
			.remove()
			.draw();

	});
    // Flash message auto-hide logic
	window.onload = function() {
		// Select all alert messages
		const alerts = document.querySelectorAll('.alert');
		alerts.forEach(function(alert) {
			// Set timeout to fade out the alert after 5 seconds (5000ms)
			setTimeout(function() {
				alert.classList.add('fade');
				alert.classList.remove('show');
			}, 5000);
		});
	}
</script>
@endpush

@push('modals')
@endpush