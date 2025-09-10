@extends('admin.layout')
@section('title', 'Support Faqs')
@section('content')


    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">FAQs</h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#faqsModal">Add New Faqs</button>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="faqsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit faqs Modal -->
    <div class="modal fade" id="faqsModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-body">
                    <form id="faqsForm" class="small" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="faqs_method" value="POST">
                        <input type="hidden" id="faqs_id" name="faqs_id">
                        <h5 id="formTitle">Add New faqs</h5>
                        <div class="" id="msg"></div>
                        <hr>
                        <div class="form-group">
                            <label for="faqs_name">Question</label>
                            <input type="text" name="name" id="faqs_question" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="faqs_description">Answer</label>
                            <textarea name="description" id="faqs_answer" class="form-control" rows="6"></textarea>
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


    <!-- View FAQ Modal -->
    <div class="modal fade" id="viewFaqModal" tabindex="-1" aria-labelledby="viewFaqLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <h5 id="faqQuestion"></h5><hr>
            <p id="faqAnswer" class=""></p>
          </div>

          <div class="modal-footer justify-content-between align-items-center">
            <small class="text-dark small font-weight-bold mt-3"><span id="faqCreatedAt"></span></small>
            <button type="button" class="btn btn-sm btn-secondary mx-1"data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>



    <script>
        jQuery(document).ready(function ($) {
            // Load faqss Table
            function loadfaqss() {
                $('#faqsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route("admin.faqs.list") }}',
                        type: 'GET'
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'question', name: 'question' },
                        { data: 'answer', name: 'answer' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    error: function (xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                    }
                });
            }

            loadfaqss();

            // Submit Add/Edit faqs Form
            $('#faqsForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#faqs_id').val();
                const faqsQuestion = $('#faqs_question').val().trim();
                const faqsAnswer = $('#faqs_answer').val().trim();

                if (!faqsQuestion || !faqsAnswer) {
                    showWarningToast("All fields are required.");
                    return;
                }

                const url = id ? '{{ route("admin.faqs.update", ["id" => ":id"]) }}'.replace(':id', id) : '{{ route("admin.faqs.store") }}';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: method,
                        question: faqsQuestion,
                        answer: faqsAnswer
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#faqsForm')[0].reset();
                            $('#faqs_method').val('POST');
                            $('#formTitle').text('Add New faqs');
                            jQuery('#faqsModal').modal('hide');
                            loadfaqss();
                            showSuccessToast(response.message);
                        } else {
                            showWarningToast(response.message || "Failed to save faqs.");
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
                    url: '{{ route("admin.faqs.edit", ["id" => ":id"]) }}'.replace(':id', id),
                    type: 'GET',
                    success: function (res) {
                        $('#formTitle').text('Edit faqs');
                        $('#faqs_id').val(res.id);
                        $('#faqs_question').val(res.question);
                        $('#faqs_answer').val(res.answer);
                        $('#faqs_method').val('PUT');
                        jQuery('#faqsModal').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        showDangerToast("Failed to load faqs data.");
                    }
                });
            });

            // Delete faqs
            $(document).on('click', '.delfaqsBtn', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this faqs?')) {
                    $.ajax({
                        url: '{{ route("admin.faqs.delete", ["id" => ":id"]) }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.success) {
                                loadfaqss();
                                showSuccessToast(res.message);
                            } else {
                                showWarningToast(res.message);
                            }
                        },
                        error: function () {
                            showDangerToast("Failed to delete faqs. Please try again.");
                        }
                    });
                }
            });

            $(document).on('click', '.viwBtn', function () {
              let faqId = $(this).data('id');

              $.ajax({
                  url: "{{ route('admin.faqs.view', ':id') }}".replace(':id', faqId),
                  type: "GET",
                  success: function (data) {
                      $('#faqQuestion').text(data.question);
                      $('#faqAnswer').text(data.answer);
                      $('#faqCreatedAt').text(data.created_at);
                      jQuery('#viewFaqModal').modal('show');
                  },
                  error: function (xhr) {
                      console.error(xhr.responseText);
                      alert('Could not load FAQ details.');
                  }
              });
          });



        });
    </script>

@endsection