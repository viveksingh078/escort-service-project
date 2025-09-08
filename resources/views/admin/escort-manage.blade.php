@extends('admin.layout')
@section('title', 'Escort Manage')
@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="pb-1">Escort Manage</h5>
        <a href="{{ route('admin.escort') }}" class="btn btn-sm btn-primary">Add Escort</a>
      </div>
      <hr class="p-0 my-3">

      <div class="table-responsive">
        <table id="escortTable" class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Profile Picture</th>
              <th>Escort Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  {{-- View Escort Modal --}}
  <div class="modal fade" id="viewEscortModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title">View Escort</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><b>Name:</b> <span id="view_name"></span></p>
          <p><b>Email:</b> <span id="view_email"></span></p>
          <p><b>Profile Picture:</b></p>
          <img id="view_profile_picture" src="" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
        </div>
      </div>
    </div>
  </div>

  {{-- Edit Escort Modal --}}
  <div class="modal fade" id="editEscortModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title">Edit Escort</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="edit-escort-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
              <label for="edit_name">Escort Name</label>
              <input type="text" id="edit_name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="edit_email">Email</label>
              <input type="email" id="edit_email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="edit_profile_picture">Profile Picture</label>
              <input type="file" id="edit_profile_picture" name="profile_picture" class="form-control">
              <img id="edit_current_picture" src="" class="rounded-circle mt-2"
                style="width:100px;height:100px;object-fit:cover;">
            </div>
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-sm btn-primary mt-3">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    jQuery(document).ready(function ($) {
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      function loadEscorts() {
        $('#escortTable').DataTable({
          processing: true,
          serverSide: true,
          destroy: true,
          ajax: '{{ route("admin.escorts.index") }}',
          columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            {
              data: 'profile_picture', render: function (data, type, row) {
                return data === 'images/default-profile.jpg'
                  ? `<img src="{{ asset('images/default-profile.jpg') }}" class="rounded-circle" style="width:50px;height:50px;">`
                  : `<img src="{{ asset('storage') }}/${data}" class="rounded-circle" style="width:50px;height:50px;">`;
              }
            },
            { data: 'name' },
            { data: 'email' },
            { data: 'action', orderable: false, searchable: false }
          ]
        });
      }

      loadEscorts();

      // Delete Escort
      $(document).on('click', '.delEscortBtn', function () {
        let id = $(this).data('id');
        if (confirm("Delete this escort?")) {
          $.ajax({
            url: '{{ route("admin.escorts.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            success: function (res) { if (res.success) { loadEscorts(); alert(res.message); } }
          });
        }
      });

      // View Escort
      $(document).on('click', '.viewEscortBtn', function () {
        let id = $(this).data('id');
        $.get('{{ route("admin.escorts.view", ":id") }}'.replace(':id', id), function (res) {
          if (res.success) {
            $('#view_name').text(res.data.name);
            $('#view_email').text(res.data.email);
            $('#view_profile_picture').attr('src',
              res.data.profile_picture === 'images/default-profile.jpg'
                ? "{{ asset('images/default-profile.jpg') }}"
                : "{{ asset('storage') }}/" + res.data.profile_picture
            );
            jQuery('#viewEscortModal').modal('show');
          }
        });
      });

      // Edit Escort
      $(document).on('click', '.editEscortBtn', function () {
        let id = $(this).data('id');
        $.get('{{ route("admin.escorts.edit", ":id") }}'.replace(':id', id), function (res) {
          if (res.success) {
            $('#edit_id').val(res.data.id);
            $('#edit_name').val(res.data.name);
            $('#edit_email').val(res.data.email);
            $('#edit_current_picture').attr('src',
              res.data.profile_picture === 'images/default-profile.jpg'
                ? "{{ asset('images/default-profile.jpg') }}"
                : "{{ asset('storage') }}/" + res.data.profile_picture
            );
            jQuery('#editEscortModal').modal('show');
          }
        });
      });

      // Update Escort
      $('#edit-escort-form').submit(function (e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
          url: '{{ route("admin.escorts.update", ":id") }}'.replace(':id', id),
          type: 'POST',
          data: formData, processData: false, contentType: false,
          success: function (res) {
            if (res.success) { $('#editEscortModal').modal('hide'); loadEscorts(); alert(res.message); }
          }
        });
      });
    });
  </script>
@endsection