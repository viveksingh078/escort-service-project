@extends('admin.layout')
@section('title', 'Manage Countries')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Manage Countries</h5>
                <a href="{{ route('admin.countries.create') }}" class="btn btn-sm btn-primary">Add Country</a>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="countryTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Flag</th>
                            <th>Country Name</th>
                            <th>ISO2</th>
                            <th>ISO3</th>
                            <th>Phone Code</th>
                            <th>Escorts Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- View Country Modal -->
    <div class="modal fade" id="viewCountryModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">View Country</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view-modal-body">
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Country Modal -->
    <div class="modal fade" id="editCountryModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Country</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCountryForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="countryId" id="countryId" value="">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" name="id" id="id" class="form-control rounded-pill" readonly>
                            <p class="invalid-feedback"></p>
                        </div>
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
                            <img id="flag_preview" src="" alt="Flag Preview" style="max-width: 100px; display: none; margin-top: 10px;">
                        </div>
                        <button type="submit" id="updateBtn" class="btn btn-primary">Update Country</button>
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

            // Debug
            console.log('jQuery version:', $.fn.jquery);
            if (!$.fn.DataTable) {
                console.error('DataTable plugin not loaded. Check if datatables.min.js is included.');
                alert('DataTable plugin missing.');
                return;
            }

            // Load DataTable
            function loadCountries() {
                $('#countryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: '{{ route("admin.countries.manage") }}',
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'id' },
                        {
                            data: 'flag',
                            render: function (data) {
                                if (data) {
                                    return `<img src="/${data}" alt="Flag" style="width: 30px; height: auto;">`; // Correct path with leading slash
                                }
                                return `<span>No flag available</span>`;
                            }
                        },
                        { data: 'name' },
                        { data: 'iso2' },
                        { data: 'iso3' },
                        { data: 'phonecode' },
                        { data: 'escorts_count' },
                        {
                            data: 'action',
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-info viewCountryFlagBtn" data-id="${row.id}">View</button>
                                    <button class="btn btn-sm btn-warning editCountryFlagBtn" data-id="${row.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delCountryFlagBtn" data-id="${row.id}">Delete</button>
                                `;
                            },
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }
            loadCountries();

            // View Country
            $(document).on('click', '.viewCountryFlagBtn', function () {
                let id = $(this).data('id');
                $.get('{{ route("admin.countries.view", ":id") }}'.replace(':id', id), function (res) {
                    if (res.success) {
                        let d = res.data;
                        let flagSrc = d.flag ? `<img src="/${d.flag}" alt="Flag" style="width: 50px; height: auto;">` : 'No flag available';
                        let content = `
                            <p><strong>ID:</strong> ${d.id}</p>
                            <p><strong>Name:</strong> ${d.name}</p>
                            <p><strong>Flag:</strong> ${flagSrc}</p>
                            <p><strong>ISO2:</strong> ${d.iso2 || 'N/A'}</p>
                            <p><strong>ISO3:</strong> ${d.iso3 || 'N/A'}</p>
                            <p><strong>Phone Code:</strong> ${d.phonecode || 'N/A'}</p>
                            <p><strong>Escorts Count:</strong> ${d.escorts_count || 0}</p>
                        `;
                        $('#view-modal-body').html(content);
                        jQuery('#viewCountryModal').modal('show');
                    } else {
                        alert(res.message || 'Failed to load country.');
                    }
                });
            });

            // Edit Country
            $(document).on('click', '.editCountryFlagBtn', function () {
                let id = $(this).data('id');
                $('#countryId').val(id);
                $.get('{{ route("admin.countries.edit", ["id" => ":id"]) }}'.replace(':id', id), function (res) {
                    if (res.success) {
                        let d = res.data;
                        $('#id').val(d.id);
                        $('#name').val(d.name);
                        $('#iso2').val(d.iso2);
                        $('#iso3').val(d.iso3);
                        $('#phonecode').val(d.phonecode);
                        $('#flag').val(''); // Clear file input
                        if (d.flag) {
                            $('#flag_preview').attr('src', '/' + d.flag).show(); // Correct path with leading slash
                        } else {
                            $('#flag_preview').hide();
                        }
                        jQuery('#editCountryModal').modal('show');
                    } else {
                        alert(res.message || 'Failed to load country.');
                    }
                }).fail(function (xhr) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Error loading country data.');
                });
            });

            // Preview Flag Image on File Select
            $('#flag').on('change', function () {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#flag_preview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Update Country
            $(document).on('click', '#updateBtn', function (e) {
                e.preventDefault();
                let id = $('#countryId').val();
                if (!id) { alert("Country ID nahi mila!"); return; }

                let form = $(this).closest('form')[0];
                let formData = new FormData(form);
                formData.append('_method', 'PUT');

                $(form).find('.invalid-feedback').empty();
                $(form).find('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route("admin.countries.update", ":id") }}'.replace(':id', id),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.success) {
                            jQuery('#editCountryModal').modal('hide');
                            loadCountries();
                            alert(res.message || 'Country updated successfully!');
                        } else if (res.errors) {
                            Object.keys(res.errors).forEach(field => {
                                let input = $(`#editCountryForm [name="${field}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    input.closest('.form-group').find('.invalid-feedback').text(res.errors[field][0]);
                                }
                            });
                        } else {
                            alert(res.message || 'Failed to update country.');
                        }
                    },
                    error: function (xhr) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Error updating country.');
                    }
                });
            });

            // Delete Country
            $(document).on('click', '.delCountryFlagBtn', function () {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this country?')) {
                    $.ajax({
                        url: '{{ route("admin.countries.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        success: function (res) {
                            if (res.success) {
                                loadCountries();
                                alert(res.message || 'Country deleted successfully.');
                            } else {
                                alert(res.message || 'Failed to delete country.');
                            }
                        },
                        error: function (xhr) {
                            console.log('AJAX Error:', xhr.responseText);
                            alert('Error deleting country.');
                        }
                    });
                }
            });
        });
    </script>
@endsection