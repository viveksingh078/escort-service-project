@extends('admin.layout')
@section('title', 'Escort Manage')
@section('content')
<<<<<<< HEAD
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
=======
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Escort Manage</h5>
                <a href="{{ route('admin.escort.create') }}" class="btn btn-sm btn-primary">Add Escort</a>
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
    <div class="modal fade" id="viewEscortModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">View Escort</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view-modal-body">

                </div>
            </div>
        </div>
    </div>

    {{-- Edit Escort Modal --}}
    <div class="modal fade" id="editEscortModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Escort</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit-modal-body">



                    <div class="container">
                        <div class="row">
                            <div class="col-12 mx-auto">
                                <div class="escort-register-inner shadow-sm p-4">
                                    <h5 class="h3">Personal Details</h5>
                                    <p class="small">
                                        You must use your full, real name exactly as displayed on your passport, driving
                                        license or government
                                        issued Identity Card. This is used for admin purposes only and is not shown
                                        publicly.
                                    </p>
                                    <hr class="p-0 my-2">
                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="escId" id="escId" value="">
                                        <div class="row mt-3">
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="display_name" class="form-label fw-bold small">Display
                                                    Name</label>
                                                <input type="text" class="form-control rounded-pill text-capitalize"
                                                    name="display_name" id="display_name" value="" readonly="">
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="username" class="form-label fw-bold small">Username</label>
                                                <input type="text" class="form-control rounded-pill" name="username"
                                                    id="username" value="" readonly="">
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="user_email" class="form-label fw-bold small">User Email</label>
                                                <input type="text" class="form-control rounded-pill" name="user_email"
                                                    id="user_email" value="" readonly="">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="first_name" class="form-label fw-bold small">First Name</label>
                                                <input type="text"
                                                    class="form-control custom-input @error('first_name') is-invalid @enderror"
                                                    name="first_name" id="first_name" value="{{ old('first_name') }}"
                                                    placeholder="Enter your first name">
                                                @error('first_name')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="last_name" class="form-label fw-bold small">Last Name</label>
                                                <input type="text"
                                                    class="form-control custom-input @error('last_name') is-invalid @enderror"
                                                    name="last_name" id="last_name" value="{{ old('last_name') }}"
                                                    placeholder="Enter your last name">
                                                @error('last_name')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="dob" class="form-label fw-bold small">Date of Birth</label>
                                                <input type="date"
                                                    class="form-control custom-input @error('dob') is-invalid @enderror"
                                                    name="dob" id="dob" value="{{ old('dob') }}">
                                                <input type="hidden" class="form-contro is-invalid" name="age" id="age"
                                                    value="{{ old('age') }}">
                                                @error('dob')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="country_code" class="form-label fw-bold small">Country Code
                                                    (Mobile)</label>
                                                <input type="number"
                                                    class="form-control custom-input @error('country_code') is-invalid @enderror"
                                                    name="country_code" id="country_code" value="{{ old('country_code') }}"
                                                    placeholder="Country code">
                                                @error('country_code')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="phone_number" class="form-label fw-bold small">Phone
                                                    Number</label>
                                                <input type="text"
                                                    class="form-control custom-input @error('phone_number') is-invalid @enderror"
                                                    name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                                    placeholder="Enter your phone number">
                                                @error('phone_number')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="whatsapp_number" class="form-label fw-bold small">Whatsapp
                                                    Number</label>
                                                <input type="text"
                                                    class="form-control custom-input @error('whatsapp_number') is-invalid @enderror"
                                                    name="whatsapp_number" id="whatsapp_number"
                                                    value="{{ old('whatsapp_number') }}"
                                                    placeholder="Enter your whatsapp number">
                                                @error('whatsapp_number')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">

                                                <label for="profile_picture" class="form-label fw-bold small">Profile
                                                    Picture</label>
                                                <input type="file"
                                                    class="form-control custom-input @error('profile_picture') is-invalid @enderror"
                                                    name="profile_picture" id="profile_picture" accept="image/*">
                                                @error('profile_picture')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                                <!-- ✅ Preview -->
                                                <img id="profile_picture_preview" src="" alt="Profile Picture Preview"
                                                    style="max-width:120px; margin-top:10px; display:none; border-radius:10px;">
                                            </div>


                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">

                                                <label for="profile_banner" class="form-label fw-bold small">Profile
                                                    Banner</label>
                                                <input type="file"
                                                    class="form-control custom-input @error('profile_banner') is-invalid @enderror"
                                                    name="profile_banner" id="profile_banner" accept="image/*">
                                                @error('profile_banner')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                                <!-- ✅ Preview -->
                                                <img id="profile_banner_preview" src="" alt="Profile Banner Preview"
                                                    style="max-width:120px; margin-top:10px; display:none; border-radius:10px;">
                                            </div>


                                            <div class="col-sm-12 col-md-12 mb-4">
                                                <label class="form-label fw-bold small">Profile Category</label>
                                                <div class="d-flex gap-2 flex-wrap" id="profile_category_group">
                                                    @foreach($categories as $index => $category)
                                                        <label class="btn btn-outline-dark category-label mb-2 mx-1 btn-sm"
                                                            for="category_id_{{ $category->id }}">
                                                            <input type="radio" id="category_id_{{ $category->id }}"
                                                                name="category_id" value="{{ $category->id }}"
                                                                class="category-radio d-none" {{ old('category_id', $index === 0 ? $category->id : '') == $category->id ? 'checked' : '' }}>
                                                            {{ $category->name }}
                                                        </label>
                                                    @endforeach
                                                    @error('category_id')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <!-- !!!!!!! About profile !!!!!!! -->
                                        <h5 class="mt-4 h3">About profile</h5>
                                        <p class="small">All these details what you add here are going to related to your
                                            public profile.</p>
                                        <hr class="p-0 my-2">
                                        <div class="row mt-3">
                                            <div class="col-sm-12 col-lg-12 col-md-12 mb-4">
                                                <label for="about_me" class="form-label fw-bold small">About Me</label>
                                                <textarea
                                                    class="form-control custom-textarea @error('about_me') is-invalid @enderror"
                                                    id="about_me" name="about_me" rows="3">{{ old('about_me') }}</textarea>

                                                @error('about_me')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>


                                        </div>

                                        <!-- !!!!!!! Physical Details !!!!!!! -->
                                        <h5 class="mt-4 h3">Physical Details</h5>
                                        <p class="small">Add your physical details. These will appear on your public
                                            profile.</p>
                                        <hr class="p-0 my-2">
                                        <div class="row mt-3">
                                            <!-- Height -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="height" class="form-label fw-bold small">Height</label>
                                                <select id="height" name="height"
                                                    class="form-select form-control custom-input @error('height') is-invalid @enderror">
                                                    <option value="">Select Height</option>
                                                    @for($i = 140; $i <= 210; $i++)
                                                        <option value="{{ $i }}" {{ old('height') == $i ? 'selected' : '' }}>
                                                            {{ $i }} cm
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('height')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Dress -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="dress" class="form-label fw-bold small">Dress</label>
                                                <select id="dress" name="dress"
                                                    class="form-select form-control custom-input @error('dress') is-invalid @enderror">
                                                    <option value="">Select Dress Size</option>
                                                    @for($i = 1; $i <= 20; $i++)
                                                        <option value="{{ $i }}" {{ old('dress') == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('dress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Weight -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="weight" class="form-label fw-bold small">Weight</label>
                                                <select id="weight" name="weight"
                                                    class="form-select form-control custom-input @error('weight') is-invalid @enderror">
                                                    <option value="">Select Weight</option>
                                                    @for($i = 40; $i <= 120; $i++)
                                                        <option value="{{ $i }}" {{ old('weight') == $i ? 'selected' : '' }}>
                                                            {{ $i }} kg
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('weight')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Bust -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="bust" class="form-label fw-bold small">Bust</label>
                                                <select id="bust" name="bust"
                                                    class="form-select form-control custom-input @error('bust') is-invalid @enderror">
                                                    <option value="">Select Bust Size</option>
                                                    @for($i = 70; $i <= 120; $i++)
                                                        <option value="{{ $i }}" {{ old('bust') == $i ? 'selected' : '' }}>
                                                            {{ $i }} cm
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('bust')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Waist -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="waist" class="form-label fw-bold small">Waist</label>
                                                <select id="waist" name="waist"
                                                    class="form-select form-control custom-input @error('waist') is-invalid @enderror">
                                                    <option value="">Select Waist Size</option>
                                                    @for($i = 40; $i <= 100; $i++)
                                                        <option value="{{ $i }}" {{ old('waist') == $i ? 'selected' : '' }}>
                                                            {{ $i }} cm
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('waist')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Eyes -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="eyes" class="form-label fw-bold small">Eyes</label>
                                                <select id="eyes" name="eyes"
                                                    class="form-select form-control custom-input @error('eyes') is-invalid @enderror">
                                                    <option value="">Select Eye Color</option>
                                                    <option value="gray" {{ old('eyes') == 'gray' ? 'selected' : '' }}>Gray
                                                    </option>
                                                    <option value="blue" {{ old('eyes') == 'blue' ? 'selected' : '' }}>Blue
                                                    </option>
                                                    <option value="green" {{ old('eyes') == 'green' ? 'selected' : '' }}>Green
                                                    </option>
                                                    <option value="hazel" {{ old('eyes') == 'hazel' ? 'selected' : '' }}>Hazel
                                                    </option>
                                                    <option value="brown" {{ old('eyes') == 'brown' ? 'selected' : '' }}>Brown
                                                    </option>
                                                    <option value="black" {{ old('eyes') == 'black' ? 'selected' : '' }}>Black
                                                    </option>
                                                </select>
                                                @error('eyes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Hair -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="hair" class="form-label fw-bold small">Hair</label>
                                                <select id="hair" name="hair"
                                                    class="form-select form-control custom-input @error('hair') is-invalid @enderror">
                                                    <option value="">Select Hair Color</option>
                                                    <option value="blond" {{ old('hair') == 'blond' ? 'selected' : '' }}>Blond
                                                    </option>
                                                    <option value="black" {{ old('hair') == 'black' ? 'selected' : '' }}>Black
                                                    </option>
                                                    <option value="brown" {{ old('hair') == 'brown' ? 'selected' : '' }}>Brown
                                                    </option>
                                                    <option value="red" {{ old('hair') == 'red' ? 'selected' : '' }}>Red
                                                    </option>
                                                    <option value="auburn" {{ old('hair') == 'auburn' ? 'selected' : '' }}>
                                                        Auburn</option>
                                                    <option value="gray" {{ old('hair') == 'gray' ? 'selected' : '' }}>Gray
                                                    </option>
                                                    <option value="other" {{ old('hair') == 'other' ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                                @error('hair')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Hips -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="hips" class="form-label fw-bold small">Hips</label>
                                                <select id="hips" name="hips"
                                                    class="form-select form-control custom-input @error('hips') is-invalid @enderror">
                                                    <option value="">Select Hip Size</option>
                                                    @for($i = 70; $i <= 120; $i++)
                                                        <option value="{{ $i }}" {{ old('hips') == $i ? 'selected' : '' }}>
                                                            {{ $i }} cm
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('hips')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Shoe -->
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="shoe" class="form-label fw-bold small">Shoe</label>
                                                <select id="shoe" name="shoe"
                                                    class="form-select form-control custom-input @error('shoe') is-invalid @enderror">
                                                    <option value="">Select Shoe Size</option>
                                                    @for($i = 4; $i <= 12; $i += 0.5)
                                                        <option value="{{ $i }}" {{ old('shoe') == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('shoe')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                        <!-- !!!!!!! Social Profiles !!!!!!! -->
                                        <h5 class="mt-4 h3">Social Profiles</h5>
                                        <p class="small">Connect your social media accounts to make your profile more
                                            engaging and trustworthy. Visitors will be able to see your social links on your
                                            public profile.</p>
                                        <hr class="p-0 my-2">
                                        <div class="row mt-3">
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="facebook_url" class="form-label fw-bold small">
                                                    <i class="fab fa-facebook text-primary"></i> Facebook
                                                </label>
                                                <input type="url"
                                                    class="form-control custom-input @error('facebook_url') is-invalid @enderror"
                                                    name="facebook_url" id="facebook_url" value="{{ old('facebook_url') }}"
                                                    placeholder="Enter your Facebook URL">
                                                @error('facebook_url')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="twitter_url" class="form-label fw-bold small">
                                                    <i class="fab fa-twitter text-info"></i> Twitter
                                                </label>
                                                <input type="url"
                                                    class="form-control custom-input @error('twitter_url') is-invalid @enderror"
                                                    name="twitter_url" id="twitter_url" value="{{ old('twitter_url') }}"
                                                    placeholder="Enter your Twitter URL">
                                                @error('twitter_url')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="linkedin_url" class="form-label fw-bold small">
                                                    <i class="fab fa-linkedin text-primary"></i> LinkedIn
                                                </label>
                                                <input type="url"
                                                    class="form-control custom-input @error('linkedin_url') is-invalid @enderror"
                                                    name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url') }}"
                                                    placeholder="Enter your LinkedIn URL">
                                                @error('linkedin_url')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                                                <label for="youtube_url" class="form-label fw-bold small">
                                                    <i class="fab fa-youtube text-danger"></i> YouTube
                                                </label>
                                                <input type="url"
                                                    class="form-control custom-input @error('youtube_url') is-invalid @enderror"
                                                    name="youtube_url" id="youtube_url" value="{{ old('youtube_url') }}"
                                                    placeholder="Enter your YouTube URL">
                                                @error('youtube_url')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <h5 class="mt-4 h3">Address Details</h5>
                                        <p class="small">Please select your country, state, and city. This will help in
                                            localizing your profile.</p>
                                        <hr class="p-0 my-2">
                                        <div class="row mt-3">

                                            <!-- Country -->
                                            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                                                <label for="country" class="form-label fw-bold small">Country</label>
                                                <select id="country" name="country"
                                                    class="form-select custom-input form-control @error('country') is-invalid @enderror">
                                                    <option value="">Loading countries...</option>
                                                </select>
                                                @error('country')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- State -->
                                            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                                                <label for="state" class="form-label fw-bold small">State</label>
                                                <select id="state" name="state"
                                                    class="form-select custom-input form-control @error('state') is-invalid @enderror">
                                                    <option value="">Select Country First</option>
                                                </select>
                                                @error('state')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- City -->
                                            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                                                <label for="city" class="form-label fw-bold small">City</label>
                                                <select id="city" name="city"
                                                    class="form-select custom-input form-control @error('city') is-invalid @enderror">
                                                    <option value="">Select State First</option>
                                                </select>
                                                @error('city')
                                                    <p class="invalid-feedback">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>
                                        <!--!!!!!!!  Payment Settings !!!!!!! -->
                                        <h5 class="mt-4 h3">Payment Settings</h5>
                                        <p class="small">In this section you need to add your monthly subscription.</p>
                                        <hr class="p-0 my-2">
                                        <div class="row mt-3">
                                            @php
                                                $price = old('subscription_price', 50); // Default value 50 if nothing submitted before
                                              @endphp
                                            <div class="col-sm-12 col-lg-12 col-md-12 mb-5">
                                                <label for="subscription_price" class="fw-bold small">
                                                    Subscription Prices
                                                    <span type="button" class="info_icon info_icon3"></span>
                                                </label>
                                                <div class="range-slider">
                                                    <input class="range-slider__range" type="range" id="subscription_price"
                                                        name="subscription_price" value="{{ $price }}" min="0" max="100">
                                                    <span class="range-slider__value">{{ $price }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-lg-12 col-md-12 mb-4">
                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input custom-input-check shadow-none @error('terms') is-invalid @enderror"
                                                        type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                                                    <label class="form-check-label mx-0" for="terms">
                                                        I confirm that I am registering on the platform in order to generate
                                                        income on an ongoing basis and
                                                        will be liable and responsible for my own tax reporting obligations.
                                                    </label>
                                                    @error('terms')
                                                        <p class="text-danger small">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input custom-input-check shadow-none @error('digital_services_only') is-invalid @enderror"
                                                        type="checkbox" id="digital_services_only"
                                                        name="digital_services_only" value="1" {{ old('digital_services_only') ? 'checked' : '' }}>
                                                    <label class="form-check-label mx-0" for="digital_services_only">
                                                        I confirm that I will only use the service to receive funds for
                                                        digital services and I am aware that
                                                        any attempt to use the service for physical services or in-person
                                                        escort bookings will cause immediate
                                                        account termination and the instant refund of ALL client funds held
                                                        by us.
                                                    </label>
                                                    @error('digital_services_only')
                                                        <p class="text-danger small">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input custom-input-check shadow-none @error('promote_profile') is-invalid @enderror"
                                                        type="checkbox" id="promote_profile" name="promote_profile"
                                                        value="1" {{ old('promote_profile') ? 'checked' : '' }}>
                                                    <label class="form-check-label mx-0" for="promote_profile">
                                                        Yes, I want my profile to be promoted
                                                    </label>
                                                    @error('promote_profile')
                                                        <p class="text-danger small">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div>
                                                <button class="btn custom-btn" type="submit" id="updateBtn">
                                                    Submit
                                                    <i class="fa-solid fa-chevron-right custom-btn-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    ajax: '{{ route("admin.escort.list") }}',
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
                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                        url: '{{ route("admin.escorts.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        success: function (res) { if (res.success) { loadEscorts(); } }
                    });
                }
            });

            $(document).on('click', '.viewEscortBtn', function () {
                let id = $(this).data('id');

                $.get("{{ route('admin.escorts.view', ':id') }}".replace(':id', id), function (res) {
                    if (res.success) {
                        let d = res.data;
                        let photos = res.photos || [];
                        let videos = res.videos || [];

                        // Format height if it exists
                        let formattedHeight = '';
                        if (d.height) {
                            // If height is in centimeters (numeric and > 100)
                            if (!isNaN(d.height) && d.height > 100) {
                                let feet = Math.floor(d.height / 30.48);
                                let inches = Math.round((d.height / 2.54) - (feet * 12));
                                formattedHeight = feet + ',' + inches;
                            } else {
                                // If already in feet.inches format
                                let parts = d.height.toString().split('.');
                                formattedHeight = parts[0] + ',' + (parts[1] || '0');
                            }
                        }

                        // Build social links HTML
                        let socialLinksHtml = `
                                                <a href="${d.facebook_url || '#'}" class="social-link btn btn-sm">
                                                    <i class="fab fa-facebook pro-social-icon"></i>
                                                </a>
                                                <a href="${d.twitter_url || '#'}" class="social-link btn btn-sm">
                                                    <i class="fab fa-twitter pro-social-icon"></i>
                                                </a>
                                                <a href="${d.linkedin_url || '#'}" class="social-link btn btn-sm">
                                                    <i class="fab fa-linkedin pro-social-icon"></i>
                                                </a>
                                                <a href="${d.youtube_url || '#'}" class="social-link btn btn-sm">
                                                    <i class="fab fa-youtube pro-social-icon"></i>
                                                </a>
                                            `;

                        // Build photos HTML
                        let photosHtml = '';
                        let displayedPhotos = photos.slice(0, 7);
                        let hiddenPhotos = photos.slice(7);

                        displayedPhotos.forEach(photo => {
                            // Fix photo path
                            let photoPath = "{{ asset('') }}" + photo.file_path;

                            photosHtml += `
                                                    <div class="col-sm-6 col-md-3 col-lg-3 mb-4">
                                                        <a data-fancybox="gallery" href="${photoPath}" data-caption="${photo.title || ''}">
                                                            <img src="${photoPath}" alt="${photo.title || ''}" class="img-fluid rounded shadow" style="height:140px; object-fit:cover;">
                                                        </a>
                                                    </div>
                                                `;
                        });

                        if (photos.length > 7) {
                            photosHtml += `
                                                    <div class="col-sm-6 col-md-3 col-lg-3 mb-4 d-flex align-items-center justify-content-center">
                                                        <div class="more-box bg-light rounded shadow text-center w-100 h-100 d-flex align-items-center justify-content-center" style="height:140px; cursor:pointer;" id="viewMorePhotos">
                                                            <span class="fw-bold">+ ${photos.length - 7} More</span>
                                                        </div>
                                                    </div>
                                                `;
                        }

                        // Build hidden photos HTML
                        let hiddenPhotosHtml = '';
                        hiddenPhotos.forEach(photo => {
                            // Fix photo path for hidden photos too
                            let photoPath = "{{ asset('') }}" + photo.file_path;

                            hiddenPhotosHtml += `
                                                    <a data-fancybox="gallery" href="${photoPath}" data-caption="${photo.title || ''}" class="d-none">
                                                        <img src="${photoPath}" alt="${photo.title || ''}">
                                                    </a>
                                                `;
                        });

                        // Build videos HTML
                        let videosHtml = '';
                        let displayedVideos = videos.slice(0, 7);
                        let hiddenVideos = videos.slice(7);

                        displayedVideos.forEach(video => {
                            // Fix video and thumbnail paths
                            let videoPath = "{{ asset('') }}" + video.file_path;
                            let thumbnailPath = video.thumbnail_path ? "{{ asset('') }}" + video.thumbnail_path : "{{ asset('default-video-thumbnail.jpg') }}";

                            videosHtml += `
                                                  <div class="col-sm-6 col-md-3 col-lg-3 mb-4 position-relative">
                                                      <a data-fancybox="videos" href="${videoPath}" data-caption="${video.title || ''}" data-type="video">
                                                          <img src="${thumbnailPath}" alt="${video.title || ''}" class="img-fluid rounded shadow" style="height:140px; object-fit:cover;">
                                                          <span class="position-absolute top-50 start-50 translate-middle text-white" style="font-size:35px; pointer-events:none;">
                                                              <i class="fas fa-play-circle text-dark" style="font-size: 35px;"></i>
                                                          </span>
                                                      </a>
                                                  </div>
                                              `;
                        });

                        if (videos.length > 7) {
                            videosHtml += `
                                                  <div class="col-sm-6 col-md-3 col-lg-3 mb-4 d-flex align-items-center justify-content-center">
                                                      <div class="more-box bg-light rounded shadow text-center w-100 h-100 d-flex align-items-center justify-content-center" style="height:140px; cursor:pointer;" id="viewMoreVideos">
                                                          <span class="fw-bold">+ ${videos.length - 7} More</span>
                                                      </div>
                                                  </div>
                                              `;
                        }

                        // Build hidden videos HTML
                        let hiddenVideosHtml = '';
                        hiddenVideos.forEach(video => {
                            // Fix video and thumbnail paths for hidden videos too
                            let videoPath = "{{ asset('') }}" + video.file_path;
                            let thumbnailPath = video.thumbnail_path ? "{{ asset('') }}" + video.thumbnail_path : "{{ asset('default-video-thumbnail.jpg') }}";

                            hiddenVideosHtml += `
                                                  <a data-fancybox="videos" href="${videoPath}" data-caption="${video.title || ''}" data-type="video" class="d-none">
                                                      <img src="${thumbnailPath}" alt="${video.title || ''}">
                                                  </a>
                                              `;
                        });

                        // Fix profile picture path
                        var profile_picture = "{{ asset('storage') }}/" + d.profile_picture;

                        // Build the main HTML template
                        let html = `
                                                <div class="container-fluid m-0 p-0 single-profile-page">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-lg-4 col-md-4">
                                                                <div class="profile-card">
                                                                    <div class="card bg-white border-0 shadow-none">
                                                                        <div class="card-header p-0 m-0">
                                                                            <img src="${profile_picture}" class="profile-card-img card-img" alt="">
                                                                        </div>
                                                                        <div class="card-body shadow-sm small py-5">
                                                                            ${d.age ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Age</h5>
                                                                                    <h5 class="card-title text-uppercase m-0">${d.age}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.first_name ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Name</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.first_name} ${d.last_name || ''}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.Gender ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Gender</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.Gender}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.height ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Height</h5>
                                                                                    <h5 class="card-title text-uppercase m-0">${formattedHeight}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.dress ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Dress</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.dress}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.weight ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Weight</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.weight}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.bust ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Bust</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.bust}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.waist ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Waist</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.waist}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.eyes ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Eyes</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.eyes}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.hair ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Hair</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.hair}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.hips ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Hips</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.hips}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.shoe ? `
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="card-title text-uppercase m-0">Shoe</h5>
                                                                                    <h5 class="card-title text-capitalize m-0">${d.shoe}</h5>
                                                                                </div>
                                                                                <hr class="text-white">
                                                                            ` : ''}

                                                                            ${d.about_me ? `
                                                                                <p class="p-0 m-0 py-4">${d.about_me}</p>
                                                                            ` : ''}

                                                                            <div class="social-links">
                                                                                ${socialLinksHtml}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-lg-8 col-md-8 profile-card-right bg-dark">
                                                                <div class="profile-card-right-inner">
                                                                    <div class="grid-margin stretch-card">
                                                                        <div class="card shadow-none bg-transparent">
                                                                            <div class="card-body">
                                                                                ${d.name ? `
                                                                                    <h4 class="card-title p-0 m-0 text-lowercase text-white">
                                                                                        <i class="fa-solid fa-user text-white mr-2"></i>
                                                                                        ${d.name}
                                                                                    </h4>
                                                                                ` : ''}

                                                                                ${(d.country_code && d.phone_number) ? `
                                                                                    <div class="d-flex my-3 align-items-center">
                                                                                        <i class="fa-solid fa-phone text-white mr-2"></i>
                                                                                        <p class="text-white m-0">+${d.country_code} ${d.phone_number}</p>
                                                                                        <span class="mx-2"></span>
                                                                                        <i class="fa-brands fa-whatsapp text-white mr-2"></i>
                                                                                        <p class="text-white m-0">+${d.country_code} ${d.phone_number}</p>
                                                                                    </div>
                                                                                ` : ''}

                                                                                ${d.email ? `
                                                                                    <div class="d-flex my-3 align-items-center">
                                                                                        <i class="fa-solid fa-envelope text-white mr-2"></i>
                                                                                        <p class="text-white m-0">${d.email}</p>
                                                                                    </div>
                                                                                ` : ''}

                                                                                ${(d.country || d.state || d.city) ? `
                                                                                    <div class="d-flex my-3 align-items-center">
                                                                                        <i class="fa-solid fa-location-dot text-white mr-2"></i>
                                                                                        <p class="text-white m-0">
                                                                                            ${d.country || ''}
                                                                                            ${d.state ? ', ' + d.state : ''}
                                                                                            ${d.city ? ', ' + d.city : ''}
                                                                                        </p>
                                                                                    </div>
                                                                                ` : ''}

                                                                                <hr class="text-white">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    ${photos.length > 0 ? `
                                                                        <div class="grid-margin stretch-card">
                                                                            <div class="card shadow-none bg-transparent">
                                                                                <div class="card-body">
                                                                                    <h4 class="card-title text-white">Photos</h4>
                                                                                    <hr class="text-white">
                                                                                    <div class="row">
                                                                                        ${photosHtml}
                                                                                    </div>
                                                                                    ${hiddenPhotosHtml}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ` : ''}

                                                                    ${videos.length > 0 ? `
                                                                        <div class="grid-margin stretch-card">
                                                                            <div class="card bg-transparent shadow-none">
                                                                                <div class="card-body">
                                                                                    <h4 class="card-title text-white">Videos</h4>
                                                                                    <hr class="text-white">
                                                                                    <div class="row">
                                                                                        ${videosHtml}
                                                                                    </div>
                                                                                    ${hiddenVideosHtml}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ` : ''}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;

                        $('#view-modal-body').html(html);

                        // Add event listeners for "view more" buttons
                        $('#viewMorePhotos').on('click', function () {
                            $('a[data-fancybox="gallery"].d-none').first().trigger('click');
                        });

                        $('#viewMoreVideos').on('click', function () {
                            $('a[data-fancybox="videos"].d-none').first().trigger('click');
                        });

                        jQuery('#viewEscortModal').modal('show');
                    }
                });
            });

            // Edit Escort
            $(document).on('click', '.editEscortBtn', function () {
                let id = $(this).data('id');
                $('#escId').val(id);
                $.get('{{ route("admin.escorts.edit", ":id") }}'.replace(':id', id), function (res) {
                    if (res.success) {
                        let d = res.data;
                        console.log(res);

                        // Basic Info
                        $('#username').val(d.username);
                        $('#display_name').val(d.display_name);
                        $('#user_email').val(d.email);
                        $('#first_name').val(d.first_name);
                        $('#last_name').val(d.last_name);
                        $('#dob').val(d.dob);
                        $("#country_code").val(d.country_code);
                        $('#phone_number').val(d.phone_number);
                        $('#whatsapp_number').val(d.whatsapp_number);
                        $('#about_me').val(d.about_me);
                        $('#facebook_url').val(d.facebook_url);
                        $('#linkedin_url').val(d.linkedin_url);
                        $('#twitter_url').val(d.twitter_url);
                        $('#youtube_url').val(d.youtube_url);

                        // Physical Details
                        if (d.height) $('#height').val(d.height).trigger('change');
                        if (d.weight) $('#weight').val(d.weight).trigger('change');
                        if (d.dress) $('#dress').val(d.dress).trigger('change');
                        if (d.bust) $('#bust').val(d.bust).trigger('change');
                        if (d.waist) $('#waist').val(d.waist).trigger('change');
                        if (d.hips) $('#hips').val(d.hips).trigger('change');
                        if (d.shoe) $('#shoe').val(d.shoe).trigger('change');
                        if (d.eyes) $('#eyes').val(d.eyes).trigger('change');
                        if (d.hair) $('#hair').val(d.hair).trigger('change');

                        // Category_id fix: Radio check aur active class
                        if (d.category_id) {
                            $('.category-label').removeClass('active');
                            const radio = $('#category_id_' + d.category_id);
                            if (radio.length) {
                                radio.prop('checked', true);
                                radio.closest('label').addClass('active');
                            }
                        }

                        // Address Details (Country -> State -> City)
                        if (d.country) {
                            $('#country').val(d.country).trigger('change');
                            $.getJSON("/get-states/" + d.country, function (states) {
                                let options = '<option value="">Select State</option>';
                                $.each(states, function (key, state) {
                                    options += `<option value="${state.id}">${state.name}</option>`;
                                });
                                $("#state").html(options);
                                if (d.state) {
                                    $('#state').val(d.state).trigger('change');
                                    $.getJSON("/get-cities/" + d.state, function (cities) {
                                        let cityOptions = '<option value="">Select City</option>';
                                        $.each(cities, function (key, city) {
                                            cityOptions += `<option value="${city.id}">${city.name}</option>`;
                                        });
                                        $("#city").html(cityOptions);
                                        if (d.city) {
                                            $('#city').val(d.city).trigger('change');
                                        }
                                    });
                                }
                            });
                        }

                        // Profile Picture Preview
                        if (d.profile_picture && d.profile_picture !== 'images/default-profile.jpg' && d.profile_picture !== undefined && d.profile_picture !== null) {
                            let picUrl = "{{ asset('storage') }}/" + d.profile_picture;
                            $('#profile_picture_preview').attr('src', picUrl).show();
                            $('#profile_picture_preview').on('error', function () {
                                $(this).attr('src', "{{ asset('images/default-profile.jpg') }}").show();
                            });
                        } else {
                            $('#profile_picture_preview').attr('src', "{{ asset('images/default-profile.jpg') }}").show();
                        }

                        // Profile Banner Preview
                        if (d.profile_banner && d.profile_banner !== 'images/default-banner.jpg' && d.profile_banner !== undefined && d.profile_banner !== null) {
                            let bannerUrl = "{{ asset('storage') }}/" + d.profile_banner;
                            $('#profile_banner_preview').attr('src', bannerUrl).show();
                            $('#profile_banner_preview').on('error', function () {
                                $(this).attr('src', "{{ asset('images/default-banner.jpg') }}").show();
                            });
                        } else {
                            $('#profile_banner_preview').attr('src', "{{ asset('images/default-banner.jpg') }}").show();
                        }

                        jQuery('#editEscortModal').modal('show');
                    }
                });
            });

            // Update Escort (FormData fix kiya)
            $(document).on('click', '#updateBtn', function (e) {
                e.preventDefault();

                let id = $('#escId').val();
                if (!id) {
                    console.log("Error: Escort ID nahi mila!");
                    alert("Escort ID nahi mila, phir se try karo.");
                    return;
                }

                let form = $(this).closest('form')[0];
                let formData = new FormData(form);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: '/admin/escorts/' + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.success) {
                            jQuery('#editEscortModal').modal('hide');
                            loadEscorts();
                            alert(res.message || 'Escort update ho gaya!');
                        } else {
                            alert(res.message || 'Update fail ho gaya.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX Error:", xhr.responseText, error);
                        alert("Update mein error aaya: " + error + ". Console check kar.");
                    }
                });
            });


        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('.category-radio');

            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    // Remove 'active' class from all labels
                    document.querySelectorAll('.category-label').forEach(label => {
                        label.classList.remove('active');
                    });

                    // Add 'active' class to the selected one
                    if (this.checked) {
                        this.closest('label').classList.add('active');
                    }
                });

                // Set default active state if one is pre-checked (e.g., after form validation error)
                if (radio.checked) {
                    radio.closest('label').classList.add('active');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const rangeInput = document.querySelector('.range-slider__range');
            const rangeValue = document.querySelector('.range-slider__value');

            // Initial value sync
            rangeValue.textContent = rangeInput.value;

            // On input change
            rangeInput.addEventListener('input', function () {
                rangeValue.textContent = this.value;
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const dobInput = document.getElementById('dob');
            const ageInput = document.getElementById('age');

            // Set max date to today minus 18 years
            const today = new Date();
            const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            dobInput.max = eighteenYearsAgo.toISOString().split('T')[0];

            dobInput.addEventListener('change', function () {
                const dob = new Date(this.value);
                if (isValidDate(dob)) {
                    const age = calculateAge(dob);
                    ageInput.value = age;
                } else {
                    ageInput.value = '';
                }
            });

            function calculateAge(dob) {
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                return age;
            }

            function isValidDate(d) {
                return d instanceof Date && !isNaN(d);
            }
        });

    </script>

    <script>
        $(document).ready(function () {

            // Profile Picture Preview
            $('#profile_picture').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile_picture_preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#profile_picture_preview').hide();
                }
            });

            // Profile Banner Preview
            $('#profile_banner').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile_banner_preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#profile_banner_preview').hide();
                }
            });
            // Load Countries on page load
            $.getJSON("/get-countries", function (data) {
                let options = '<option value="">Select Country</option>';
                $.each(data, function (key, country) {
                    options += `<option value="${country.id}">${country.name}</option>`;
                });
                $("#country").html(options);
            });

            // Load States when Country changes
            $("#country").on("change", function () {
                let countryId = $(this).val();
                $("#state").html('<option value="">Loading...</option>');
                $("#city").html('<option value="">Select State First</option>');

                if (countryId) {
                    $.getJSON("/get-states/" + countryId, function (data) {
                        let options = '<option value="">Select State</option>';
                        $.each(data, function (key, state) {
                            options += `<option value="${state.id}">${state.name}</option>`;
                        });
                        $("#state").html(options);
                    });
                }
            });

            // Load Cities when State changes
            $("#state").on("change", function () {
                let stateId = $(this).val();
                $("#city").html('<option value="">Loading...</option>');

                if (stateId) {
                    $.getJSON("/get-cities/" + stateId, function (data) {
                        let options = '<option value="">Select City</option>';
                        $.each(data, function (key, city) {
                            options += `<option value="${city.id}">${city.name}</option>`;
                        });
                        $("#city").html(options);
                    });
                }
            });
        });
    </script>

>>>>>>> 23c30d7 (Escort project)
@endsection