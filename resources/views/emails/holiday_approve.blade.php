@extends('emails.layouts.email')

@section('content')
    <h2>Holiday Request Approved</h2>

    <p>Dear {{ $holidayData['user_name'] ?? 'Employee' }},</p>

    <p>We're pleased to inform you that your holiday/leave request has been <strong style="color:#22543d;">approved</strong>.</p>

    <div class="info-box">
        <p style="margin:0;font-size:18px;font-weight:600;color:#667eea;">Leave Details</p>
    </div>

    <div class="info-row">
        <span class="info-label">From Date:</span>
        <span class="info-value"><strong>{{ $holidayData['from_date'] ?? 'N/A' }}</strong></span>
    </div>

    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">To Date:</span>
        <span class="info-value"><strong>{{ $holidayData['to_date'] ?? 'N/A' }}</strong></span>
    </div>

    @if(isset($holidayData['total_days']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Total Days:</span>
        <span class="info-value">{{ $holidayData['total_days'] }} day(s)</span>
    </div>
    @endif

    @if(isset($holidayData['note']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Note:</span>
        <span class="info-value">{{ $holidayData['note'] }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <p style="margin-top:24px;">Enjoy your time off! We hope you have a wonderful and restful holiday.</p>

    <p style="margin-top:16px;">If you have any questions or need to make changes, please contact HR.</p>
@endsection

