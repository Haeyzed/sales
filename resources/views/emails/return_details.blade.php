@extends('emails.layouts.email')

@section('content')
    <h2>Return Details</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $returnData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        @if(isset($returnData['sale_reference']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Sale Reference:</span>
            <span class="info-value">{{ $returnData['sale_reference'] }}</span>
        </div>
        @endif
    </div>
    
    @if(isset($returnData['products']) && count($returnData['products']) > 0)
    <h3>Returned Items</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returnData['products'] as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product }}</td>
                <td>{{ ($returnData['qty'][$key] ?? 0) . ' ' . ($returnData['unit'][$key] ?? '') }}</td>
                <td>
                    @if(isset($returnData['total'][$key]) && isset($returnData['qty'][$key]) && $returnData['qty'][$key] > 0)
                        {{ number_format((float)($returnData['total'][$key] / $returnData['qty'][$key]), $generalSetting->decimal ?? 2, '.', '') }}
                    @else
                        0.00
                    @endif
                </td>
                <td>{{ number_format((float)($returnData['total'][$key] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>{{ $returnData['total_qty'] ?? 0 }}</strong></td>
                <td></td>
                <td><strong>{{ number_format((float)($returnData['total_price'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($returnData['order_tax']) && $returnData['order_tax'] > 0)
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Order Tax</strong></td>
                <td><strong>{{ number_format((float)$returnData['order_tax'], $generalSetting->decimal ?? 2, '.', '') }} ({{ $returnData['order_tax_rate'] ?? 0 }}%)</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>
                <td><strong>{{ number_format((float)($returnData['grand_total'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif
    
    @if(isset($returnData['return_note']))
    <div class="divider"></div>
    <p><strong>Return Note:</strong> {{ $returnData['return_note'] }}</p>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Thank you for your return. We'll process your refund as soon as possible.</p>
@endsection

