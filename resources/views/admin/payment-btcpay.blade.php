@extends('admin.layout')

@section('title', 'Pay with BTC Pay')

@section('content')
    <div class="container py-5">
        <div class="card shadow p-4">
            <h4>Pay with BTC Pay</h4>
            <p>Click the button below to proceed with BTC Pay checkout.</p>

            {{-- Form to create invoice via Laravel --}}
            <form method="POST" action="{{ route('admin.payment.btcpay.create') }}">
                @csrf
                <button type="submit">
                    <img src="https://btcpay.coincharge.io/img/paybutton/pay.svg" style="width:180px" alt="Pay with BTCPay">
                </button>
            </form>
        </div>
    </div>
@endsection