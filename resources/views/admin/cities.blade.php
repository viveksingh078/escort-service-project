@extends('admin.layout')
@section('title', 'Cities Settings')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
  <div class="container py-5 px-5 bg-white">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="pb-1">Cities</h5>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#citiesBackdrop">Add Cities</button>
    </div>
    <hr class="p-0 my-3">

    <div class="table-responsive">
      <table id="citiescitiesTable" class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Cities Name</th>
            <th>State Id</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="citiesBackdrop" data-backdrop="static" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="add-cities-form" class="small">
          @csrf
          <h5>Add cities</h5>
          <div class="" id="msg"></div>
          <hr>
          <div class="form-group">
            <label for="cities_name">cities Name</label>
            <input type="text" name="cities_name" id="cities_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="state_id">State Id</label>
            <input type="number" name="state_id" id="state_id" class="form-control">
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
<div class="modal fade" id="editcitiesModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="edit-cities-form" class="small">
          @csrf
          @method('PUT')
          <h5>Edit cities</h5>
          <input type="hidden" id="edit_id">
          <hr>
          <div class="form-group">
            <label for="edit_cities_name">cities Name</label>
            <input type="text" id="edit_cities_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_state_id">State Id</label>
            <input type="number" id="edit_state_id" class="form-control">
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

  // Load cities Categories Table
  function loadcitiesCategories() {
    $('#citiescitiesTable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      ajax: {
        url: '{{ route("admin.cities.list") }}',
        type: 'GET'
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'state_id', name: 'state_id' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ]
    });
  }

  // Submit Add cities Form
  $('#add-cities-form').on('submit', function (e) {
    e.preventDefault();

    const cities_name = $('#cities_name').val().trim();
    const state_id = $('#state_id').val().trim();

    if (!cities_name || !state_id) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: "{{ url('/admin/add-cities') }}",
      type: "POST",
      data: {
        _token: '{{ csrf_token() }}',
        cities_name,
        state_id
      },
      success: function (response) {
        if (response.success) {
          $('#add-cities-form')[0].reset();
          jQuery('#citiesBackdrop').modal('hide');
          loadcitiesCategories();
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

    $.get('/admin/edit-cities/' + id, function (data) {
      $('#edit_id').val(data.id);
      $('#edit_cities_name').val(data.name);
      $('#edit_state_id').val(data.state_id);
      jQuery('#editcitiesModal').modal('show');
    });
  });

  // Submit Edit cities Form
  $('#edit-cities-form').on('submit', function (e) {
    e.preventDefault();

    const id = $('#edit_id').val();
    const cities_name = $('#edit_cities_name').val().trim();
    const state_id = $('#edit_state_id').val().trim();

    if (!cities_name || !state_id) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: '/admin/update-cities/' + id,
      method: 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        cities_name,
        state_id
      },
      success: function (res) {
        if (res.success) {
          jQuery('#editcitiesModal').modal('hide');
          $('#edit-cities-form')[0].reset();
          loadcitiesCategories();
          showSuccessToast(res.message);
        } else {
          showWarningToast(res.message);
        }
      },
      error: function () {
        showDangerToast("Failed to update cities. Please try again.");
      }
    });
  });

  // Delete cities
  $(document).on('click', '.delBtn', function () {
    const id = $(this).data("id");

    if (confirm('Are you sure you want to delete this cities?')) {
      $.ajax({
        url: '/admin/delete-cities/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function (res) {
          if (res.success) {
            loadcitiesCategories();
            showSuccessToast(res.message);
          } else {
            showWarningToast(res.message);
          }
        },
        error: function () {
          showDangerToast("Failed to delete cities. Please try again.");
        }
      });
    }
  });

  // Initial DataTable Load
  loadcitiesCategories();
});
</script>

@endsection