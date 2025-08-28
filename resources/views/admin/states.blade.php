@extends('admin.layout')
@section('title', 'States Settings')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
  <div class="container py-5 px-5 bg-white">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="pb-1">States</h5>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#statesBackdrop">Add States</button>
    </div>
    <hr class="p-0 my-3">

    <div class="table-responsive">
      <table id="statesTable" class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>States Name</th>
            <th>Country Id</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="statesBackdrop" data-backdrop="static" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="add-states-form" class="small">
          @csrf
          <h5>Add states</h5>
          <div class="" id="msg"></div>
          <hr>
          <div class="form-group">
            <label for="state_name">states Name</label>
            <input type="text" name="state_name" id="state_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="country_id">Country Id</label>
            <input type="number" name="country_id" id="country_id" class="form-control">
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

<!-- Edit Modal -->
<div class="modal fade" id="editstatesModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="edit-states-form" class="small">
          @csrf
          @method('PUT')
          <h5>Edit states</h5>
          <input type="hidden" id="edit_id">
          <hr>
          <div class="form-group">
            <label for="edit_state_name">states Name</label>
            <input type="text" id="edit_state_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_country_id">Country Id</label>
            <input type="number" id="edit_country_id" class="form-control">
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

  // Load states Categories Table
  function loadstates() {
    $('#statesTable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      ajax: {
        url: '{{ route("admin.states.list") }}',
        type: 'GET'
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'country_id', name: 'country_id' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ]
    });
  }

  // Submit Add states Form
  $('#add-states-form').on('submit', function (e) {
    e.preventDefault();

    const state_name = $('#state_name').val().trim();
    const country_id = $('#country_id').val().trim();

    if (!state_name || !country_id) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: "{{ url('/admin/add-states') }}",
      type: "POST",
      data: {
        _token: '{{ csrf_token() }}',
        state_name,
        country_id
      },
      success: function (response) {
        if (response.success) {
          $('#add-states-form')[0].reset();
          jQuery('#statesBackdrop').modal('hide');
          loadstates();
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
  $(document).on('click', '.editBtn', function () {
    const id = $(this).data("id");

    $.get('/admin/edit-states/' + id, function (data) {
      $('#edit_id').val(data.id);
      $('#edit_state_name').val(data.name);
      $('#edit_country_id').val(data.country_id);
      jQuery('#editstatesModal').modal('show');
    });
  });

  // Submit Edit states Form
  $('#edit-states-form').on('submit', function (e) {
    e.preventDefault();

    const id = $('#edit_id').val();
    const state_name = $('#edit_state_name').val().trim();
    const country_id = $('#edit_country_id').val().trim();

    if (!state_name || !country_id) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: '/admin/update-states/' + id,
      method: 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        state_name,
        country_id
      },
      success: function (res) {
        if (res.success) {
          jQuery('#editstatesModal').modal('hide');
          $('#edit-states-form')[0].reset();
          loadstates();
          showSuccessToast(res.message);
        } else {
          showWarningToast(res.message);
        }
      },
      error: function () {
        showDangerToast("Failed to update states. Please try again.");
      }
    });
  });

  // Delete states
  $(document).on('click', '.delBtn', function () {
    const id = $(this).data("id");

    if (confirm('Are you sure you want to delete this states?')) {
      $.ajax({
        url: '/admin/delete-states/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function (res) {
          if (res.success) {
            loadstates();
            showSuccessToast(res.message);
          } else {
            showWarningToast(res.message);
          }
        },
        error: function () {
          showDangerToast("Failed to delete states. Please try again.");
        }
      });
    }
  });

  // Initial DataTable Load
  loadstates();
});
</script>

@endsection