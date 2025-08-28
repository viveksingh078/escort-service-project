@extends('admin.layout')
@section('title', 'Payment Gateway')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container-fluid p-0 m-0 py-4">
        <div class="container py-5 px-5 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="pb-1">Payment Gateway</h5>
            </div>
            <hr class="p-0 my-3">

            <div class="table-responsive">
                <table id="paymentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Name</th>
                            <th>Payment Details</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Razorpay Modal -->
    <div class="modal fade" id="razorpayModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Razorpay</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="razorpayForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Key ID</label>
                            <input type="text" class="form-control" id="razorpayKeyId" required>
                        </div>
                        <div class="form-group">
                            <label>Key Secret</label>
                            <input type="text" class="form-control" id="razorpayKeySecret" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="razorpayStatus" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PayPal Modal -->
    <div class="modal fade" id="paypalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit PayPal</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="paypalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Client ID</label>
                            <input type="text" class="form-control" id="paypalClientId" required>
                        </div>
                        <div class="form-group">
                            <label>Client Secret</label>
                            <input type="text" class="form-control" id="paypalClientSecret" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="paypalStatus" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stripe Modal -->
    <div class="modal fade" id="stripeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Stripe</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="stripeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Public Key</label>
                            <input type="text" class="form-control" id="stripePublicKey" required>
                        </div>
                        <div class="form-group">
                            <label>Secret Key</label>
                            <input type="text" class="form-control" id="stripeSecretKey" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="stripeStatus" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BTC Pay Modal -->
    <div class="modal fade" id="btcPayModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit BTC Pay</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="btcPayForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>BTC Pay Key</label>
                            <input type="text" class="form-control" id="btcPayKey" required>
                        </div>
                        <div class="form-group">
                            <label>BTC Pay Secret</label>
                            <input type="text" class="form-control" id="btcPaySecret" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="btcPayStatus" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            loadPaymentGateways();

            function loadPaymentGateways() {
                // Payment icons mapping
                function getPaymentIcon(name) {
                    name = name.toLowerCase();
                    if (name.includes("razorpay")) return `<i class="fab fa-cc-mastercard text-primary"></i>`;
                    if (name.includes("paypal")) return `<i class="fab fa-paypal text-primary"></i>`;
                    if (name.includes("stripe")) return `<i class="fab fa-cc-stripe text-info"></i>`;
                    if (name.includes("btc")) return `<i class="fas fa-credit-card text-success"></i>`;
                    return `<i class="fas fa-money-bill-wave text-secondary"></i>`; // default
                }

                $.get("{{ route('admin.paymentgateway.list') }}", function (res) {
                    let rows = '';
                    res.data.forEach((pg, index) => {
                        let detailsObj = JSON.parse(pg.details || '{}');
                        let detailsStr = Object.entries(detailsObj).map(([key, value]) => `${key.replace('_', ' ').toUpperCase()}: ${value || ''}`).join(', ');
                        rows += `
                                                                                            <tr>
                                                                                                <td>${index + 1}</td>
                                                                                                <td>${getPaymentIcon(pg.name)} ${pg.name}</td>
                                                                                                <td>${detailsStr}</td>
                                                                                                <td>${pg.status}</td>
                                                                                                <td>
                                                                                                    <button class="btn btn-warning btn-sm editBtn" 
                                                                                                        data-name="${pg.name.toLowerCase().replace(' ', '-')}">Edit</button>
                                                                                                    <button class="btn btn-danger btn-sm deleteBtn" data-name="${pg.name.toLowerCase().replace(' ', '-')}">Credential</button>
                                                                                                </td>
                                                                                            </tr>`;
                    });
                    $("#paymentTable tbody").html(rows);
                });
            }

            $(document).on("click", ".editBtn", function () {
                let gateway = $(this).data("name"); // e.g., 'btc-pay'
                $.get("/admin/payment-gateway/edit/" + gateway, function (res) {
                    if (gateway === 'razorpay') {
                        $("#razorpayKeyId").val(res.key_id);
                        $("#razorpayKeySecret").val(res.key_secret);
                        $("#razorpayStatus").val(res.status);
                        $("#razorpayModal").modal('show');
                    } else if (gateway === 'paypal') {
                        $("#paypalClientId").val(res.client_id);
                        $("#paypalClientSecret").val(res.client_secret);
                        $("#paypalStatus").val(res.status);
                        $("#paypalModal").modal('show');
                    } else if (gateway === 'stripe') {
                        $("#stripePublicKey").val(res.public_key);
                        $("#stripeSecretKey").val(res.secret_key);
                        $("#stripeStatus").val(res.status);
                        $("#stripeModal").modal('show');
                    } else if (gateway === 'btc-pay') {
                        $("#btcPayKey").val(res.btc_pay_key);
                        $("#btcPaySecret").val(res.btc_pay_secret);
                        $("#btcPayStatus").val(res.status);
                        $("#btcPayModal").modal('show');
                    }
                });
            });

            $("#razorpayForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/payment-gateway/update/razorpay",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        key_id: $("#razorpayKeyId").val(),
                        key_secret: $("#razorpayKeySecret").val(),
                        status: $("#razorpayStatus").val()
                    },
                    success: function () {
                        $("#razorpayModal").modal('hide');
                        loadPaymentGateways();
                        showSuccessToast("Razorpay updated!");
                    },
                    error: function () {
                        showDangerToast("Something went wrong.");
                    }
                });
            });

            $("#paypalForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/payment-gateway/update/paypal",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        client_id: $("#paypalClientId").val(),
                        client_secret: $("#paypalClientSecret").val(),
                        status: $("#paypalStatus").val()
                    },
                    success: function () {
                        $("#paypalModal").modal('hide');
                        loadPaymentGateways();
                        showSuccessToast("PayPal updated!");
                    },
                    error: function () {
                        showDangerToast("Something went wrong.");
                    }
                });
            });

            $("#stripeForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/payment-gateway/update/stripe",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        public_key: $("#stripePublicKey").val(),
                        secret_key: $("#stripeSecretKey").val(),
                        status: $("#stripeStatus").val()
                    },
                    success: function () {
                        $("#stripeModal").modal('hide');
                        loadPaymentGateways();
                        showSuccessToast("Stripe updated!");
                    },
                    error: function () {
                        showDangerToast("Something went wrong.");
                    }
                });
            });

            $("#btcPayForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/payment-gateway/update/btc-pay",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        btc_pay_key: $("#btcPayKey").val(),
                        btc_pay_secret: $("#btcPaySecret").val(),
                        status: $("#btcPayStatus").val()
                    },
                    success: function () {
                        $("#btcPayModal").modal('hide');
                        loadPaymentGateways();
                        showSuccessToast("BTC Pay updated!");
                    },
                    error: function () {
                        showDangerToast("Something went wrong.");
                    }
                });
            });

            $(document).on("click", ".deleteBtn", function () {
                let gateway = $(this).data("name");
                if (!confirm("Are you sure?")) return;
                $.ajax({
                    url: "/admin/payment-gateway/delete/" + gateway,
                    type: "DELETE",
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        loadPaymentGateways();
                        showSuccessToast("Deleted successfully!");
                    },
                    error: function () {
                        showDangerToast("Failed to delete.");
                    }
                });
            });

            // Toast Functions
            function showSuccessToast(message) {
                toastr.success(message, 'Success', { timeOut: 5000 });
            }
            function showWarningToast(message) {
                toastr.warning(message, 'Warning', { timeOut: 5000 });
            }
            function showDangerToast(message) {
                toastr.error(message, 'Error', { timeOut: 5000 });
            }
        });
    </script>

    <!-- Toastr CSS & JS -->
    @if (!isset($toastrIncluded))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <?php    $toastrIncluded = true; ?>
    @endif

@endsection