@extends('admin.layout')
@section('title', 'Escort Category')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
  <div class="container py-5 px-5 bg-white">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="pb-1">Category</h5>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#categoryBackdrop">Add Category</button>
    </div>
    <hr class="p-0 my-3">

    <div class="table-responsive">
      <table id="escortCategoryTable" class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Category Slug</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="categoryBackdrop" data-backdrop="static" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="add-category-form" class="small">
          @csrf
          <h5>Add Category</h5>
          <div class="" id="msg"></div>
          <hr>
          <div class="form-group">
            <label for="category_name">Category Name</label>
            <input type="text" name="category_name" id="category_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="category_slug">Category Slug</label>
            <input type="text" name="category_slug" id="category_slug" class="form-control" readonly>
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
<div class="modal fade" id="editCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-body">
        <form id="edit-category-form" class="small">
          @csrf
          @method('PUT')
          <h5>Edit Category</h5>
          <input type="hidden" id="edit_id">
          <hr>
          <div class="form-group">
            <label for="edit_category_name">Category Name</label>
            <input type="text" id="edit_category_name" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_category_slug">Category Slug</label>
            <input type="text" id="edit_category_slug" class="form-control" readonly>
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

  // Auto-generate slug
  function generateSlug(text) {
    return text.toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '_')
      .replace(/_+/g, '_');
  }

  $('#category_name').on('input', function () {
    $('#category_slug').val(generateSlug($(this).val()));
  });

  $('#edit_category_name').on('input', function () {
    $('#edit_category_slug').val(generateSlug($(this).val()));
  });

  // Load Escort Categories Table
  function loadEscortCategories() {
    $('#escortCategoryTable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      ajax: {
        url: '{{ route("admin.escort.categories.list") }}',
        type: 'GET'
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'slug', name: 'slug' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ]
    });
  }

  // Submit Add Category Form
  $('#add-category-form').on('submit', function (e) {
    e.preventDefault();

    const category_name = $('#category_name').val().trim();
    const category_slug = $('#category_slug').val().trim();

    if (!category_name || !category_slug) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: "{{ url('/admin/add-escort-category') }}",
      type: "POST",
      data: {
        _token: '{{ csrf_token() }}',
        category_name,
        category_slug
      },
      success: function (response) {
        if (response.success) {
          $('#add-category-form')[0].reset();
          jQuery('#categoryBackdrop').modal('hide');
          loadEscortCategories();
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

    $.get('/admin/edit-escort-category/' + id, function (data) {
      $('#edit_id').val(data.id);
      $('#edit_category_name').val(data.name);
      $('#edit_category_slug').val(data.slug);
      jQuery('#editCategoryModal').modal('show');
    });
  });

  // Submit Edit Category Form
  $('#edit-category-form').on('submit', function (e) {
    e.preventDefault();

    const id = $('#edit_id').val();
    const category_name = $('#edit_category_name').val().trim();
    const category_slug = $('#edit_category_slug').val().trim();

    if (!category_name || !category_slug) {
      showWarningToast("All fields are required.");
      return;
    }

    $.ajax({
      url: '/admin/update-escort-category/' + id,
      method: 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        category_name,
        category_slug
      },
      success: function (res) {
        if (res.success) {
          jQuery('#editCategoryModal').modal('hide');
          $('#edit-category-form')[0].reset();
          loadEscortCategories();
          showSuccessToast(res.message);
        } else {
          showWarningToast(res.message);
        }
      },
      error: function () {
        showDangerToast("Failed to update category. Please try again.");
      }
    });
  });

  // Delete Category
  $(document).on('click', '.delBtn', function () {
    const id = $(this).data("id");

    if (confirm('Are you sure you want to delete this category?')) {
      $.ajax({
        url: '/admin/delete-escort-category/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function (res) {
          if (res.success) {
            loadEscortCategories();
            showSuccessToast(res.message);
          } else {
            showWarningToast(res.message);
          }
        },
        error: function () {
          showDangerToast("Failed to delete category. Please try again.");
        }
      });
    }
  });

  // Initial DataTable Load
  loadEscortCategories();
});
</script>

@endsection