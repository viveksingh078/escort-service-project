@extends('admin.layout')
@section('title', 'Ads Space')
@section('content')

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Ads Space</h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adsBackdrop">Add New
                    Ad</button>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="adsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Position</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="adsBackdrop" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="add-ads-form" class="small" enctype="multipart/form-data">
                        @csrf
                        <h5>Add New Ad</h5>
                        <div id="msg"></div>
                        <hr>
                        <div class="form-group">
                            <label for="ad_title">Title *</label>
                            <input type="text" name="title" id="ad_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ad_description">Description</label>
                            <textarea name="description" id="ad_description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ad_image">Image *</label>
                            <input type="file" name="image" id="ad_image" class="form-control" required>
                            <img id="editAdImagePreview" src="" alt="Ad Image" width="100" class="mt-2">
                        </div>
                        <div class="form-group">
                            <label for="ad_position">Ad Position *</label>
                            <select name="position" id="ad_position" class="form-control" required>
                                <option value="">Select Position</option>
                                <option value="topbar">Topbar</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ad_link">Link Address *</label>
                            <input type="url" name="link" id="ad_link" class="form-control" required>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editAdsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="edit-ads-form" class="small" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <h5>Edit Ad</h5>
                        <input type="hidden" id="edit_ad_id">
                        <hr>
                        <div class="form-group">
                            <label for="edit_ad_title">Title *</label>
                            <input type="text" name="title" id="edit_ad_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_ad_description">Description</label>
                            <textarea name="description" id="edit_ad_description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_ad_image">Image</label>
                            <input type="file" name="image" id="edit_ad_image" class="form-control">

                            <div id="edit_ad_image_preview" class="mt-2"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_ad_position">Ad Position *</label>
                            <select name="position" id="edit_ad_position" class="form-control" required>
                                <option value="">Select Position</option>
                                <option value="topbar">Topbar</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_ad_link">Link Address *</label>
                            <input type="url" name="link" id="edit_ad_link" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary mt-3">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewAdsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <h5>View Ad</h5>
                    <hr>
                    <div class="form-group">
                        <label>Title</label>
                        <p id="view_ad_title"></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p id="view_ad_description"></p>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <div id="view_ad_image"></div>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <p id="view_ad_position"></p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Load Ads Table
            function loadAdsTable() {
                $('#adsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route("admin.ads.list") }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        error: function (xhr, status, error) {
                            console.log('AJAX Error: ', status, error);
                            console.log('Response: ', xhr.responseText);
                            showDangerToast('Failed to load ads. Check console for details.');
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'image', name: 'image', orderable: false, searchable: false },
                        { data: 'title', name: 'title' },
                        { data: 'description', name: 'description' },
                        { data: 'position', name: 'position' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            }

            // Submit Add Ad Form
            $('#add-ads-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.ads.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#add-ads-form')[0].reset();
                            jQuery('#adsBackdrop').modal('hide');
                            loadAdsTable();
                            showSuccessToast(response.message);
                        } else {
                            showDangerToast(response.message);
                        }
                    },
                    error: function (xhr) {
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
            $(document).on('click', '.editAdBtn', function () {
                const id = $(this).data("id");
                $.get('/admin/ads/' + id + '/edit', function (data) {
                    $('#edit_ad_id').val(data.id);
                    $('#edit_ad_title').val(data.title);
                    $('#edit_ad_description').val(data.description);
                    $('#edit_ad_link').val(data.link);
                    $('#edit_ad_position').val(data.position);
                    if (data.image) {
                        $('#edit_ad_image_preview').html('<img src="/storage/' + data.image + '" width="120">');
                    } else {
                        $('#edit_ad_image_preview').html('');
                    }
                    jQuery('#editAdsModal').modal('show');
                }).fail(function (xhr) {
                    console.log('Edit Fetch Error: ', xhr.responseText);
                    showDangerToast('Failed to load ad for editing.');
                });
            });

            // Image preview for edit modal
            $('#edit_ad_image').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#edit_ad_image_preview').html('<img src="' + e.target.result + '" width="120">');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#edit_ad_image_preview').html('');
                }
            });

            // Submit Edit Ad Form
            $('#edit-ads-form').on('submit', function (e) {
                e.preventDefault();
                let id = $('#edit_ad_id').val();
                let formData = new FormData(this);
                // Debug FormData
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                formData.append('_method', 'PUT');
                $.ajax({
                    url: '/admin/ads/' + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response);
                        showSuccessToast('Ad updated successfully!');
                        jQuery('#editAdsModal').modal('hide');
                        $('#adsTable').DataTable().ajax.url('/admin/ads/list?t=' + new Date().getTime()).load(); (function () {
                            console.log('Table reloaded with new data: ' + response.new_image);
                        });
                    },
                    error: function (xhr) {
                        console.log('Update Error: ', xhr.responseText);
                        showDangerToast('Update failed! Check console for details.');
                    }
                });
            });

            // Delete Ad
            $(document).on('click', '.delAdBtn', function () {
                const id = $(this).data("id");
                if (confirm('Are you sure you want to delete this ad?')) {
                    $.ajax({
                        url: '/admin/ads/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (res) {
                            if (res.success) {
                                loadAdsTable();
                                showSuccessToast(res.message);
                            } else {
                                showWarningToast(res.message);
                            }
                        },
                        error: function () {
                            showDangerToast("Failed to delete ad. Please try again.");
                        }
                    });
                }
            });

            // Open View Modal
            $(document).on('click', '.viewAdBtn', function () {
                const id = $(this).data("id");
                $.get('/admin/ads/' + id, function (data) {
                    $('#view_ad_title').text(data.title || 'N/A');
                    $('#view_ad_description').text(data.description || 'N/A');
                    $('#view_ad_position').text(data.position || 'N/A');
                    if (data.image) {
                        $('#view_ad_image').html('<img src="/storage/' + data.image + '" width="120">');
                    } else {
                        $('#view_ad_image').html('No image');
                    }
                    jQuery('#viewAdsModal').modal('show');
                }).fail(function (xhr) {
                    console.log('View Fetch Error: ', xhr.responseText);
                    showDangerToast('Failed to load ad details.');
                });
            });

            // Initial DataTable Load
            loadAdsTable();
        });
    </script>

@endsection