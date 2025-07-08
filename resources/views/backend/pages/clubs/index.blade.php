@extends(backendView('layouts.app'))

@section('title', 'Clubs')

@section('page-title')
    {{ __('Clubs') }}
@endsection

@section('content')
	@include(backendView('includes.alert'))
	<div class="container-xxl">
		<div class="row align-items-center">
			<div class="border-0 mb-4">
				<div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
					<h3 class="fw-bold mb-0">Clubs</h3>
                    <div class="col-auto d-flex w-sm-100">
						<a type="button" href="{{ route('admin.clubs.create') }}" class="btn btn-primary btn-set-task w-sm-100"><i class="icofont-plus-circle me-2 fs-6"></i>Add Club</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix g-3">
			<div class="col-sm-12">
				<div class="card mb-3">
					<div class="card-body">
						<table id="club-table" class="table table-hover align-middle mb-0" style="width:100%">
							<thead>
								<tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Logo</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
                            @foreach ($clubs as $club)
                            <tr>
                                <td><strong>{{ $club->id }}</strong></td>
                                <td>{{ $club->name }}</td>
                                <td>
                                    @if($club->logo)
                                    <img src="{{ asset('storage/' . $club->logo) }}" alt="" width="50">
                                    @else
                                    <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <a href="{{ route('admin.clubs.edit', $club->id) }}" class="btn btn-outline-secondary">
                                            <i class="icofont-edit text-success"></i>
                                        </a>
                                        <form action="{{ route('admin.clubs.destroy', $club->id) }}" method="POST" style="display:inline;">
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
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
