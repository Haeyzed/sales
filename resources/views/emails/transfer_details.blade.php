@extends('emails.layouts.email')

@section('content')
    <h2>Transfer Details</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $transferData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        @if(isset($transferData['status']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ strtolower($transferData['status']) }}">
                    {{ ucfirst($transferData['status']) }}
                </span>
            </span>
        </div>
        @endif
    </div>
    
    @if(isset($transferData['from_warehouse']) || isset($transferData['to_warehouse']))
    <h3>Warehouse Information</h3>
    <div class="info-box">
        @if(isset($transferData['from_warehouse']))
        <div class="info-row">
            <span class="info-label">From Warehouse:</span>
            <span class="info-value">{{ $transferData['from_warehouse'] }}</span>
        </div>
        @endif
        
        @if(isset($transferData['to_warehouse']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">To Warehouse:</span>
            <span class="info-value">{{ $transferData['to_warehouse'] }}</span>
        </div>
        @endif
    </div>
    @endif
    
    @if(isset($transferData['products']) && count($transferData['products']) > 0)
    <h3>Transferred Items</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Cost</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transferData['products'] as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product }}</td>
                <td>{{ $transferData['qty'][$key] ?? 0 }}</td>
                <td>{{ number_format((float)($transferData['net_unit_cost'][$key] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
                <td>{{ number_format((float)($transferData['total'][$key] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
                <td><strong>{{ $transferData['total_qty'] ?? 0 }}</strong></td>
                <td></td>
                <td><strong>{{ number_format((float)($transferData['total_cost'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @if(isset($transferData['shipping_cost']) && $transferData['shipping_cost'] > 0)
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Shipping Cost</strong></td>
                <td><strong>{{ number_format((float)$transferData['shipping_cost'], $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>
                <td><strong>{{ number_format((float)($transferData['grand_total'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif
    
    @if(isset($transferData['note']))
    <div class="divider"></div>
    <p><strong>Note:</strong> {{ $transferData['note'] }}</p>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">The transfer has been processed. Please verify the items at the destination warehouse.</p>
@endsection

