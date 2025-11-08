@extends('emails.layouts.email')

@section('content')
    <h2>Customer Deposit Notification</h2>
    
    <p>Dear {{ $depositData['customer_name'] ?? 'Valued Customer' }},</p>
    
    <p>This is to confirm that a deposit has been made to your account.</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Deposit Information</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Amount:</span>
        <span class="info-value"><strong style="font-size:20px;color:#667eea;">{{ $depositData['currency'] ?? '' }} {{ number_format((float)($depositData['amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></span>
    </div>
    
    @if(isset($depositData['reference_no']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Reference No:</span>
        <span class="info-value">{{ $depositData['reference_no'] }}</span>
    </div>
    @endif
    
    @if(isset($depositData['date']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Date:</span>
        <span class="info-value">{{ $depositData['date'] }}</span>
    </div>
    @endif
    
    @if(isset($depositData['note']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Note:</span>
        <span class="info-value">{{ $depositData['note'] }}</span>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Your current account balance has been updated. Thank you for your business!</p>
@endsection

