@extends('emails.layouts.email')

@section('content')
    <h2>Sale Details</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $saleData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Sale Status:</span>
            <span class="info-value">
                @if(isset($saleData['sale_status']))
                    @if($saleData['sale_status'] == 1)
                        <span class="status-badge status-completed">Completed</span>
                    @elseif($saleData['sale_status'] == 2)
                        <span class="status-badge status-pending">Pending</span>
                    @else
                        <span class="status-badge status-pending">{{ $saleData['sale_status'] }}</span>
                    @endif
                @else
                    N/A
                @endif
            </span>
        </div>
        
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Payment Status:</span>
            <span class="info-value">
                @if(isset($saleData['payment_status']))
                    @if($saleData['payment_status'] == 1)
                        <span class="status-badge status-pending">Pending</span>
                    @elseif($saleData['payment_status'] == 2)
                        <span class="status-badge status-due">Due</span>
                    @elseif($saleData['payment_status'] == 3)
                        <span class="status-badge status-partial">Partial</span>
                    @else
                        <span class="status-badge status-paid">Paid</span>
                    @endif
                @else
                    N/A
                @endif
            </span>
        </div>
    </div>
    
    @if(isset($saleData['products']) && count($saleData['products']) > 0)
    <h3>Order Items</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                @if(isset($saleData['file']))
                <th>Download</th>
                @endif
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saleData['products'] as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product }}</td>
                @if(isset($saleData['file'][$key]) && $saleData['file'][$key])
                <td><a href="{{ $saleData['file'][$key] }}" style="color:#667eea;text-decoration:none;">Download</a></td>
                @elseif(isset($saleData['file']))
                <td>N/A</td>
                @endif
                <td>{{ ($saleData['qty'][$key] ?? 0) . ' ' . ($saleData['unit'][$key] ?? '') }}</td>
                <td>
                    @if(isset($saleData['total'][$key]) && isset($saleData['qty'][$key]) && $saleData['qty'][$key] > 0)
                        {{ number_format((float)($saleData['total'][$key] / $saleData['qty'][$key]), $generalSetting->decimal ?? 2, '.', '') }}
                    @else
                        0.00
                    @endif
                </td>
                <td>{{ number_format((float)($saleData['total'][$key] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 4 : 3 }}" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>{{ $saleData['total_qty'] ?? 0 }}</strong></td>
                <td><strong>{{ number_format((float)($saleData['total_price'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($saleData['order_tax']) && $saleData['order_tax'] > 0)
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Order Tax</strong></td>
                <td><strong>{{ number_format((float)$saleData['order_tax'], $generalSetting->decimal ?? 2, '.', '') }} ({{ $saleData['order_tax_rate'] ?? 0 }}%)</strong></td>
            </tr>
            @endif
            @if(isset($saleData['order_discount']) && $saleData['order_discount'] > 0)
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Order Discount</strong></td>
                <td><strong>{{ number_format((float)$saleData['order_discount'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
            @if(isset($saleData['shipping_cost']) && $saleData['shipping_cost'] > 0)
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Shipping Cost</strong></td>
                <td><strong>{{ number_format((float)$saleData['shipping_cost'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Grand Total</strong></td>
                <td><strong>{{ number_format((float)($saleData['grand_total'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Paid Amount</strong></td>
                <td><strong>{{ number_format((float)($saleData['paid_amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($saleData['grand_total']) && isset($saleData['paid_amount']))
            <tr>
                <td colspan="{{ isset($saleData['file']) ? 5 : 4 }}" style="text-align:right;"><strong>Due Amount</strong></td>
                <td><strong>{{ number_format((float)($saleData['grand_total'] - $saleData['paid_amount']), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
        </tfoot>
    </table>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">Thank you for your business! If you have any questions about this sale, please don't hesitate to contact us.</p>
@endsection

