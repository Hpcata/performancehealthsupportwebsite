// Keep session alive by making an AJAX request every 5 minutes
setInterval(function() {
    $.ajax({
        url: '/check-auth',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (!response.authenticated) {
                // If session is lost, redirect to login
                window.location.href = '/login';
            }
        }
    });
}, 300000); // 5 minutes in milliseconds 