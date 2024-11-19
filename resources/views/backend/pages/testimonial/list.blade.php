@extends(backendView('layouts.app'))

@section('title', 'Testimonial')

@section('page-title')
    {{ __('Testimonials') }}
@endsection

@section('content')
	@include(backendView('includes.alert'))
	<div class="container-xxl">
		<div class="row align-items-center">
			<div class="border-0 mb-4">
				<div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
					<h3 class="fw-bold mb-0">Testimonials</h3>
                    <div class="col-auto d-flex w-sm-100">
						<a type="button" href="{{ route('testimonials.add') }}" class="btn btn-primary btn-set-task w-sm-100"><i class="icofont-plus-circle me-2 fs-6"></i>Add Testimonial</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix g-3">
			<div class="col-sm-12">
				<div class="card mb-3">
					<div class="card-body">
						<table id="testimonial-table" class="table table-hover align-middle mb-0" style="width:100%">
							<thead>
								<tr>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Testimonial</th>
                                    <th>Image</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush

@push('custom_scripts')
<script>
	var ajaxParams = {};
	function init(ajaxParams) {
		$('#testimonial-table').DataTable({
			processing: true,
			ajax: "{{ route('testimonials.list-ajax') }}",
			"order": [[ 0, "desc" ]],
			columns: [
                { data: 'name', name: 'name' },
                { data: 'designation', name: 'designation' },
                { data: 'review', name: 'review' },
                { data: "testimonial_image" ,
					"render": function ( data) {
					return '<img src="' + data + '" width="100px">';},
					sortable: false
				},
				{ data: 'action', name: 'action', sortable: false },
			],
		}).draw();
	}

    function deleteTestimonial(id) {
		$.ajax({
			url: "{{ route('testimonials.delete') }}",
			headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
			method: 'POST',
			data: {
				id: id,
			},
			success: function(response) {
				Swal.fire("Testimonial deleted successfully!", "", "success");
				$('#testimonial-table').DataTable().ajax.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
					Swal.fire({
					icon: "error",
					title: "Oops...",
					text: "Something went wrong!",
				});
			}
		});
	}

    $(document).on('click', '.deleterow', function() {
		var id = $(this).attr('data-id');
		Swal.fire({
			title: "Are you sure?",
			text: "You want to delete Testimonial?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Submit"
			}).then((result) => {
			if (result.isConfirmed) {
				deleteTestimonial(id);
			}
		});
	});

	$(document).ready(function(){
		init(ajaxParams);
	});
</script>
@endpush
