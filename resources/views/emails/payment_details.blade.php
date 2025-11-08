@extends('emails.layouts.email')

@section('content')
    <h2>Payment Details</h2>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Sale Reference:</span>
            <span class="info-value"><strong>{{ $paymentData['sale_reference'] ?? 'N/A' }}</strong></span>
        </div>

        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Payment Reference:</span>
            <span class="info-value"><strong>{{ $paymentData['payment_reference'] ?? 'N/A' }}</strong></span>
        </div>

        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Payment Method:</span>
            <span class="info-value">
                <span class="status-badge status-paid">{{ $paymentData['payment_method'] ?? 'N/A' }}</span>
            </span>
        </div>
    </div>

    <h3>Payment Summary</h3>
    <table class="data-table">
        <tbody>
            <tr>
                <td style="width:50%;"><strong>Grand Total:</strong></td>
                <td style="text-align:right;"><strong>{{ $paymentData['currency'] ?? '' }} {{ number_format((float)($paymentData['grand_total'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Paid Amount:</strong></td>
                <td style="text-align:right;"><strong>{{ $paymentData['currency'] ?? '' }} {{ number_format((float)($paymentData['paid_amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($paymentData['due']) && $paymentData['due'] > 0)
            <tr>
                <td><strong>Due Amount:</strong></td>
                <td style="text-align:right;"><strong style="color:#e53e3e;">{{ $paymentData['currency'] ?? '' }} {{ number_format((float)$paymentData['due'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="divider"></div>

    <p style="margin-top:24px;">Thank you for your payment! If you have any questions, please don't hesitate to contact us.</p>
@endsection

