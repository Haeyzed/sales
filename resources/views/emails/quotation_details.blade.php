@extends('emails.layouts.email')

@section('content')
    <h2>Quotation Details</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $quotationData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        @if(isset($quotationData['quotation_status']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ strtolower($quotationData['quotation_status']) }}">
                    {{ ucfirst($quotationData['quotation_status']) }}
                </span>
            </span>
        </div>
        @endif
    </div>
    
    @if(isset($quotationData['products']) && count($quotationData['products']) > 0)
    <h3>Quotation Items</h3>
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
            @foreach($quotationData['products'] as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product }}</td>
                <td>{{ ($quotationData['qty'][$key] ?? 0) . ' ' . ($quotationData['unit'][$key] ?? '') }}</td>
                <td>
                    @if(isset($quotationData['total'][$key]) && isset($quotationData['qty'][$key]) && $quotationData['qty'][$key] > 0)
                        {{ number_format((float)($quotationData['total'][$key] / $quotationData['qty'][$key]), $generalSetting->decimal ?? 2, '.', '') }}
                    @else
                        0.00
                    @endif
                </td>
                <td>{{ number_format((float)($quotationData['total'][$key] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>{{ $quotationData['total_qty'] ?? 0 }}</strong></td>
                <td></td>
                <td><strong>{{ number_format((float)($quotationData['total_price'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($quotationData['order_tax']) && $quotationData['order_tax'] > 0)
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Order Tax</strong></td>
                <td><strong>{{ number_format((float)$quotationData['order_tax'], $generalSetting->decimal ?? 2, '.', '') }} ({{ $quotationData['order_tax_rate'] ?? 0 }}%)</strong></td>
            </tr>
            @endif
            @if(isset($quotationData['order_discount']) && $quotationData['order_discount'] > 0)
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Order Discount</strong></td>
                <td><strong>{{ number_format((float)$quotationData['order_discount'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
            @if(isset($quotationData['shipping_cost']) && $quotationData['shipping_cost'] > 0)
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Shipping Cost</strong></td>
                <td><strong>{{ number_format((float)$quotationData['shipping_cost'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>
                <td><strong>{{ number_format((float)($quotationData['grand_total'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Thank you for your interest! If you have any questions about this quotation, please don't hesitate to contact us.</p>
@endsection

