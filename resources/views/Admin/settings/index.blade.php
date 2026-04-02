@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">System Settings</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <!-- IMPORTANT: Removed @method('PUT') – we use POST -->

                <h4 class="mb-3">Pricing & Charges</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="service_charge_amount" class="form-label">Service Charge Amount (₦)</label>
                        <input type="number" name="service_charge_amount" step="0.01" class="form-control" 
                               value="{{ old('service_charge_amount', $serviceChargeAmount) }}" required min="0">
                        <small class="form-text text-muted">Fixed amount added to every order</small>
                    </div>

                    

                <hr>

                <h4 class="mb-3">Payment Gateways</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="paystack_enabled" value="1" 
                                   class="form-check-input" id="paystack_enabled" 
                                   {{ $paystackEnabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="paystack_enabled">Enable Paystack</label>
                        </div>

                        <input type="text" name="paystack_public_key" class="form-control mb-2" 
                               placeholder="Public Key" value="{{ old('paystack_public_key', $paystackPublicKey) }}">

                        <input type="text" name="paystack_secret_key" class="form-control" 
                               placeholder="Secret Key" value="{{ old('paystack_secret_key', $paystackSecretKey) }}">
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="manual_bank_enabled" value="1" 
                                   class="form-check-input" id="manual_bank_enabled" 
                                   {{ $manualBankEnabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="manual_bank_enabled">Enable Manual Bank Transfer</label>
                        </div>

                        <input type="text" name="manual_bank_name" class="form-control mb-2" 
                               placeholder="Bank Name" value="{{ old('manual_bank_name', $manualBankName) }}">

                        <input type="text" name="manual_account_number" class="form-control mb-2" 
                               placeholder="Account Number" value="{{ old('manual_account_number', $manualAccountNumber) }}">

                        <input type="text" name="manual_account_name" class="form-control" 
                               placeholder="Account Name" value="{{ old('manual_account_name', $manualAccountName) }}">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection