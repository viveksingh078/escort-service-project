@extends('admin.layout')
@section('title', 'Escort Manage')
@section('content')
  <!-- Ensure CSRF token is available -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="pb-1">Escort Manage</h5>
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#escortBackdrop">Add
          Escort</button>
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

  <!-- Add Modal -->
  <div class="modal fade" id="escortBackdrop" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-body">
          <form id="add-escort-form" class="small">
            @csrf
            <h5>Add Escort</h5>
            <div class="" id="msg"></div>
            <hr>
            <div class="form-group">
              <label for="escort_name">Escort Name</label>
              <input type="text" name="name" id="escort_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="escort_email">Email</label>
              <input type="email" name="email" id="escort_email" class="form-control">
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
  <div class="modal fade" id="viewEscortModal" tabindex="-1" aria-labelledby="viewEscortModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title" id="viewEscortModalLabel">View Escort</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Escort Name</label>
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
  <div class="modal fade" id="editEscortModal" tabindex="-1" aria-labelledby="editEscortModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title" id="editEscortModalLabel">Edit Escort</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="edit-escort-form" class="small">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
              <label for="edit_name">Escort Name</label>
              <input type="text" id="edit_name" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_email">Email</label>
              <input type="email" id="edit_email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="edit_profile_picture">Profile Picture</label>
              <input type="file" id="edit_profile_picture" name="profile_picture" class="form-control">
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

      // Load Escort Table
      function loadEscorts() {
        $('#escortTable').DataTable({
          processing: true,
          serverSide: true,
          destroy: true,
          ajax: {
            url: '{{ route("admin.escorts.index") }}',
            type: 'GET'
          },
          columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
              data: 'profile_picture',
              name: 'profile_picture',
              render: function (data, type, row) {
                return data ?
                  `<img src="{{ asset('storage/') }}/${data}" alt="${row.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">` :
                  `<img src="{{ asset('images/default-profile.jpg') }}" alt="Default Profile" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">`;
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
                                                  <button class="btn btn-sm btn-info viewEscortBtn" data-id="${row.id}">View</button>
                                                  <button class="btn btn-sm btn-warning editEscortBtn" data-id="${row.id}">Edit</button>
                                                  <button class="btn btn-sm btn-danger delEscortBtn" data-id="${row.id}">Delete</button>
                                              `;
              }
            }
          ]
        });
      }

      // Submit Add Escort Form
      $('#add-escort-form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('role', 'escort');

        $.ajax({
          url: "{{ route('admin.escorts.store') }}",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              $('#add-escort-form')[0].reset();
              jQuery('#escortBackdrop').modal('hide');
              loadEscorts();
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

      // Delete Escort
      $(document).on('click', '.delEscortBtn', function () {
        const id = $(this).data("id");

        if (confirm('Are you sure you want to delete this escort?')) {
          $.ajax({
            url: '{{ route("admin.escorts.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
              if (res.success) {
                loadEscorts();
                alert(res.message);
              } else {
                alert(res.message);
              }
            },
            error: function () {
              alert("Failed to delete escort. Please try again.");
            }
          });
        }
      });

      // View Escort
      $(document).on('click', '.viewEscortBtn', function () {
        const id = $(this).data("id");
        $.ajax({
          url: '{{ route("admin.escorts.view", ":id") }}'.replace(':id', id),
          type: 'GET',
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function (response) {
            if (response.success) {
              $('#view_name').text(response.data.name);
              $('#view_email').text(response.data.email);
              let profilePicture = response.data.profile_picture ?
                '{{ asset("storage") }}/' + response.data.profile_picture :
                '{{ asset("images/default-profile.jpg") }}';
              $('#view_profile_picture').attr('src', profilePicture);
              jQuery('#viewEscortModal').modal('show');
            } else {
              alert(response.message);
            }
          },
          error: function () {
            alert("Failed to load escort details. Please try again.");
          }
        });
      });

      // Edit Escort
      $(document).on('click', '.editEscortBtn', function () {
        const id = $(this).data("id");
        $.ajax({
          url: '{{ route("admin.escorts.edit", ":id") }}'.replace(':id', id),
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
              jQuery('#editEscortModal').modal('show');
              console.log('Edit Data Loaded:', response.data);
            } else {
              alert(response.message);
            }
          },
          error: function () {
            alert("Failed to load escort for editing. Please try again.");
          }
        });
      });

      // Submit Edit Escort Form
      $('#edit-escort-form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData($('#edit-escort-form')[0]); // Direct form se data
        formData.append('_method', 'PUT'); // For Laravel PUT

        console.log('Before Submit - Form Data:', {
          id: $('#edit_id').val(),
          name: $('#edit_name').val(),
          email: $('#edit_email').val(),
          profile_picture: $('#edit_profile_picture')[0].files[0] ? 'File present' : 'No file'
        });

        $.ajax({
          url: '{{ route("admin.escorts.update", ":id") }}'.replace(':id', $('#edit_id').val()),
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              jQuery('#editEscortModal').modal('hide');
              loadEscorts();
              alert(response.message);
            } else {
              alert(response.message);
            }
          },
          error: function (xhr) {
            console.log('Error Response:', xhr.responseJSON);
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
              alert("Failed to update escort. Check console for details.");
            }
          }
        });
      });

      // Initial DataTable Load
      loadEscorts();
    });
  </script>
@endsection