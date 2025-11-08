@extends('emails.layouts.email')

@section('content')
    <h2>System Log Message</h2>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Log Information</p>
    </div>
    
    @if(isset($logData['title']))
    <div class="info-row">
        <span class="info-label">Title:</span>
        <span class="info-value"><strong>{{ $logData['title'] }}</strong></span>
    </div>
    @endif
    
    @if(isset($logData['type']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Type:</span>
        <span class="info-value">
            <span class="status-badge status-{{ strtolower($logData['type']) }}">
                {{ ucfirst($logData['type']) }}
            </span>
        </span>
    </div>
    @endif
    
    @if(isset($logData['date']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Date:</span>
        <span class="info-value">{{ $logData['date'] }}</span>
    </div>
    @endif
    
    @if(isset($logData['message']))
    <div class="divider"></div>
    <h3>Message</h3>
    <div class="info-box">
        <p style="margin:0;white-space:pre-line;">{{ $logData['message'] }}</p>
    </div>
    @endif
    
    @if(isset($logData['details']))
    <div class="divider"></div>
    <h3>Details</h3>
    <div class="info-box">
        <pre style="margin:0;font-family:inherit;white-space:pre-wrap;">{{ $logData['details'] }}</pre>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">This is an automated system notification. Please review the log details above.</p>
@endsection

