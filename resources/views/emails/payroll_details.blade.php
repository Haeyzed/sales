@extends('emails.layouts.email')

@section('content')
    <h2>Payroll Details</h2>
    
    <p>Dear {{ $payrollData['employee_name'] ?? 'Employee' }},</p>
    
    <p>This is to confirm your payroll payment details.</p>
    
    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Payment Information</p>
    </div>
    
    <div class="info-row">
        <span class="info-label">Reference No:</span>
        <span class="info-value"><strong>{{ $payrollData['reference_no'] ?? 'N/A' }}</strong></span>
    </div>
    
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Amount:</span>
        <span class="info-value"><strong style="font-size:20px;color:#667eea;">{{ $payrollData['currency'] ?? '' }} {{ number_format((float)($payrollData['amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></span>
    </div>
    
    @if(isset($payrollData['paying_method']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Payment Method:</span>
        <span class="info-value">{{ ucfirst($payrollData['paying_method']) }}</span>
    </div>
    @endif
    
    @if(isset($payrollData['date']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Payment Date:</span>
        <span class="info-value">{{ $payrollData['date'] }}</span>
    </div>
    @endif
    
    @if(isset($payrollData['note']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Note:</span>
        <span class="info-value">{{ $payrollData['note'] }}</span>
    </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Thank you for your hard work and dedication!</p>
@endsection

