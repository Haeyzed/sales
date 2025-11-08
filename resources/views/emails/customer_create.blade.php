@extends('emails.layouts.email')

@section('content')
    <h2>Welcome, {{ $customerData['name'] ?? 'Valued Customer' }}!</h2>

    <p>Thank you for choosing us! We're thrilled to have you as our customer.</p>

    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Your Account Information</p>
    </div>

    <div class="info-row">
        <span class="info-label">Name:</span>
        <span class="info-value">{{ $customerData['name'] ?? 'N/A' }}</span>
    </div>

    @if(isset($customerData['company_name']))
    <div class="info-row">
        <span class="info-label">Company:</span>
        <span class="info-value">{{ $customerData['company_name'] }}</span>
    </div>
    @endif

    @if(isset($customerData['email']))
    <div class="info-row">
        <span class="info-label">Email:</span>
        <span class="info-value">{{ $customerData['email'] }}</span>
    </div>
    @endif

    @if(isset($customerData['phone_number']))
    <div class="info-row">
        <span class="info-label">Phone:</span>
        <span class="info-value">{{ $customerData['phone_number'] }}</span>
    </div>
    @endif

    @if(isset($customerData['customer_group']))
    <div class="info-row">
        <span class="info-label">Customer Group:</span>
        <span class="info-value">{{ $customerData['customer_group'] }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <p style="margin-top:24px;">We hope that our service will satisfy you. Our team is committed to providing you with the best experience possible.</p>

    <p style="margin-top:16px;">If you have any questions or need assistance, please feel free to contact us anytime.</p>

    <p style="margin-top:16px;"><strong>Thank you for your trust in us!</strong></p>
@endsection

