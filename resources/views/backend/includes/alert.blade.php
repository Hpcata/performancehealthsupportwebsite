@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
    </div>
@endif

<script>
    // TO hide the alert after some time
    document.addEventListener('DOMContentLoaded', function() {
        var alertElement = document.querySelector('[role="alert"]');

        if (alertElement) {
            setTimeout(function() {
                alertElement.style.transition = 'opacity 1s ease';
                alertElement.style.opacity = 0;
                setTimeout(function() {
                    alertElement.style.display = 'none';
                }, 1000);
            }, 3000);
        }
    });
</script>
