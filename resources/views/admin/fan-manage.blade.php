@extends('admin.layout')
@section('title', '{{ $title ?? "Manage Fan" }}')
@section('content')
  <!-- Ensure CSRF token is available -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="pb-1">{{ $title ?? "Manage Fan" }}</h5>
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#fanBackdrop">Add
          {{ ucfirst($role ?? 'Fan') }}</button>
      </div>
      <hr class="p-0 my-3">

      <div class="table-responsive">
        <table id="userTable" class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Profile Picture</th>
              <th>{{ ucfirst($role ?? 'Fan') }} Name</th>
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
          <form id="add-user-form" class="small">
            @csrf
            <input type="hidden" name="role" value="{{ $role ?? 'fan' }}">
            <h5>Add {{ ucfirst($role ?? 'Fan') }}</h5>
            <div class="" id="msg"></div>
            <hr>
            <div class="form-group">
              <label for="user_name">{{ ucfirst($role ?? 'Fan') }} Name</label>
              <input type="text" name="name" id="user_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="user_email">Email</label>
              <input type="email" name="email" id="user_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="profile_picture">Profile Picture</label>
              <input type="file" name="profile_picture" id="profile_picture" class="form-control">
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
  <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title" id="viewUserModalLabel">View {{ ucfirst($role ?? 'Fan') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>{{ ucfirst($role ?? 'Fan') }} Name</label>
            <p id="view_name" class="form-control-plaintext"></p>
          </div>
          <div class="form-group">
            <label>Email</label>
            <p id="view_email" class="form-control-plaintext"></p>
          </div>
          <div class="form-group">
            <label>Profile Picture</label>
            <img id="view_profile_picture" src="" alt="Profile Picture" class="rounded-circle"
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
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit {{ ucfirst($role ?? 'Fan') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="edit-user-form" class="small">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id">
            <input type="hidden" name="role" value="{{ $role ?? 'fan' }}">
            <div class="form-group">
              <label for="edit_name">{{ ucfirst($role ?? 'Fan') }} Name</label>
              <input type="text" id="edit_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_email">Email</label>
              <input type="email" id="edit_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_profile_picture">Profile Picture</label>
              <input type="file" id="edit_profile_picture" class="form-control">
              <img id="edit_current_picture" src="" alt="Current Profile Picture" class="rounded-circle mt-2"
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
      // Ensure CSRF token is set for all AJAX calls
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Load User Table
      function loadUsers() {
        $('#userTable').DataTable({
          processing: true,
          serverSide: true,
          destroy: true,
          ajax: {
            url: '{{ route("admin.fans.index") }}',
            type: 'GET'
          },
          columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
              data: 'profile_picture',
              name: 'profile_picture',
              render: function (data, type, row) {
                return data && data !== 'images/default-profile.png' ?
                  `<img src="{{ asset('storage/') }}/${data}" alt="${row.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">` :
                  `<img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">`;
              }
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            {
              data: 'action',
              name: 'action',
              orderable: false,
              searchable: false,
              render: function (data, type, row) {
                return `
                                  <button class="btn btn-sm btn-info viewFanBtn" data-id="${row.id}" data-role="${row.role}">View</button>
                                  <button class="btn btn-sm btn-warning editFanBtn" data-id="${row.id}" data-role="${row.role}">Edit</button>
                                  <button class="btn btn-sm btn-danger delFanBtn" data-id="${row.id}" data-role="${row.role}">Delete</button>
                                `;
              }
            }
          ]
        });
      }

      // Submit Add User Form
      $('#add-user-form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
          url: "{{ route('admin.fans.store') }}",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              $('#add-user-form')[0].reset();
              jQuery('#fanBackdrop').modal('hide');
              loadUsers();
              alert(response.message);
            } else {
              alert(response.message);
            }
          },
          error: function (xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let msg = '';
              for (let field in errors) {
                msg += errors[field].join(', ') + '\n';
              }
              alert(msg);
            } else {
              alert("Something went wrong. Please try again later.");
            }
          }
        });
      });

      // Delete User
      $(document).on('click', '.delFanBtn', function () {
        const id = $(this).data("id");
        const role = $(this).data("role");

        if (confirm('Are you sure you want to delete this user?')) {
          $.ajax({
            url: '{{ route("admin.fans.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
              if (res.success) {
                loadUsers();
                alert(res.message);
              } else {
                alert(res.message);
              }
            },
            error: function () {
              alert("Failed to delete user. Please try again.");
            }
          });
        }
      });

      // View User
      $(document).on('click', '.viewFanBtn', function () {
        const id = $(this).data("id");
        const role = $(this).data("role");
        $.ajax({
          url: '{{ route("admin.fans.view", ":id") }}'.replace(':id', id),
          type: 'GET',
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function (response) {
            if (response.success) {
              $('#view_name').text(response.data.name);
              $('#view_email').text(response.data.email);
              let profilePicture = response.data.profile_picture ?
                '{{ asset("storage") }}/' + response.data.profile_picture :
                '{{ asset("images/default-profile.png") }}';
              $('#view_profile_picture').attr('src', profilePicture);
              jQuery('#viewUserModal').modal('show');
            } else {
              alert(response.message);
            }
          },
          error: function () {
            alert("Failed to load user details. Please try again.");
          }
        });
      });

      // Edit User
      $(document).on('click', '.editFanBtn', function () {
        const id = $(this).data("id");
        const role = $(this).data("role");
        $.ajax({
          url: '{{ route("admin.fans.edit", ":id") }}'.replace(':id', id),
          type: 'GET',
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function (response) {
            if (response.success) {
              $('#edit_id').val(response.data.id);
              $('#edit_name').val(response.data.name);
              $('#edit_email').val(response.data.email);
              let profilePicture = response.data.profile_picture ?
                '{{ asset("storage") }}/' + response.data.profile_picture :
                '{{ asset("images/default-profile.png") }}';
              $('#edit_current_picture').attr('src', profilePicture);
              jQuery('#editUserModal').modal('show');
            } else {
              alert(response.message);
            }
          },
          error: function () {
            alert("Failed to load user for editing. Please try again.");
          }
        });
      });

      // Submit Edit User Form
      $('#edit-user-form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData();
        let nameValue = $('#edit_name').val();
        let emailValue = $('#edit_email').val() + '+update' + Math.floor(Math.random() * 1000) + '@gmail.com';
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('id', $('#edit_id').val());
        formData.append('name', nameValue);
        formData.append('email', emailValue);
        formData.append('role', '{{ $role ?? "fan" }}');
        if ($('#edit_profile_picture')[0].files[0]) {
          formData.append('profile_picture', $('#edit_profile_picture')[0].files[0]);
        }

        $.ajax({
          url: '{{ route("admin.fans.update", ":id") }}'.replace(':id', $('#edit_id').val()),
          type: 'PUT',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              jQuery('#editUserModal').modal('hide');
              loadUsers();
              alert(response.message);
            } else {
              alert(response.message);
            }
          },
          error: function (xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let msg = 'Validation Errors:\n';
              for (let field in errors) {
                msg += `${field}: ${errors[field].join(', ')}\n`;
              }
              alert(msg);
            } else if (xhr.status === 419) {
              alert("CSRF token mismatch. Please refresh the page (Ctrl + F5) and try again.");
            } else {
              alert("Failed to update user. Check console for details.");
            }
          }
        });
      });

      // Initial DataTable Load
      loadUsers();
    });
  </script>
@endsection