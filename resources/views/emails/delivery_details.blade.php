@extends('emails.layouts.email')

@section('content')
    <h2>Delivery Details</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $deliveryData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        @if(isset($deliveryData['status']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ strtolower($deliveryData['status']) }}">
                    {{ ucfirst($deliveryData['status']) }}
                </span>
            </span>
        </div>
        @endif
    </div>
    
    @if(isset($deliveryData['address']))
    <h3>Delivery Address</h3>
    <div class="info-box">
        <p style="margin:0;white-space:pre-line;">{{ $deliveryData['address'] }}</p>
    </div>
    @endif
    
    @if(isset($deliveryData['courier']))
    <div class="info-row" style="margin-top:16px;">
        <span class="info-label">Courier:</span>
        <span class="info-value">{{ $deliveryData['courier'] }}</span>
    </div>
    @endif
    
    @if(isset($deliveryData['delivered_by']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Delivered By:</span>
        <span class="info-value">{{ $deliveryData['delivered_by'] }}</span>
    </div>
    @endif
    
    @if(isset($deliveryData['recieved_by']))
    <div class="info-row" style="margin-top:12px;">
        <span class="info-label">Received By:</span>
        <span class="info-value">{{ $deliveryData['recieved_by'] }}</span>
    </div>
    @endif
    
    @if(isset($deliveryData['note']))
    <div class="divider"></div>
    <p><strong>Note:</strong> {{ $deliveryData['note'] }}</p>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Thank you for your order! We hope you receive your delivery soon.</p>
@endsection

