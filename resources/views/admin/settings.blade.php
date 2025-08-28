@extends('admin.layout')
@section('title', 'Admin Settings')
@section('content')

<div class="container-fluid p-0 m-0 py-4">
  <div class="container py-5 px-5 bg-white">
    <div class="d-flex justify-content-between align-items-center">
      <div class="">
        <h5 class="pb-1">Web Settings </h5>
      </div>
      <div class=""></div>
    </div>
    <hr class="p-0 m-0">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
    @csrf

        <div class="row">

            <!-- Website URL -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                <div class="form-group">
                    <label for="website_url" class="small m-0 p-0">Website URL</label>
                    <input type="text" name="website_url" id="website_url" class="form-control  @error('website_url') is-invalid @enderror" placeholder="Enter website URL" value="{{ old('website_url', get_option('website_url')) }}">
                    @error('website_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Website Name -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                <div class="form-group">
                    <label for="website_name" class="small m-0 p-0">Website Name</label>
                    <input type="text" name="website_name" id="website_name" class="form-control  @error('website_name') is-invalid @enderror" placeholder="Enter website name" value="{{ old('website_name', get_option('website_name')) }}" >
                    @error('website_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Meta Title -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-3">
                <div class="form-group">
                    <label for="meta_title" class="small m-0 p-0">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Enter meta title" value="{{ old('meta_title', get_option('meta_title')) }}" >
                    @error('meta_title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Meta Description -->
            <div class="col-sm-12 col-lg-12 col-md-12 mb-3">
                <div class="form-group">
                    <label for="meta_description" class="small m-0 p-0">Meta Description</label>
                    <textarea class="form-control" name="meta_description" id="meta_description" style="height: 100px" placeholder="Enter meta description">{{ old('meta_description', get_option('meta_description')) }}</textarea>
                    @error('meta_description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="email" class="small m-0 p-0">Email</label>
                    <input type="email" name="email" id="email" class="form-control  @error('email') is-invalid @enderror" placeholder="Enter email address" value="{{ old('email', get_option('email')) }}" >
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Secondary Email -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="secondary_email" class="small m-0 p-0">Secondary Email</label>
                    <input type="email" name="secondary_email" id="secondary_email" class="form-control" placeholder="Enter secondary email" value="{{ old('secondary_email', get_option('secondary_email')) }}" >
                    @error('secondary_email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Phone Number -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="phone_number" class="small m-0 p-0">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="form-control  @error('phone_number') is-invalid @enderror" placeholder="Enter phone number" value="{{ old('phone_number', get_option('phone_number')) }}" >
                    @error('phone_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Secondary Phone -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="secondary_phone" class="small m-0 p-0">Secondary Phone Number</label>
                    <input type="tel" name="secondary_phone" id="secondary_phone" class="form-control" placeholder="Enter secondary phone number" value="{{ old('secondary_phone', get_option('secondary_phone')) }}">
                    @error('secondary_phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                <div class="form-group">
                    <label for="address" class="small m-0 p-0">Address</label>
                    <textarea class="form-control  @error('address') is-invalid @enderror" name="address" id="address" style="height: 100px" placeholder="Enter address">{{ old('address', get_option('address')) }}</textarea>
                    @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Secondary Address -->
            <div class="col-sm-12 col-lg-6 col-md-6 mb-3">
                <div class="form-group">
                    <label for="secondary_address" class="small m-0 p-0">Secondary Address</label>
                    <textarea class="form-control" name="secondary_address" id="secondary_address" style="height: 100px" placeholder="Enter secondary address">{{ old('secondary_address', get_option('secondary_address')) }}</textarea>
                    @error('secondary_address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Map Embed Code -->
            <div class="col-sm-12 col-lg-12 col-md-12 mb-3">
                <div class="form-group">
                    <label for="map_embed" class="small m-0 p-0">Map (Embedded Code)</label>
                    <textarea class="form-control" name="map_embed" id="map_embed" style="height: 100px" placeholder="Paste embed code for map">{{ old('map_embed', get_option('map_embed')) }}</textarea>
                    @error('map_embed')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Social Links -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="facebook" class="small m-0 p-0">Facebook</label>
                    <input type="url" name="facebook" id="facebook" class="form-control" placeholder="Facebook profile URL" value="{{ old('facebook', get_option('facebook')) }}" >
                    @error('facebook')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="twitter" class="small m-0 p-0">Twitter</label>
                    <input type="url" name="twitter" id="twitter" class="form-control" placeholder="Twitter profile URL" value="{{ old('twitter', get_option('twitter')) }}" >
                    @error('twitter')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="instagram" class="small m-0 p-0">Instagram</label>
                    <input type="url" name="instagram" id="instagram" class="form-control" placeholder="Instagram profile URL" value="{{ old('instagram', get_option('instagram')) }}" >
                    @error('instagram')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="linkedin" class="small m-0 p-0">LinkedIn</label>
                    <input type="url" name="linkedin" id="linkedin" class="form-control" placeholder="LinkedIn profile URL" value="{{ old('linkedin', get_option('linkedin')) }}" >
                    @error('linkedin')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Favicon Upload -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="favicon" class="small m-0 p-0">Favicon</label>
                    <input type="file" name="favicon" id="favicon" class="form-control  @error('favicon') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'favicon_preview')"  >
                    <img id="favicon_preview" src="#" alt="Favicon Preview" class="mt-2" style="display:none; max-width: 100px;">
                    @if(get_option('favicon'))
                        <img src="{{ asset(get_option('favicon')) }}" alt="Current Favicon" style="max-height: 60px;" class="mb-2">
                    @endif
                    @error('favicon')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Logo Upload -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="logo" class="small m-0 p-0">Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control  @error('logo') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'logo_preview')">
                    <img id="logo_preview" src="#" alt="Logo Preview" class="mt-2" style="display:none; max-width: 100px;">
                    @if(get_option('logo'))
                        <img src="{{ asset(get_option('logo')) }}" alt="Current logo" style="max-height: 60px;" class="mb-2">
                    @endif
                    @error('logo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- White Logo Upload -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <label for="white_logo" class="small m-0 p-0">White Logo</label>
                    <input type="file" name="white_logo" id="white_logo" class="form-control @error('white_logo') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'white_logo_preview')">
                    <img id="white_logo_preview" src="#" alt="White Logo Preview" class="mt-2" style="display:none; max-width: 100px;">
                    @if(get_option('white_logo'))
                        <img src="{{ asset(get_option('white_logo')) }}" alt="Current white logo" style="max-height: 60px;" class="mb-2">
                    @endif
                    @error('white_logo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>


        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-sm-12 col-lg-3 col-md-3 mb-3">
                <div class="form-group">
                    <input type="submit" name="submit" id="submit_button" value="Save Settings" class="btn btn-primary">
                </div>
            </div>
        </div>
    </form>



    <script>
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
    </script>



  </div>
</div>


@endsection