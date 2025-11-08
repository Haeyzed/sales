@extends('emails.layouts.email')

@section('content')
    <h2>Welcome, {{ $billerData['name'] ?? 'New Biller' }}!</h2>
    
    <p>Congratulations on joining our team as a biller. We're excited to have you on board!</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Your Account Details</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Name:</span>
        <span class="info-value">{{ $billerData['name'] ?? 'N/A' }}</span>
    </div>
    
    @if(isset($billerData['company_name']))
    <div class="info-row">
        <span class="info-label">Company:</span>
        <span class="info-value">{{ $billerData['company_name'] }}</span>
    </div>
    @endif
    
    @if(isset($billerData['email']))
    <div class="info-row">
        <span class="info-label">Email:</span>
        <span class="info-value">{{ $billerData['email'] }}</span>
    </div>
    @endif
    
    @if(isset($billerData['phone_number']))
    <div class="info-row">
        <span class="info-label">Phone:</span>
        <span class="info-value">{{ $billerData['phone_number'] }}</span>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">We hope that you will be dedicated and honest with your work. If you have any questions or need assistance, please don't hesitate to reach out to us.</p>
    
    <p style="margin-top:16px;"><strong>Thank you for being part of our team!</strong></p>
@endsection

