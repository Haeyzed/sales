@extends('emails.layouts.email')

@section('content')
    <h2>Delivery Challan</h2>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Reference No:</span>
            <span class="info-value"><strong>{{ $challanData['reference_no'] ?? 'N/A' }}</strong></span>
        </div>
        
        @if(isset($challanData['status']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ strtolower($challanData['status']) }}">
                    {{ ucfirst($challanData['status']) }}
                </span>
            </span>
        </div>
        @endif
        
        @if(isset($challanData['courier']))
        <div class="info-row" style="margin-top:12px;">
            <span class="info-label">Courier:</span>
            <span class="info-value">{{ $challanData['courier'] }}</span>
        </div>
        @endif
    </div>
    
    @if(isset($challanData['packing_slips']) && count($challanData['packing_slips']) > 0)
    <h3>Packing Slips</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($challanData['packing_slips'] as $slip)
            <tr>
                <td>{{ $slip['reference'] ?? 'N/A' }}</td>
                <td>{{ $challanData['currency'] ?? '' }} {{ number_format((float)($slip['amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($slip['status'] ?? 'pending') }}">
                        {{ ucfirst($slip['status'] ?? 'Pending') }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align:right;"><strong>Total</strong></td>
                <td><strong>{{ $challanData['currency'] ?? '' }} {{ number_format((float)($challanData['total_amount'] ?? 0), $generalSetting->decimal ?? 2, '.', '') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif
    
    <div class="divider"></div>
    
    <p style="margin-top:24px;">This challan contains all the delivery information. Please keep this for your records.</p>
@endsection

