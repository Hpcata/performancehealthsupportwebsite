@extends(backendView('layouts.app'))

@section('title', 'Users List')

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
                <h3 class="fw-bold mb-0">Users List</h3>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Quiz Submit</th>
                                <th>Plan Purchase</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @if(in_array($user->email, $quizSubmissions))
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($user->id, $planPurchases))
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('front.profile', ['id' => $user->id]) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="icofont-user text-success"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                        <i class="icofont-trash text-danger"></i>
                                    </a>
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

<!-- User Details Modal -->
<div id="userDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Delete</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</h6>
                <!-- <p class="text-danger">This action cannot be undone.</p> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('custom_scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    // $('#myDataTable').DataTable({
    //     "paging": true,
    //     "ordering": true,
    //     "info": true,
    //     "searching": true,
    //     "pageLength": 10
    // });

    // Handle user details view
    $('.view-user-details').click(function() {
        const userId = $(this).data('user-id');

        $.ajax({
            url: '{{ route("admin.user.details") }}',
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if (response.success) {
                    const user = response.data;
                    let modalContent = `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> ${user.name || 'N/A'}</p>
                                <p><strong>Email:</strong> ${user.email || 'N/A'}</p>
                                <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Created At:</strong> ${new Date(user.created_at).toLocaleString()}</p>
                                <p><strong>Last Updated:</strong> ${new Date(user.updated_at).toLocaleString()}</p>
                            </div>
                        </div>`;

                    $('#userDetailsModal .modal-body').html(modalContent);
                    $('#userDetailsModal').modal('show');
                }
            },
            error: function() {
                alert('Error loading user details');
            }
        });
    });

    // Handle delete user
    $(document).on('click', '.delete-user', function() {
        // alert('delete');
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        $('#deleteUserName').text(userName);
        $('#deleteUserForm').attr('action', '{{ route("admin.users.destroy", "") }}/' + userId);
        $('#deleteUserModal').modal('show');
    });
});
</script>
@endpush
@endsection
