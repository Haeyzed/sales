@extends('emails.layouts.email')

@section('content')
    <h2>Welcome, {{ $supplierData['name'] ?? 'New Supplier' }}!</h2>
    
    <p>Thank you for partnering with us. We're excited to work with you!</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Your Account Details</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Name:</span>
        <span class="info-value">{{ $supplierData['name'] ?? 'N/A' }}</span>
    </div>
    
    @if(isset($supplierData['company_name']))
    <div class="info-row">
        <span class="info-label">Company:</span>
        <span class="info-value">{{ $supplierData['company_name'] }}</span>
    </div>
    @endif
    
    @if(isset($supplierData['email']))
    <div class="info-row">
        <span class="info-label">Email:</span>
        <span class="info-value">{{ $supplierData['email'] }}</span>
    </div>
    @endif
    
    @if(isset($supplierData['phone_number']))
    <div class="info-row">
        <span class="info-label">Phone:</span>
        <span class="info-value">{{ $supplierData['phone_number'] }}</span>
    </div>
    @endif
    
    @if(isset($supplierData['vat_number']))
    <div class="info-row">
        <span class="info-label">VAT Number:</span>
        <span class="info-value">{{ $supplierData['vat_number'] }}</span>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">We look forward to a successful partnership. If you have any questions or need assistance, please don't hesitate to reach out to us.</p>
    
    <p style="margin-top:16px;"><strong>Thank you for being part of our supply chain!</strong></p>
@endsection

