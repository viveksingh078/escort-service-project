@extends('admin.layout')
@section('title', 'Add Country')
@section('content')
    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <h5 class="pb-1">Add Country</h5>
            <hr class="p-0 my-3">
            <form id="addCountryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Country Name</label>
                    <input type="text" name="name" id="name" class="form-control rounded-pill" value="">
                    <p class="invalid-feedback"></p>
                </div>
                <div class="form-group">
                    <label for="iso2">ISO2</label>
                    <input type="text" name="iso2" id="iso2" class="form-control rounded-pill" value="">
                    <p class="invalid-feedback"></p>
                </div>
                <div class="form-group">
                    <label for="iso3">ISO3</label>
                    <input type="text" name="iso3" id="iso3" class="form-control rounded-pill" value="">
                    <p class="invalid-feedback"></p>
                </div>
                <div class="form-group">
                    <label for="phonecode">Phone Code</label>
                    <input type="text" name="phonecode" id="phonecode" class="form-control rounded-pill" value="">
                    <p class="invalid-feedback"></p>
                </div>
                <div class="form-group">
                    <label for="flag">Flag Image</label>
                    <input type="file" name="flag" id="flag" class="form-control rounded-pill">
                    <p class="invalid-feedback"></p>
                    <img id="flag_preview" src="" alt="Flag Preview"
                        style="max-width: 100px; display: none; margin-top: 10px;">
                </div>
                <button type="submit" id="addBtn" class="btn btn-primary">Add Country</button>
            </form>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Preview Flag Image on File Select
            $('#flag').on('change', function () {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#flag_preview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Add Country
            $('#addCountryForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route("admin.countries.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.success) {
                            alert(res.message || 'Country added successfully!');
                            window.location.href = '{{ route("admin.countries.manage") }}';
                        } else if (res.errors) {
                            Object.keys(res.errors).forEach(field => {
                                let input = $(`#addCountryForm [name="${field}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    input.closest('.form-group').find('.invalid-feedback').text(res.errors[field][0]);
                                }
                            });
                        } else {
                            alert(res.message || 'Failed to add country.');
                        }
                    },
                    error: function (xhr) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Error adding country.');
                    }
                });
            });
        });
    </script>
@endsection