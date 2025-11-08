@extends('emails.layouts.email')

@section('content')
    <h2>User Account Details</h2>
    
    <p>Dear {{ $userData['name'] ?? 'User' }},</p>
    
    <p>Your user account has been created successfully. Here are your account details:</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Account Information</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Name:</span>
        <span class="info-value">{{ $userData['name'] ?? 'N/A' }}</span>
    </div>
    
    @if(isset($userData['email']))
    <div class="info-row">
        <span class="info-label">Email:</span>
        <span class="info-value">{{ $userData['email'] }}</span>
    </div>
    @endif
    
    @if(isset($userData['phone']))
    <div class="info-row">
        <span class="info-label">Phone:</span>
        <span class="info-value">{{ $userData['phone'] }}</span>
    </div>
    @endif
    
    @if(isset($userData['role']))
    <div class="info-row">
        <span class="info-label">Role:</span>
        <span class="info-value">{{ $userData['role'] }}</span>
    </div>
    @endif
    
    @if(isset($userData['warehouse']))
    <div class="info-row">
        <span class="info-label">Warehouse:</span>
        <span class="info-value">{{ $userData['warehouse'] }}</span>
    </div>
    @endif
    
    @if(isset($userData['password']))
    <div class="divider"></div>
    <div class="info-box" style="background-color:#fff3cd;border-left-color:#ffc107;">
        <p style="margin:0;font-weight:600;color:#856404;">Temporary Password</p>
        <p style="margin:8px 0 0 0;color:#856404;">{{ $userData['password'] }}</p>
        <p style="margin:8px 0 0 0;font-size:12px;color:#856404;">Please change this password after your first login.</p>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">You can now log in to your account and start using the system.</p>
    
    @if(isset($userData['login_url']))
    <p style="margin-top:16px;text-align:center;">
        <a href="{{ $userData['login_url'] }}" class="button">Login to Your Account</a>
    </p>
    @endif
@endsection

