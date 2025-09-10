@extends('admin.layout')
@section('title', 'Support Tickets')
@section('content')
    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Tickets</h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ticketsModal">Add New
                    Ticket</button>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="ticketsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket ID</th>
                            <th>Email ID</th>
                            <th>Category</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Ticket Modal -->
    <div class="modal fade" id="ticketsModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="ticketsForm" class="small" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="tickets_method" value="POST">
                        <input type="hidden" id="ticket_id" name="ticket_id">
                        <h5 id="formTitle">Add New Ticket</h5>
                        <div class="" id="msg"></div>
                        <hr>
                        <div class="form-group">
                            <label for="ticket_name">Name</label>
                            <input type="text" name="name" id="ticket_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="ticket_email">Email</label>
                            <input type="email" name="email" id="ticket_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="ticket_category">Category</label>
                            <select name="category" id="ticket_category" class="form-control">
                                <option value="account">Account</option>
                                <option value="listing">Listing</option>
                                <option value="billing">Billing</option>
                                <option value="safety">Safety</option>
                                <option value="technical">Technical</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ticket_message">Message</label>
                            <textarea name="message" id="ticket_message" class="form-control" rows="6"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ticket_attachment">Attachment</label>
                            <input type="file" name="attachment" id="ticket_attachment" class="form-control">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary mt-3">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Ticket Modal -->
    <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 id="ticketCategory"></h5>
                    <hr>
                    <p id="ticketMessage"></p>
                    <p><strong>Submitted by:</strong> <span id="ticketName"></span> (<span id="ticketEmail"></span>)</p>
                    <p><strong>Attachment:</strong> <a id="ticketAttachment" target="_blank">View</a></p>
                </div>
                <div class="modal-footer justify-content-between align-items-center">
                    <small class="text-dark small font-weight-bold mt-3"><span id="ticketCreatedAt"></span></small>
                    <button type="button" class="btn btn-sm btn-secondary mx-1" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Load Tickets Table
            function loadTickets() {
                $('#ticketsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route("admin.tickets.list") }}',
                        type: 'GET'
                    },
                    columns: [
                        {
                            data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function (data, type, row, meta) {
                                return meta.row + 1; // Dynamic serial number (1, 2, 3...)
                            }
                        },
                        { data: 'id', name: 'id' }, // Ticket ID
                        { data: 'email', name: 'email' }, // Email ID
                        { data: 'category', name: 'category' },
                        { data: 'message', name: 'message' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    error: function (xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                    }
                });
            }

            loadTickets();

            // Submit Add/Edit Ticket Form
            $('#ticketsForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#ticket_id').val();
                const ticketName = $('#ticket_name').val().trim();
                const ticketEmail = $('#ticket_email').val().trim();
                const ticketCategory = $('#ticket_category').val();
                const ticketMessage = $('#ticket_message').val().trim();
                const ticketAttachment = $('#ticket_attachment')[0].files[0];

                if (!ticketName || !ticketEmail || !ticketCategory || !ticketMessage) {
                    showWarningToast("All fields are required except attachment.");
                    return;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', id ? 'PUT' : 'POST');
                formData.append('name', ticketName);
                formData.append('email', ticketEmail);
                formData.append('category', ticketCategory);
                formData.append('message', ticketMessage);
                if (ticketAttachment) {
                    formData.append('attachment', ticketAttachment);
                }

                const url = id ? '{{ route("admin.tickets.update", ["id" => ":id"]) }}'.replace(':id', id) : '{{ route("admin.tickets.store") }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#ticketsForm')[0].reset();
                            $('#tickets_method').val('POST');
                            $('#formTitle').text('Add New Ticket');
                            jQuery('#ticketsModal').modal('hide');
                            loadTickets();
                            showSuccessToast(response.message);
                        } else {
                            showWarningToast(response.message || "Failed to save ticket.");
                        }
                    },
                    error: function (xhr) {
                        console.log('Submit error:', xhr.responseText);
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
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route("admin.tickets.edit", ["id" => ":id"]) }}'.replace(':id', id),
                    type: 'GET',
                    success: function (res) {
                        $('#formTitle').text('Edit Ticket');
                        $('#ticket_id').val(res.id);
                        $('#ticket_name').val(res.name);
                        $('#ticket_email').val(res.email);
                        $('#ticket_category').val(res.category);
                        $('#ticket_message').val(res.message);
                        $('#tickets_method').val('PUT');
                        jQuery('#ticketsModal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        showDangerToast("Failed to load ticket data.");
                    }
                });
            });

            // Delete Ticket
            $(document).on('click', '.delfaqsBtn', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this ticket?')) {
                    $.ajax({
                        url: '{{ route("admin.tickets.delete", ["id" => ":id"]) }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.success) {
                                loadTickets(); // Reload table after delete
                                showSuccessToast(res.message);
                            } else {
                                showWarningToast(res.message);
                            }
                        },
                        error: function () {
                            showDangerToast("Failed to delete ticket. Please try again.");
                        }
                    });
                }
            });

            // View Ticket
            $(document).on('click', '.viwBtn', function () {
                let ticketId = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.tickets.view', ':id') }}".replace(':id', ticketId),
                    type: "GET",
                    success: function (data) {
                        $('#ticketCategory').text(data.category);
                        $('#ticketMessage').text(data.message);
                        $('#ticketName').text(data.name);
                        $('#ticketEmail').text(data.email);
                        $('#ticketAttachment').attr('href', data.attachment || '#');
                        $('#ticketCreatedAt').text(data.created_at);
                        jQuery('#viewTicketModal').modal('show');
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('Could not load ticket details.');
                    }
                });
            });
        });
    </script>
@endsection