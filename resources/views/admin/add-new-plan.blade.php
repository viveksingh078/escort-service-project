@extends('admin.layout')
@section('title', 'Subscription Plans')
@section('content')

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Membership Plans</h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#planModal">Add New
                    Plan</button>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="subscriptionTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Plan Name</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Feature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Plan Modal -->
    <div class="modal fade" id="planModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="planForm" method="POST" action="{{ route('admin.subscription.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="plan_method" value="POST">
                        <input type="hidden" id="plan_id" name="plan_id">

                        <div class="form-group">
                            <label for="plan_name">Plan Name</label>
                            <input type="text" name="name" id="plan_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="plan_price">Price (in $)</label>
                            <input type="number" name="price" id="plan_price" class="form-control" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="plan_duration">Duration (in days)</label>
                            <input type="number" name="duration" id="plan_duration" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="features"><strong>Select Features</strong></label>
                            <div class="row">
                                @foreach ($features as $feature)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <!-- NAME must be feature_ids[] so backend receives array -->
                                            <input class="form-check-input" type="checkbox" name="feature_ids[]"
                                                value="{{ $feature->id }}" id="feature{{ $feature->id }}">
                                            <label class="form-check-label" for="feature{{ $feature->id }}">
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary mt-3">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Load Subscription Plans Table
            function loadPlans() {
                $('#subscriptionTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route("admin.subscription.list") }}',
                        type: 'GET'
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'price', name: 'price' },
                        { data: 'duration', name: 'duration' },
                        { data: 'feature', name: 'feature' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    error: function (xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                    }
                });
            }

            loadPlans();

            // Submit Add/Edit Plan Form
            $('#planForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#plan_id').val();
                const planName = $('#plan_name').val().trim();
                const planPrice = $('#plan_price').val().trim();
                const planDuration = $('#plan_duration').val().trim();
                const planFeature = [];
                $('input[name="feature_ids[]"]:checked').each(function () {
                    planFeature.push($(this).val());
                });

                if (!planName || !planPrice || !planDuration) {

                    showWarningToast("All fields are required.");
                    return;
                }

                // Check for duplicate plan name (only for add, not edit)
                if (!id) {
                    $.ajax({
                        url: '{{ route("admin.subscription.check-duplicate") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: planName
                        },
                        success: function (response) {
                            console.log('Duplicate check response:', response);
                            if (response.success && response.exists) {
                                showWarningToast("A plan with this name already exists.");
                                return;
                            }
                            submitPlanForm(id, planName, planPrice, planDuration, planFeature);
                        },
                        error: function (xhr) {
                            console.log('Duplicate check error:', xhr.responseText);
                            showDangerToast("Error checking plan name. Please try again.");
                        }
                    });
                } else {
                    submitPlanForm(id, planName, planPrice, planDuration, planFeature);
                }
            });

            // Function to submit the form
            function submitPlanForm(id, planName, planPrice, planDuration, planFeature) {
                const url = id ? '{{ route("admin.subscription.update", ["id" => ":id"]) }}'.replace(':id', id) : '{{ route("admin.subscription.store") }}';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: method,
                        name: planName,
                        price: planPrice,
                        duration: planDuration,
                        feature_ids: planFeature

                    },
                    success: function (response) {
                        console.log('Submit response:', response);
                        if (response.success) {
                            $('#planForm')[0].reset();
                            $('#plan_method').val('POST');
                            $('#formTitle').text('Add New Plan');
                            jQuery('#planModal').modal('hide');
                            loadPlans();
                            showSuccessToast(response.message);
                        } else {
                            showWarningToast(response.message || "Failed to save plan.");
                        }
                    },
                    error: function (xhr) {
                        console.log('Submit error:', xhr.responseText);
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let msg = '';
                            for (let field in errors) {
                                msg += errors[field].join(', ') + '\n';
                            }
                            showWarningToast(msg);
                        } else {
                            showDangerToast("Something went wrong. Please try again later.");
                        }
                    }
                });
            }


            // Open Edit Modal
            $(document).on('click', '.editBtn', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("admin.subscription.edit", ["id" => ":id"]) }}'.replace(':id', id), // Route fix
                    type: 'GET',
                    success: function (res) {
                        $('#formTitle').text('Edit Plan');
                        $('#plan_id').val(res.id);
                        $('#plan_name').val(res.name);
                        $('#plan_price').val(res.price);
                        $('#plan_duration').val(res.duration);
                        $('#plan_method').val('PUT');
                        // Remove $('#plan_feature').val(res.feature); as it's not needed
                        jQuery('#planModal').modal('show');
                        // Pre-check features if res.features exists (assuming API returns feature IDs)
                        if (res.features && Array.isArray(res.features)) {
                            $('input[name="feature_ids[]"]').prop('checked', false);
                            res.features.forEach(id => $('#feature' + id).prop('checked', true));
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        showDangerToast("Failed to load plan data.");
                    }
                });
            });
            // Delete Plan
            $(document).on('click', '.delBtn', function () {
                const id = $(this).data("id");
                if (confirm('Are you sure you want to delete this plan?')) {
                    $.ajax({
                        url: '{{ route("subscription.destroy", ["id" => ":id"]) }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (res) {
                            if (res.success) {
                                loadPlans();
                                showSuccessToast(res.message);
                            } else {
                                showWarningToast(res.message);
                            }
                        },
                        error: function () {
                            showDangerToast("Failed to delete plan. Please try again.");
                        }
                    });
                }
            });

            // Toast Functions
            function showSuccessToast(message) {
                toastr.success(message, 'Success', { timeOut: 5000 });
            }

            function showWarningToast(message) {
                toastr.warning(message, 'Warning', { timeOut: 5000 });
            }

            function showDangerToast(message) {
                toastr.error(message, 'Error', { timeOut: 5000 });
            }
        });
    </script>
    <style>
        .form-check-input {
            margin-left: 6px;
        }
    </style>


    <!-- Include Toastr CSS and JS -->
    @if (!isset($toastrIncluded))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <?php    $toastrIncluded = true; ?>
    @endif

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@endsection