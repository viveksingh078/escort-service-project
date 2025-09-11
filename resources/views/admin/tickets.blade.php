@extends('admin.layout')
@section('title', 'Support Tickets')
@section('content')
    <!-- Ensure Toastr CSS and JS are included in your layout -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                    <form id="ticketsForm" class="small" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="tickets_method" value="POST">
                        <input type="hidden" id="ticket_id" name="ticket_id">
                        <h5 id="formTitle">Add New Ticket</h5>
                        <div class="alert" id="msg" style="display:none;"></div>
                        <hr>
                        <div class="form-group">
                            <label for="ticket_name">Name</label>
                            <input type="text" name="name" id="ticket_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ticket_email">Email</label>
                            <input type="email" name="email" id="ticket_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ticket_category">Category</label>
                            <select name="category" id="ticket_category" class="form-control" required>
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
                            <textarea name="message" id="ticket_message" class="form-control" rows="6" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ticket_attachment">Attachment (Max 5MB, PDF/Image only)</label>
                            <input type="file" name="attachment" id="ticket_attachment" class="form-control"
                                accept="image/*,.pdf">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary mt-3" id="submitTicketBtn">Submit</button>
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
                    <h6>Replies:</h6>
                    <ul id="replyList" class="list-group list-group-flush"></ul>
                </div>
                <div class="modal-footer">
                    <small class="text-dark small font-weight-bold"><span id="ticketCreatedAt"></span></small>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-info btn-lg replyBtn mx-2" data-id=""
                            id="replyButton">Reply</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Ticket Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="replyForm">
                    @csrf
                    <input type="hidden" id="reply_ticket_id" name="ticket_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Reply to Ticket</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reply_message">Reply Message</label>
                            <textarea id="reply_message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="sendReplyBtn" class="btn btn-info">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .modal-footer .btn-info {
            padding: 8px 20px;
            font-weight: bold;
        }

        .modal-footer .btn-secondary {
            padding: 6px 15px;
        }

        .alert {
            font-size: 14px;
            padding: 10px;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Initialize Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

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
                                return meta.row + 1;
                            }
                        },
                        { data: 'id', name: 'id' },
                        { data: 'email', name: 'email' },
                        { data: 'category', name: 'category' },
                        { data: 'message', name: 'message' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    error: function (xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                        toastr.error('Failed to load tickets.');
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
                const $submitBtn = $('#submitTicketBtn');

                // Client-side validation
                if (!ticketName || !ticketEmail || !ticketCategory || !ticketMessage) {
                    toastr.warning('All fields except attachment are required.');
                    return;
                }

                // Validate file size (max 5MB) and type
                if (ticketAttachment) {
                    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if (ticketAttachment.size > maxSize) {
                        toastr.warning('Attachment size must be less than 5MB.');
                        return;
                    }
                    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                    if (!allowedTypes.includes(ticketAttachment.type)) {
                        toastr.warning('Only JPEG, PNG, or PDF files are allowed.');
                        return;
                    }
                }

                // Show loading state
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');

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
                            $('#ticket_attachment').val(''); // Clear file input
                            jQuery('#ticketsModal').modal('hide');
                            loadTickets();
                            toastr.success(response.message || 'Ticket added successfully!');
                        } else {
                            toastr.warning(response.message || 'Failed to save ticket.');
                        }
                    },
                    error: function (xhr) {
                        console.log('Submit error:', xhr.responseText);
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMsg = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg);
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html('Submit');
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
                        $('#ticket_attachment').val(''); // Clear file input for edit
                        jQuery('#ticketsModal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        toastr.error('Failed to load ticket data.');
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
                                loadTickets();
                                toastr.success(res.message || 'Ticket deleted successfully!');
                            } else {
                                toastr.warning(res.message || 'Failed to delete ticket.');
                            }
                        },
                        error: function () {
                            toastr.error('Failed to delete ticket. Please try again.');
                        }
                    });
                }
            });

            // View Ticket with Replies
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
                        $('#ticketAttachment').attr('href', data.attachment ? '{{ asset("storage") }}/' + data.attachment : '#');
                        $('#ticketCreatedAt').text(data.created_at);

                        // Load replies
                        $('#replyList').empty();
                        if (data.replies && data.replies.length > 0) {
                            data.replies.forEach(reply => {
                                $('#replyList').append(
                                    `<li class="list-group-item border-bottom">` +
                                    `<strong>Admin:</strong> ${reply.admin_id ? 'Admin #' + reply.admin_id : 'Unknown'}<br>` +
                                    `<small class="text-muted">${new Date(reply.created_at).toLocaleString()}</small><br>` +
                                    `<p class="mb-0">${reply.message}</p>` +
                                    `</li>`
                                );
                            });
                        } else {
                            $('#replyList').append('<li class="list-group-item">No replies yet.</li>');
                        }

                        $('#replyButton').data('id', ticketId);
                        jQuery('#viewTicketModal').modal('show');
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        toastr.error('Could not load ticket details.');
                    }
                });
            });

            // Handle Reply Form Submission
            $('#replyForm').on('submit', function (e) {
                e.preventDefault();

                const ticketId = $('#reply_ticket_id').val();
                const replyMessage = $('#reply_message').val().trim();
                const $submitBtn = $('#sendReplyBtn');

                if (!replyMessage) {
                    toastr.warning('Reply message is required.');
                    return;
                }

                // Show loading
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('message', replyMessage);

                $.ajax({
                    url: '{{ route("admin.tickets.reply", ["ticketId" => ":id"]) }}'.replace(':id', ticketId),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#replyForm')[0].reset();
                            jQuery('#replyModal').modal('hide');
                            toastr.success(response.message || 'Reply sent successfully!');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function (xhr) {
                        console.log('Reply error:', xhr.responseText);
                        let errorMsg = 'Failed to send reply. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg);
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html('Send Reply');
                    }
                });
            });

            // Open Reply Modal
            $(document).on('click', '#replyButton', function () {
                const ticketId = $(this).data('id');
                $('#reply_ticket_id').val(ticketId);

                jQuery('#viewTicketModal').modal('hide');
                setTimeout(function () {
                    jQuery('#replyModal').modal('show');
                }, 300);
            });
        });
    </script>
@endsection