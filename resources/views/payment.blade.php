@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pay with BTCPay</h2>
        <p>Signed in as: {{ auth('fan')->user()->email }}</p>
        <p>Amount: ${{ $invoice->amount }}</p>
        <label>Select Crypto:</label>
        <select id="crypto-select">
            @foreach($details as $method => $det)
                <option value="{{ $method }}">{{ $method }}</option>
            @endforeach
        </select>
        <div id="payment-details" style="display:none;">
            <img id="qr-image" src="" alt="QR Code">
            <p>Amount: <span id="crypto-amount"></span> <span id="crypto-symbol"></span></p>
            <p>Address: <span id="address"></span></p>
            <button id="copy-address">Copy Address</button>
        </div>
        <p><input type="checkbox" required> Accept Terms & Privacy</p>
        <small>Secured with SSL</small>
    </div>

    <script>
        const details = @json($details);
        const qrImages = @json($qrImages);
        const select = document.getElementById('crypto-select');
        const paymentDetails = document.getElementById('payment-details');
        const qrImage = document.getElementById('qr-image');
        const cryptoAmount = document.getElementById('crypto-amount');
        const cryptoSymbol = document.getElementById('crypto-symbol');
        const address = document.getElementById('address');
        const copyBtn = document.getElementById('copy-address');

        select.addEventListener('change', function () {
            const method = this.value;
            if (details[method]) {
                qrImage.src = 'data:image/png;base64,' + qrImages[method];
                cryptoAmount.textContent = details[method].amount;
                cryptoSymbol.textContent = method;
                address.textContent = details[method].destination;
                paymentDetails.style.display = 'block';
            }
        });

        // Initial load
        if (select.options.length > 0) {
            select.value = select.options[0].value;
            select.dispatchEvent(new Event('change'));
        }

        copyBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(address.textContent).then(() => {
                alert('Copied!');
            });
        });
    </script>
@endsection