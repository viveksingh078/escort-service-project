@extends('admin.layout')
@section('title', 'Ads Space')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="pb-1">Ads Space</h5>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adsModal">Add New Ads</button>
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
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Ads Modal -->
<div class="modal fade" id="adsModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-0">
            <div class="modal-body p-4">
                <form id="adsForm" class="small" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="ads_method" value="POST">
                    <input type="hidden" id="ads_id" name="ads_id">
                    <h5 id="formTitle">Add New Ads</h5>
                    <div id="msg"></div>
                    <hr>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                                <label for="ads_title">Title</label>
                                <input type="text" name="ads_title" id="ads_title" class="form-control">
                            </div>
                            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                                <label for="ads_image">Image</label>
                                <input type="file" name="ads_image" id="ads_image" class="form-control" accept="image/*">
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                                <label for="ads_link">Link Address *</label>
                                <input type="url" name="ads_link" id="ads_link" class="form-control">
                            </div>
                            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                                <label for="ads_position">Ad Position</label>
                                <select name="ads_position" id="ads_position" class="form-control">
                                    <option value="">Select Position</option>
                                    <option value="topbar">Topbar</option>
                                    <option value="sidebar">Sidebar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ads_description">Description</label>
                        <textarea name="ads_description" id="ads_description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- View Ads Modal -->
<div class="modal fade" id="viewAdsModal" tabindex="-1" aria-labelledby="viewAdsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <h5 id="viewAdsTitle"></h5>
        <hr>
        <div id="viewAdsImage" class="mb-3 text-center"></div>
        <p><strong>Link:</strong> <span id="viewAdsLink"></span></p>
        <p><strong>Position:</strong> <span id="viewAdsPosition"></span></p>
        <p><strong>Description:</strong> <span id="viewAdsDescription"></span></p>
        
      </div>
      <div class="modal-footer justify-content-between align-items-center">
        <small class="text-dark small font-weight-bold mt-3">Created at: <span id="viewAdsCreatedAt"></span></small>
        <button type="button" class="btn btn-sm btn-secondary mx-1" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- JS Section -->
<script>
jQuery(document).ready(function($){

    // Image Preview
    $('#ads_image').on('change', function(){
        if(this && this.files && this.files[0]){
            const reader = new FileReader();
            reader.onload = function(e){
                $('#imagePreview').html('<img src="'+ e.target.result +'" class="img-thumbnail" style="max-width:200px;">');
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#imagePreview').html('');
        }
    });

    // Load Ads Table
    function loadadss(){
        $('#adsTable').DataTable({
            processing:true,
            serverSide:true,
            destroy:true,
            ajax:{
                url:'{{ route("admin.ads.list") }}',
                type:'GET',
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            },
            columns:[
                {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
                {data:'image', name:'image', orderable:false, searchable:false},
                {data:'title', name:'title'},
                {data:'description', name:'description'},
                {data:'position', name:'position'},
                {data:'created_at', name:'created_at'},
                {data:'action', name:'action', orderable:false, searchable:false}
            ],
            language:{emptyTable:"No ads found. Please add new ads."}
        });
    }
    loadadss();

    // Submit Form
    $('#adsForm').on('submit', function(e){
        e.preventDefault();

        const id = $('#ads_id').val();
        const ads_title = $('#ads_title').val();
        const ads_image = $('#ads_image')[0] ? $('#ads_image')[0].files[0] : null;
        const ads_link = $('#ads_link').val();
        const ads_position = $('#ads_position').val();
        const ads_description = $('#ads_description').val();

        if(!ads_title || (!ads_image && !id)){
            showWarningToast("Title and Image are required.");
            return;
        }

        const formData = new FormData();
        formData.append('_token','{{ csrf_token() }}');
        formData.append('_method', id ? 'PUT' : 'POST');
        formData.append('ads_title', ads_title);
        if(ads_image) formData.append('ads_image', ads_image);
        formData.append('ads_link', ads_link);
        formData.append('ads_position', ads_position);
        formData.append('ads_description', ads_description);

        const url = id
            ? '{{ route("admin.ads.update", ["id" => ":id"]) }}'.replace(':id', id)
            : '{{ route("admin.ads.store") }}';

        $.ajax({
            url: url,
            type:'POST',
            data: formData,
            processData:false,
            contentType:false,
            success:function(response){
                if(response.success){
                    $('#adsForm')[0].reset();
                    $('#ads_id').val('');
                    $('#imagePreview').html('');
                    $('#formTitle').text('Add New Ads');
                    jQuery('#adsModal').modal('hide');
                    loadadss();
                    showSuccessToast(response.message);
                } else {
                    showWarningToast(response.message || "Failed to save ads.");
                }
            },
            error:function(xhr){
                if(xhr.status === 422){
                    const errors = xhr.responseJSON.errors;
                    let msg = '';
                    for(let field in errors){ msg += errors[field].join(', ') + '\n'; }
                    showWarningToast(msg);
                } else {
                    showDangerToast("Something went wrong. Please try again later.");
                }
            }
        });
    });

    // Open Edit Modal
    $(document).on('click','.editBtn', function(){
        var id = $(this).data('id');
        $.ajax({
            url:'{{ route("admin.ads.edit", ["id" => ":id"]) }}'.replace(':id', id),
            type:'GET',
            success:function(res){
              //console.log(res);
                $('#formTitle').text('Edit Ads');
                $('#ads_id').val(res.id);
                $('#ads_title').val(res.title);
                $('#ads_link').val(res.link);
                $('#ads_position').val(res.position);
                $('#ads_description').val(res.description);
                $('#ads_method').val('PUT');

                // Show existing image
                if(res.image){
                    $('#imagePreview').html('<img src="{{ asset('') }}'+res.image+'" class="img-thumbnail" style="max-width:200px;">');
                } else { $('#imagePreview').html(''); }

                jQuery('#adsModal').modal('show');
            },
            error:function(xhr){
                showDangerToast("Failed to load ads data.");
            }
        });
    });

    // Delete Ads
    $(document).on('click','.deladsBtn', function(){
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this ad?')){
            $.ajax({
                url:'{{ route("admin.ads.delete", ["id" => ":id"]) }}'.replace(':id', id),
                type:'DELETE',
                data:{_token:'{{ csrf_token() }}'},
                success:function(res){
                    if(res.success){ loadadss(); showSuccessToast(res.message); }
                    else { showWarningToast(res.message); }
                },
                error:function(){ showDangerToast("Failed to delete ads. Please try again."); }
            });
        }
    });

    // View Ads
    $(document).on('click', '.viewBtn', function () {
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('admin.ads.view', ':id') }}".replace(':id', id),
            type: "GET",
            success: function (res) {
              console.log(res);
              console.log(res);
                $('#viewAdsTitle').text(res.title);
                $('#viewAdsLink').text(res.link || '—');
                $('#viewAdsPosition').text(res.position || '—');
                $('#viewAdsDescription').text(res.description || '—');
                $('#viewAdsCreatedAt').text(res.created_at);

                if (res.image) {
                    $('#viewAdsImage').html('<img src="{{ asset("") }}' + res.image + '" class="img-fluid" style="max-width:300px;">');
                } else {
                    $('#viewAdsImage').html('<em>No image available</em>');
                }

                jQuery('#viewAdsModal').modal('show');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showDangerToast('Could not load ad details.');
            }
        });
    });


});
</script>

@endsection
