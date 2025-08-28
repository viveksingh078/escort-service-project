@extends('admin.layout')
@section('title', 'Membership Features')
@section('content')

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Membership Features</h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#featureModal">Add New
                    Feature</button>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="featureTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Feature Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Feature Modal -->
    <div class="modal fade" id="featureModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="featureForm" class="small" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="feature_method" value="POST">
                        <input type="hidden" id="feature_id" name="feature_id">
                        <h5 id="formTitle">Add New Feature</h5>
                        <div class="" id="msg"></div>
                        <hr>
                        <div class="form-group">
                            <label for="feature_name">Feature Name</label>
                            <input type="text" name="name" id="feature_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="feature_description">Description</label>
                            <textarea name="description" id="feature_description" class="form-control"></textarea>
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
            // Load Features Table
            function loadFeatures() {
                $('#featureTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route("admin.features.list") }}',
                        type: 'GET'
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'description', name: 'description' },
                        {
                            data: 'status',
                            name: 'status',
                            render: function (data) {
                                return data ? 'Active' : 'Inactive';
                            }
                        },
                        {
                            data: 'id',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return '<button class="btn btn-sm btn-primary editBtn" data-id="' + data + '">Edit</button>' +
                                    '<button class="btn btn-sm btn-danger delFeatureBtn" data-id="' + data + '">Delete</button>';
                            }
                        }
                    ],
                    error: function (xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                    }
                });
            }

            loadFeatures();

            // Submit Add/Edit Feature Form
            $('#featureForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#feature_id').val();
                const featureName = $('#feature_name').val().trim();
                const featureDescription = $('#feature_description').val().trim();

                if (!featureName || !featureDescription) {
                    showWarningToast("All fields are required.");
                    return;
                }

                const url = id ? '{{ route("admin.features.update", ["id" => ":id"]) }}'.replace(':id', id) : '{{ route("admin.features.store") }}';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: method,
                        name: featureName,
                        description: featureDescription
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#featureForm')[0].reset();
                            $('#feature_method').val('POST');
                            $('#formTitle').text('Add New Feature');
                            jQuery('#featureModal').modal('hide');
                            loadFeatures();
                            showSuccessToast(response.message);
                        } else {
                            showWarningToast(response.message || "Failed to save feature.");
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
            });

            // Open Edit Modal
            $(document).on('click', '.editBtn', function () {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route("admin.features.edit", ["id" => ":id"]) }}'.replace(':id', id),
                    type: 'GET',
                    success: function (res) {
                        $('#formTitle').text('Edit Feature');
                        $('#feature_id').val(res.id);
                        $('#feature_name').val(res.name);
                        $('#feature_description').val(res.description);
                        $('#feature_method').val('PUT');
                        jQuery('#featureModal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        showDangerToast("Failed to load feature data.");
                    }
                });
            });

            // Delete Feature
            $(document).on('click', '.delFeatureBtn', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this feature?')) {
                    $.ajax({
                        url: '{{ route("admin.features.delete", ["id" => ":id"]) }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.success) {
                                loadFeatures();
                                showSuccessToast(res.message);
                            } else {
                                showWarningToast(res.message);
                            }
                        },
                        error: function () {
                            showDangerToast("Failed to delete feature. Please try again.");
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
        .editBtn {
            margin-right: 5px;
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