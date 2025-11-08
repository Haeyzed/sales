@extends('emails.layouts.email')

@section('content')
    <h2>Welcome to {{ $tenantData['superadmin_company_name'] ?? 'Our Platform' }}!</h2>
    
    <p>Dear {{ $tenantData['name'] ?? 'Valued Customer' }},</p>
    
    <p>Congratulations! Your subscription account has been successfully created. We're excited to have you on board!</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Your Account Details</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Company Name:</span>
        <span class="info-value"><strong>{{ $tenantData['company_name'] ?? 'N/A' }}</strong></span>
    </div>
    
    @if(isset($tenantData['email']))
    <div class="info-row">
        <span class="info-label">Email:</span>
        <span class="info-value">{{ $tenantData['email'] }}</span>
    </div>
    @endif
    
    @if(isset($tenantData['domain']))
    <div class="info-row">
        <span class="info-label">Domain:</span>
        <span class="info-value">{{ $tenantData['domain'] }}</span>
    </div>
    @endif
    
    @if(isset($tenantData['subscription_plan']))
    <div class="info-row">
        <span class="info-label">Subscription Plan:</span>
        <span class="info-value">{{ $tenantData['subscription_plan'] }}</span>
    </div>
    @endif
    
    @if(isset($tenantData['login_url']))
    <div class="divider"></div>
    <p style="margin-top:24px;text-align:center;">
        <a href="{{ $tenantData['login_url'] }}" class="button">Get Started</a>
    </p>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Get started with your subscription today! Our team is here to help you every step of the way.</p>
    
    <p style="margin-top:16px;">If you have any questions or need assistance setting up your account, please don't hesitate to contact our support team.</p>
    
    <p style="margin-top:16px;"><strong>Thank you for choosing us!</strong></p>
@endsection

