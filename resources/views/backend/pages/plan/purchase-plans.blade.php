@extends('backend.layouts.app')

@section('title', 'Purchase Plans List')

@section('content')
<style>
    hr {
        margin-top : 0px !important;
        margin-bottom : 15px !important;
        border-top : 1px solid black !important;
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
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">Purchase Plans List</h3>
                <!-- <a href="{{ route('admin.plans.create') }}" class="btn btn-primary py-2 px-2 btn-set-task"><i class="icofont-plus-circle me-2 fs-6"></i> Add Plan</a> -->
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
                                <th>Plan</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Price</th>
                                <th>Phone</th>
                                <!-- <th>Status</th>
                                <th>Discount Code</th> -->
                                <!-- <th>Status</th> -->
                                <th style="white-space: nowrap;">Purchase Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $isPlanCreated = false;
                                    if(array_key_exists($payment->user_id, $useWisePlanData) && in_array($payment->plan_id, $useWisePlanData[$payment->user_id])) {
                                        $isPlanCreated = true;
                                    }
                                @endphp
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->plan->name ?? 'N/A' }}</td> <!-- Assuming you have a 'name' field in Plan model -->
                                <td>{{ $payment->name }}</td>
                                <td>{{ $payment->email }}</td>
                                <td>{{ $payment->price }}</td>
                                <td>{{ $payment->phone }}</td>
                                <!-- <td>{{ $payment->status }}</td>
                                <td>{{ $payment->coupon_code }}</td> -->
                                <!-- <td>{{ $payment->status }}</td> -->
                                <td>{{ formatDate($payment->created_at) }}</td>
                                <td>
                                    <!-- Action link to show payment details -->
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary user-pre-plan-details m-1" data-payment-id="{{ $payment->id }}" ><i class="icofont-eye text-primary"></i></a>
                                    @if($isPlanCreated)
                                    <a href="{{ route('admin.purchase-plans.edit', ['user' => $payment->user_id,'plan' => $payment->id]) }}" class="btn btn-sm btn-outline-success m-1"><i class="icofont-edit text-success"></i></a>
                                    @else
                                    <a href="{{ route('admin.purchase-plans.create', $payment->id) }}" class="btn btn-sm btn-outline-success m-1"><i class="icofont-plus text-success"></i></a>
                                    @endif
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

<div id="prePlanDetail" class="modal " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Questionnaire</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be injected here -->
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.user-pre-plan-details', function() {
            const paymentId = $(this).data('payment-id');

            console.log('Clicked on user-pre-plan-details button with paymentId:', paymentId);

            $.ajax({
                url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                method: 'GET',

                success: function(response) {
                    if (response.success) {
                        console.log(response.data);

                        let modalContent = '';

                        // Add User Details at the top
                        if (response.userDetails) {
                            const userDetails = response.userDetails;
                            modalContent += `
                                <div>
                                    <h4 style="color:#7258db;">User Details</h4><hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Name:</strong> ${userDetails.name || 'N/A'}</p>
                                            <p><strong>Email:</strong> ${userDetails.email || 'N/A'}</p>
                                            <p><strong>Phone:</strong> ${userDetails.phone || 'N/A'}</p>
                                            <p><strong>DOB:</strong> ${userDetails.dob || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Postcode:</strong> ${userDetails.address || 'N/A'}</p>
                                            <p><strong>Referred By:</strong> ${userDetails.referredBy || 'N/A'}</p>
                                            <p><strong>Sport:</strong> ${userDetails.occupation || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div><hr>`;
                        }

                        const formData = response.data;

                        const foodGroups = response.foodGroups || {}; // assuming this comes from AJAX response

                        Object.keys(formData).forEach(function (formName) {
                            if (formName === 'Personal Details') return;

                            modalContent += `<div><h4 style="color:#7258db;">${formName}</h4><hr>`;
                            const formQuestions = formData[formName];

                            Object.keys(formQuestions).forEach(function (question) {
                                let answer = formQuestions[question];
                                let answerContent = '';

                                // === FOOD PREFERENCE HANDLING ===
                                if (formName === 'Food Preference') {
                                    const expectedGroups = foodGroups;
                                    const groupNameRaw = question;

                                    const clean = s => (s || '').toString().trim();
                                    const normal = s => clean(s).replace(/\s{2,}/g, ' ');

                                    const groupKey = normal(groupNameRaw);
                                    const expectedSubs = expectedGroups[groupKey] || [];
                                    const userValue = answer;

                                    answerContent = '<ul>';

                                    if (userValue === null || userValue === undefined) {
                                        answerContent += `<li class="text-danger">Not selected</li>`;
                                    } else {
                                        if (Array.isArray(userValue)) {
                                            const nonEmpty = userValue.filter(x => clean(x));
                                            if (nonEmpty.length) {
                                                nonEmpty.forEach(item => {
                                                    answerContent += `<li>${item}</li>`;
                                                });
                                            } else {
                                                answerContent += `<li class="text-danger">Not selected</li>`;
                                            }
                                        } else if (typeof userValue === 'object') {
                                            expectedSubs.forEach(sub => {
                                                const subKey = normal(sub);
                                                const val = userValue[subKey];
                                                if (Array.isArray(val)) {
                                                    const cleanItems = val.filter(x => x && x !== 'null' && x !== null);
                                                    if (cleanItems.length) {
                                                        answerContent += `<li><strong>${subKey}</strong><ul>${cleanItems.map(v => `<li>${v}</li>`).join('')}</ul></li>`;
                                                    }
                                                } else if (typeof val === 'string' && clean(val) !== '' && val !== 'null') {
                                                    answerContent += `<li><strong>${subKey}:</strong> ${val}</li>`;
                                                } else {
                                                    answerContent += `<li class="text-danger">${subKey} — Not selected</li>`;
                                                }
                                            });
                                        } else {
                                            answerContent += `<li>${clean(userValue)}</li>`;
                                        }
                                    }

                                    answerContent += '</ul>';

                                    modalContent += `
                                        <div>
                                            <p><strong>Q : ${groupNameRaw}</strong></p>
                                            <div>${answerContent}</div>
                                        </div>`;
                                    return; // skip to next question
                                }

                                // === DEFAULT LOGIC FOR OTHER FORMS ===
                                if (!answer) {
                                    answerContent = '<span class="text-danger">Not selected</span>';
                                } else if (Array.isArray(answer)) {
                                    const filtered = answer.filter(item => item);
                                    if (filtered.length) {
                                        answerContent = '<ul>' + filtered.map(i => `<li>${i}</li>`).join('') + '</ul>';
                                    }
                                } else if (typeof answer === 'object') {
                                    // special case: { answer: 'Yes', date: '3 months ago' }
                                    if ('answer' in answer && 'date' in answer) {
                                        answerContent = `
                                            <ul>
                                                <li><strong>Answer:</strong> ${answer.answer}</li>
                                                <li><strong>Date:</strong> ${answer.date}</li>
                                            </ul>`;
                                    } else {
                                        let valid = Object.entries(answer).filter(([_, v]) => v);
                                        if (question.includes('hunger/appetite over the day')) {
                                            const order = ['breakfast', 'morning_tea', 'lunch', 'afternoon_tea', 'dinner', 'dessert'];
                                            valid.sort((a, b) => order.indexOf(a[0]) - order.indexOf(b[0]));
                                        }
                                        if (valid.length) {
                                            answerContent = '<ul>';
                                            valid.forEach(([k, v]) => {
                                                const keyLabel = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                                if (Array.isArray(v)) {
                                                    const sub = v.filter(x => x);
                                                    if (sub.length) {
                                                        answerContent += `<li><strong>${keyLabel}</strong><ul>${sub.map(s => `<li>${s}</li>`).join('')}</ul></li>`;
                                                    }
                                                } else {
                                                    answerContent += `<li><strong>${keyLabel}:</strong> ${v}</li>`;
                                                }
                                            });
                                            answerContent += '</ul>';
                                        }
                                    }
                                } else {
                                    answerContent = answer;
                                }

                                // Append question + answer block
                                if (answerContent && question) {
                                    modalContent += `
                                        <div>
                                            <p><strong>Q : ${question}</strong></p>
                                            <p>${answerContent}</p>
                                        </div>`;
                                }
                            });

                            modalContent += '</div><hr>';
                        });

                        // Set the content inside the modal
                        $('#prePlanDetail .modal-body').html(modalContent);

                        // Show the modal
                        $('#prePlanDetail').modal('show');
                    } else {
                        if (!response.data) {
                            alert('Pre plan details not available.');
                        } else {
                            alert('Failed to load the data');
                        }
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the data.');
                }
            });
        });

        // $('button[name="action"][value="view"]').on('click', function(e) {
        //     e.preventDefault();

        //     var user_id = $(this).data('user-id');  // Assume you set a data attribute with the user's ID on the button
        //     var payment_id = $(this).data('payment-id');  // Assume you set a data attribute with the user's ID on the button

        //     $.ajax({
        //         url: '{{ route("admin.handle-plan-action") }}',  // URL to your controller method for storing the form
        //         method: 'POST',
        //         data: {
        //             action: 'view',
        //             user_id: user_id,
        //             payment_id : payment_id,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.status === 'success') {
        //                 window.open(response.redirect_url, '_blank');
        //                 // window.location.href = response.redirect_url;  // Redirect to user profile page
        //             } else {
        //                 alert('Error: ' + response.message);
        //             }
        //         },
        //         error: function(xhr) {
        //             alert('Something went wrong!');
        //         }
        //     });
        // });

        // Handle the "Send" button click (Send meal plan)
        $('button[name="action"][value="send"]').on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            var user_id = $button.data('user-id');
            var payment_id = $button.data('payment-id');
            const loader = $('#loader-2');
            loader.show(); // Show the loader
            $.ajax({
                url: '{{ route("admin.handle-plan-action") }}',
                method: 'POST',
                data: {
                    action: 'send',
                    user_id: user_id,
                    payment_id: payment_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);

                        // ✅ Remove inline background if any and apply btn-success
                        $button.css('background-color', '').removeClass('btn-secondary btn-danger').addClass('btn-success');

                        // ✅ Format current date/time
                        const now = new Date();
                        const formattedDate = now.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true,
                        }).replace(',', '');

                        // ✅ Append timestamp below button (or update if already exists)
                        const timestampId = 'timestamp-' + user_id + '-' + payment_id;

                        if ($('#' + timestampId).length) {
                            $('#' + timestampId).text(formattedDate);
                        } else {
                            $('<div>')
                                .attr('id', timestampId)
                                .addClass('mt-2 text-muted')
                                .css('margin-left', '330px')
                                .text(formattedDate)
                                .insertAfter($button);
                        }
                        loader.hide();
                    } else {
                        alert('Error: ' + response.message);
                        loader.hide();
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong!');
                    loader.hide();
                }
            });
        });


        $(document).on('click', '.view-info', function () {
            // alert('22');
            var description = $(this).data('description') || 'N/A';
            $('#modalDescription').text(description);
            $('#itemInfoModal').modal('show');
        });
    });


</script>
@endsection