@extends('escort.layout')
@section('title', 'Escort Photos')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="pb-1">Escort Photos</h5>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#photoModal">Add Photo</button>
        </div>
        <hr class="p-0 my-3">

        <div class="container">
            {{-- <div class="row photo-container"></div> --}}
            <div class="photo-container row"></div>
            <div class="text-center">
                <button id="loadMoreBtn" class="btn-sm btn-primary" data-offset="0">
                <span class="btn-text">Load More</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Add/Edit Photo Modal -->
<div class="modal fade" id="photoModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-body p-4">
                <form id="photoForm" class="small" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="photo_method" value="POST">
                    <input type="hidden" id="photo_id" name="photo_id">
                    <h5 id="formTitle">Add New Photo</h5>
                    <div id="msg"></div>
                    <hr>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                                <label for="title">Title *</label>
                                <input type="text" name="title" id="title" class="form-control" >
                            </div>
                            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                                <label for="is_public">Visibility</label>
                                <select name="is_public" id="is_public" class="form-control" >
                                    <option value="1">Public</option>
                                    <option value="0">Private</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                                <label for="file_path">Upload Photo</label>
                                <!--  changed to file_path[] for multiple -->
                                <input type="file" name="file_path[]" id="file_path" class="form-control file-uploader" accept="image/*" multiple >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit" class="btn btn-sm btn-primary mt-3">
                           <span class="btn-text">Upload</span>
                           <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Section -->
<script>
jQuery(document).ready(function($){

    let userId = "{{ Auth::guard('escort')->user()->id }}";

    function loadPhotos(offset = 0) {
        // Show loader on button
        $('#loadMoreBtn').prop('disabled', true);
        $('#loadMoreBtn .btn-text').addClass('d-none');
        $('#loadMoreBtn .spinner-border').removeClass('d-none');

        $.ajax({
            url: "{{ route('escort.photos.list', ':id') }}".replace(':id', userId),
            type: "GET",
            data: { offset: offset },
            dataType: 'json',
            success: function(response) {
                if(offset === 0){
                    $('.photo-container').html(response.html);
                } else {
                    $('.photo-container').append(response.html);
                }

                // Update offset
                $('#loadMoreBtn').data('offset', offset + (offset === 0 ? 8 : 8));

                // Show or hide button based on availability
                if(response.hasMore){
                    $('#loadMoreBtn').show();
                } else {
                    $('#loadMoreBtn').hide();
                }

                // If container is empty, show no photos message
                if($('.photo-container').children().length === 0){
                    $('.photo-container').html('<div class="text-center w-100 p-3">No photos available.</div>');
                    $('#loadMoreBtn').hide();
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Could not load photos.');
            },
            complete: function() {
                // Hide loader and enable button
                $('#loadMoreBtn').prop('disabled', false);
                $('#loadMoreBtn .btn-text').removeClass('d-none');
                $('#loadMoreBtn .spinner-border').addClass('d-none');
            }
        });
    }

    // Initial load
    loadPhotos();

    // Load more button click
    $('#loadMoreBtn').click(function(){
        let offset = $(this).data('offset') || 0;
        loadPhotos(offset);
    });



    // Submit Form (Multiple Files)
    $('#photoForm').on('submit', function(e){
        e.preventDefault();

        const id = $('#photo_id').val();
        const title = $('#title').val();
        const files = $('#file_path')[0].files;
        const is_public = $('#is_public').val();
        const description = $('#description').val();

        if(!title || (!files.length && !id)){
            showWarningToast("Title and Photo are required.");
            return;
        }

        const formData = new FormData(this);
        formData.append('_method', id ? 'PUT' : 'POST');

        const url = id
            ? '{{ route("escort.photos.update", ":id") }}'.replace(':id', id)
            : '{{ route("escort.photos.store") }}';

        // Show loader
        $("#submit").prop("disabled", true);
        $("#submit .btn-text").addClass("d-none");
        $("#submit .spinner-border").removeClass("d-none");

        $.ajax({
            url: url,
            type:'POST',
            data: formData,
            processData:false,
            contentType:false,
            dataType: 'json',
            success:function(response){

             // console.log("AJAX success:", response);

                if(response.success){
                    $('#photoForm')[0].reset();
                    $('#photo_id').val('');
                    $('#formTitle').text('Add New Photo');
                    jQuery('#photoModal').modal('hide');
                    loadPhotos();
                    showSuccessToast(response.message);
                } else {
                    showDangerToast(response.message || "Failed to save photo.");
                }
            },
            error:function(xhr){
                showDangerToast("Error: " + xhr.responseText);
            },
            complete:function(){
                // Hide loader and enable button again
                $("#submit").prop("disabled", false);
                $("#submit .btn-text").removeClass("d-none");
                $("#submit .spinner-border").addClass("d-none");
            }
        });
    });


    // Delete faqs
    $(document).on('click', '.remove-photo', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let container = $(this).closest('.photo-item');

        if (confirm('Are you sure you want to delete this faqs?')) {
            $.ajax({
                url: '{{ route("escort.photos.delete", ["id" => ":id"]) }}'.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                       if(response.success){
                            container.remove(); // Remove from DOM
                            showSuccessToast(response.message);
                             loadPhotos();
                        } else {
                            showWarningToast('Could not remove photo.');
                        }

                },
                error: function () {
                    showDangerToast("Failed to delete photo. Please try again.");
                }
            });
        }
    });


});
</script>

@endsection
