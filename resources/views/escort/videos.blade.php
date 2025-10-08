@extends('escort.layout')
@section('title', 'Escort Videos')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="pb-1">Escort Videos</h5>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#videoModal">Add video</button>
        </div>
        <hr class="p-0 my-3">

        <div class="container">
            {{-- <div class="row video-container"></div> --}}
            <div class="video-container row"></div>
            <div class="text-center">
                <button id="loadMoreBtn" class="btn-sm btn-primary" data-offset="0">
                <span class="btn-text">Load More</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Add/Edit video Modal -->
<div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-body p-4">
                <form id="videoForm" class="small" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="video_method" value="POST">
                    <input type="hidden" id="video_id" name="video_id">
                    <h5 id="formTitle">Add New video</h5>
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
                                <label for="file_path">Upload video</label>
                                <!--  changed to file_path[] for multiple -->
                                <input type="file" name="file_path[]" id="file_path" class="form-control file-uploader" accept="video/*" multiple >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <div id="uploadProgressContainer" class="progress my-2" style="display:none;">
                            <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar" style="width:0%">0 MB</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1 btn-close" data-dismiss="modal">Close</button>
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



<div class="modal fade thumbnailModal" id="thumbnailModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-0">
            <div class="modal-body p-4">
                 <h5 class="mb-3">Upload Thumbnail</h5>
                <form id="thumbnailForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="video_id_thumbnail" name="video_id">
                    <div class="mb-3">
                        <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-end">
                       <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1 btn-close" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary  mx-1" id="uploadThumbnailBtn">
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

    function loadVideos(offset = 0) {
        // Show loader on button
        $('#loadMoreBtn').prop('disabled', true);
        $('#loadMoreBtn .btn-text').addClass('d-none');
        $('#loadMoreBtn .spinner-border').removeClass('d-none');

        $.ajax({
            url: "{{ route('escort.videos.list', ':id') }}".replace(':id', userId),
            type: "GET",
            data: { offset: offset },
            dataType: 'json',
            success: function(response) {
                if(offset === 0){
                    $('.video-container').html(response.html);
                } else {
                    $('.video-container').append(response.html);
                }

                // Update offset
                $('#loadMoreBtn').data('offset', offset + (offset === 0 ? 8 : 8));

                // Show or hide button based on availability
                if(response.hasMore){
                    $('#loadMoreBtn').show();
                } else {
                    $('#loadMoreBtn').hide();
                }

                // If container is empty, show no Videos message
                if($('.video-container').children().length === 0){
                    $('.video-container').html('<div class="text-center w-100 p-3">No Videos available.</div>');
                    $('#loadMoreBtn').hide();
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Could not load Videos.');
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
    loadVideos();

    // Load more button click
    $('#loadMoreBtn').click(function(){
        let offset = $(this).data('offset') || 0;
        loadVideos(offset);
    });



   // Submit Form (Multiple Files)
    $('#videoForm').on('submit', function(e){
        e.preventDefault();

        const id = $('#video_id').val();
        const title = $('#title').val();
        const files = $('#file_path')[0].files;
        const is_public = $('#is_public').val();
        const description = $('#description').val();

        if(!title || (!files.length && !id)){
            showWarningToast("Title and video are required.");
            return;
        }

        const formData = new FormData(this);
        formData.append('_method', id ? 'PUT' : 'POST');

        const url = id
            ? '{{ route("escort.videos.update", ":id") }}'.replace(':id', id)
            : '{{ route("escort.videos.store") }}';

        // Show loader
        $("#submit").prop("disabled", true);
        $("#submit .btn-text").addClass("d-none");
        $("#submit .spinner-border").removeClass("d-none");

        // Hide modal close button while uploading
        $('#videoModal .btn-close').hide();

        // Show progress bar
        $('#uploadProgressContainer').show();
        $('#uploadProgressBar').css('width', '0%').text('0 MB');

        $.ajax({
            url: url,
            type:'POST',
            data: formData,
            processData:false,
            contentType:false,
            dataType: 'json',
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        const loadedMB = (evt.loaded / (1024*1024)).toFixed(2);
                        const totalMB = (evt.total / (1024*1024)).toFixed(2);
                        const percentComplete = Math.round((evt.loaded / evt.total) * 100);

                        $('#uploadProgressBar').css('width', percentComplete + '%')
                                               .text(loadedMB + ' MB / ' + totalMB + ' MB');
                    }
                }, false);
                return xhr;
            },
            success:function(response){
                if(response.success){
                    $('#videoForm')[0].reset();
                    $('#video_id').val('');
                    $('#formTitle').text('Add New Video');
                    jQuery('#videoModal').modal('hide');
                    loadVideos();
                    showSuccessToast(response.message);
                } else {
                    showDangerToast(response.message || "Failed to save video.");
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

                // Show modal close button again
                $('#videoModal .btn-close').show();

                // Hide progress bar after short delay
                setTimeout(() => {
                    $('#uploadProgressContainer').hide();
                }, 1500);
            }
        });
    });



    // Delete faqs
    $(document).on('click', '.remove-video', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let container = $(this).closest('.video-item');

        if (confirm('Are you sure you want to delete this faqs?')) {
            $.ajax({
                url: '{{ route("escort.videos.delete", ["id" => ":id"]) }}'.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                       if(response.success){
                            container.remove(); // Remove from DOM
                            showSuccessToast(response.message);
                             loadVideos();
                        } else {
                            showWarningToast('Could not remove video.');
                        }

                },
                error: function () {
                    showDangerToast("Failed to delete video. Please try again.");
                }
            });
        }
    });


     // Click on "Add Thumbnail" button
    $(document).on('click', '.add-thumbnail', function(){
        let videoId = $(this).data('id');
        $('#video_id_thumbnail').val(videoId);
        $('#thumbnailInput').val('');
        const modal = new bootstrap.Modal(document.getElementById('thumbnailModal'));
        modal.show();
    });

    // Submit thumbnail form
    $('#thumbnailForm').on('submit', function(e){
        e.preventDefault();

        const form = $(this);
        const videoId = $('#video_id_thumbnail').val();
        const fileInput = $('#thumbnailInput')[0].files[0];

        if(!fileInput){
            alert("Please select a thumbnail image.");
            return;
        }

        const formData = new FormData();
        formData.append('thumbnail', fileInput);
        formData.append('video_id', videoId);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loader
        $('#uploadThumbnailBtn').prop('disabled', true);
        $('#uploadThumbnailBtn .btn-text').addClass('d-none');
        $('#uploadThumbnailBtn .spinner-border').removeClass('d-none');

        $.ajax({
            url: '{{ route("escort.videos.thumbnail") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
                    jQuery('#thumbnailModal').modal('hide');
                    showSuccessToast(response.message);
                    loadVideos();
                } else {
                    showDangerToast(response.message || "Failed to upload thumbnail.");
                }
            },
            error: function(xhr){
                showDangerToast("Error: " + xhr.responseText);
            },
            complete: function(){
                $('#uploadThumbnailBtn').prop('disabled', false);
                $('#uploadThumbnailBtn .btn-text').removeClass('d-none');
                $('#uploadThumbnailBtn .spinner-border').addClass('d-none');
            }
        });
    });

    // Remove thumbnail
    $(document).on('click', '.remove-thumbnail', function(e) {
        e.preventDefault();
        const videoId = $(this).data('id');
        const container = $(this).closest('.album-video-item');

        if (confirm('Are you sure you want to remove this thumbnail?')) {
            $.ajax({
                url: '{{ route("escort.videos.thumbnail.remove") }}', // Create this route in web.php
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    video_id: videoId
                },
                success: function(response) {
                    if(response.success){
                        showSuccessToast(response.message);
                        loadVideos();
                    } else {
                        showWarningToast(response.message || 'Could not remove thumbnail.');
                    }
                },
                error: function(xhr) {
                    showDangerToast('Failed to remove thumbnail. Please try again.');
                }
            });
        }
    });



});
</script>

@endsection
