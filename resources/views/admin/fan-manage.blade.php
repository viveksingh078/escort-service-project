@extends('admin.layout')
@section('title', 'Fan Manage')
@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="pb-1">Fan Manage</h5>
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#fanBackdrop">Add
          Fan</button>
      </div>
      <hr class="p-0 my-3">

      <div class="table-responsive">
        <table id="fanTable" class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Profile Picture</th>
              <th>Fan Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Modal -->
  <div class="modal fade" id="fanBackdrop" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-body">
          <form id="add-fan-form" class="small">
            @csrf
            <h5>Add Fan</h5>
            <div class="" id="msg"></div>
            <hr>
            <div class="form-group">
              <label for="fan_name">Fan Name</label>
              <input type="text" name="name" id="fan_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="fan_email">Email</label>
              <input type="email" name="email" id="fan_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="fan_profile_picture">Profile Picture</label>
              <input type="file" name="profile_picture" id="fan_profile_picture" class="form-control">
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

  <!-- View Modal -->
  <div class="modal fade" id="viewFanModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title">View Fan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Fan Name</label>
            <p id="view_name" class="form-control-plaintext"></p>
          </div>
          <div class="form-group">
            <label>Email</label>
            <p id="view_email" class="form-control-plaintext"></p>
          </div>
          <div class="form-group">
            <label>Profile Picture</label>
            <img id="view_profile_picture" src="" class="rounded-circle"
              style="width: 100px; height: 100px; object-fit: cover;">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editFanModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title">Edit Fan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form id="edit-fan-form" class="small">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
              <label for="edit_name">Fan Name</label>
              <input type="text" id="edit_name" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_email">Email</label>
              <input type="email" id="edit_email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_profile_picture">Profile Picture</label>
              <input type="file" id="edit_profile_picture" name="profile_picture" class="form-control">
              <img id="edit_current_picture" src="" class="rounded-circle mt-2"
                style="width: 100px; height: 100px; object-fit: cover;">
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

      // Load Fan Table
      function loadFans() {
        $('#fanTable').DataTable({
          processing: true,
          serverSide: true,
          destroy: true,
          ajax: { url: '{{ route("admin.fans.index") }}', type: 'GET' },
          columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            {
              data: 'profile_picture',
              render: function (data, type, row) {
                return data === 'images/default-profile.jpg'
                  ? `<img src="{{ asset('images/default-profile.jpg') }}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">`
                  : `<img src="${window.location.origin}/storage/${data}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">`;
              }
            },
            { data: 'name' },
            { data: 'email' },
            {
              data: 'action',
              orderable: false,
              searchable: false,
              render: function (data, type, row) {
                return `
                        <button class="btn btn-sm btn-info viewFanBtn" data-id="${row.id}">View</button>
                        <button class="btn btn-sm btn-warning editFanBtn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delFanBtn" data-id="${row.id}">Delete</button>
                      `;
              }
            }
          ]
        });
      }

      // Add Fan
      $('#add-fan-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('role', 'fan');

        $.ajax({
          url: "{{ route('admin.fans.store') }}",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              $('#add-fan-form')[0].reset();
              jQuery('#fanBackdrop').modal('hide');
              loadFans();
              alert(response.message);
            } else { alert(response.message); }
          },
          error: function (xhr) {
            alert("Error: " + (xhr.responseJSON?.message || "Something went wrong"));
          }
        });
      });

      // Delete Fan
      $(document).on('click', '.delFanBtn', function () {
        const id = $(this).data("id");
        if (confirm('Delete this fan?')) {
          $.ajax({
            url: '{{ route("admin.fans.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
              if (res.success) { loadFans(); alert(res.message); }
              else { alert(res.message); }
            }
          });
        }
      });

      // View Fan
      $(document).on('click', '.viewFanBtn', function () {
        const id = $(this).data("id");
        $.ajax({
          url: '{{ route("admin.fans.view", ":id") }}'.replace(':id', id),
          type: 'GET',
          success: function (response) {
            if (response.success) {
              $('#view_name').text(response.data.name);
              $('#view_email').text(response.data.email);
              let profilePicture = response.data.profile_picture === 'images/default-profile.jpg'
                ? '{{ asset('images/default-profile.jpg') }}'
                : `${window.location.origin}/storage/${response.data.profile_picture}`;
              $('#view_profile_picture').attr('src', profilePicture);
              jQuery('#viewFanModal').modal('show');
            } else { alert(response.message); }
          }
        });
      });

      // Edit Fan
      $(document).on('click', '.editFanBtn', function () {
        const id = $(this).data("id");
        $.ajax({
          url: '{{ route("admin.fans.edit", ":id") }}'.replace(':id', id),
          type: 'GET',
          success: function (response) {
            if (response.success) {
              $('#edit_id').val(response.data.id);
              $('#edit_name').val(response.data.name);
              $('#edit_email').val(response.data.email);
              let profilePicture = response.data.profile_picture === 'images/default-profile.jpg'
                ? '{{ asset('images/default-profile.jpg') }}'
                : `${window.location.origin}/storage/${response.data.profile_picture}`;
              $('#edit_current_picture').attr('src', profilePicture);
              jQuery('#editFanModal').modal('show');
            } else { alert(response.message); }
          }
        });
      });

      // Update Fan
      $('#edit-fan-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
          url: '{{ route("admin.fans.update", ":id") }}'.replace(':id', $('#edit_id').val()),
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              $('#editFanModal').modal('hide');
              loadFans();
              alert(response.message);
            } else { alert(response.message); }
          }
        });
      });

      loadFans();
    });
  </script>
@endsection