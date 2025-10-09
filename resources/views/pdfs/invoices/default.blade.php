<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <title>{{ __('invoices.invoice_title', ['number' => $invoice['invoice_number'] ?? 'N/A']) }}</title>
       <style>
        @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path('fonts/Almarai-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path('fonts/Almarai-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: 'Almarai', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            direction: {{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }};
        }
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
     
      
        .invoice-info {
            display: table-cell;
            vertical-align: top;
            text-align: {{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'right' }};
        }
       
        .invoice-info {
            display: table-cell;
            vertical-align: top;
            text-align: {{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'right' }};
        }
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 12px;
            color: #666;
        }
        /* Rest of your existing styles */
        .info-grid-wrapper {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 5px;
            overflow: hidden;
        }
        .info-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .info-grid td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .info-grid tr:last-child td {
            border-bottom: none;
        }
        .info-grid td:first-child {
            border-left: none;
        }
        .info-grid td:last-child {
            border-right: none;
        }
        .section-title {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 4px;
            border-radius: 3px;
        }
        .product-table {
            width: 100%;

            border-collapse: collapse;
            background-color: #FFFFFF;
            color: #222222;
        }
        .product-table th, .product-table td {
            border-width: 1px;
            border-style: solid;
            border-color: #ddd;
            padding: 3px;
            text-align: {{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'left' }};
        }
        .product-table thead {
            background-color: #FFFFFF;
            color: #222222;
            padding: 3px;
        }
        .number {
            direction: ltr;
            unicode-bidi: embed;
        }
        .number.qty {
            text-align: center;
        }
        .summary {
            width: 300px;
            margin-{{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'left' }}: auto;
        }

        /* Payment information styles */
        .payment-info-section {
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 10px;
            background-color: #f8f9fc;
        }

        .payment-info-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .payment-info-card {
            background-color: white;
            padding: 10px;
            text-align: center;
            border-left: 4px solid #4e73df;
            height: auto;
            margin: 5px;
        }

        .payment-info-title {
            font-size: 14px;
            color: #5a5c69;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .payment-info-value {
            font-size: 16px;
            margin-top: 5px;
        }

        .payment-status-badge {
            float: right;
            margin-top: 0 !important;
        }
        
        /* Responsive adjustments */
        @media print {
            .payment-info-section {
                page-break-inside: avoid;
            }
        }

        .number span {
            color: #888;
            font-size: 11px;
            font-style: italic;
            margin-left: 5px;
        }

        .footer {
            margin-top: 40px;
            padding: 24px 20px 16px 20px;
            background-color: #f5f6fa;
            font-size: 12px;
            text-align: center;
            color: #555;
            border-top: 1px solid #e3e6f0;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
        }

        .footer .footer-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4e73df 0%, #36b9cc 100%);
            margin: 0 auto 16px auto;
            border-radius: 2px;
        }

        .footer .footer-note {
            font-size: 13px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .footer .footer-thankyou {
            font-size: 12px;
            color: #888;
            margin-bottom: 0;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="invoice-header">
            @include('emails.partials.header')
            <div class="invoice-info">
                <div class="invoice-number">
                    {!! __('invoices.invoice_title', ['number' => $invoice->invoice_number]) !!}
                </div>
                <div class="invoice-date">
                    {!! __('invoices.invoice_date') !!}: {{ $invoice->formatted_date }}
                </div>
            </div>
        </div><!--header-->

        <!-- Exporter and Payment Information -->
        <div class="info-grid-wrapper">
            <table class="info-grid">
                <tr>
                    <td width="50%" style="vertical-align: top;">
                        <div class="section-title">{{ __('invoices.exporter_information') }}</div>
                        <div>{{ $invoice->company['name'] ?? '' }}</div>
                        <div>{{ $invoice->company['address'] ?? '' }}</div>
                        <div>{{ $invoice->company['email'] ?? '' }}</div>
                        <div>{{ $invoice->company['phone'] ?? '' }}</div>
                    </td>
                    <td width="50%" style="vertical-align: top;">
                        <div class="section-title">{{ __('invoices.bank_details') }}</div>
                        <div>{{ __('invoices.bank_name') }}: {{ $bankDetails['bank_name'] ?? '' }}</div>
                        <div>{{ __('invoices.bank_title') }}: {{ $bankDetails['account_name'] ?? '' }}</div>
                        <div>{{ __('invoices.bank_iban') }}: <span class="number">{{ $bankDetails['iban'] ?? '' }}</span></div>
                        <div>{{ __('invoices.swift_code') }}: {{ $bankDetails['swift_code'] ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
         <!-- Payment Information Section -->
        @if(isset($invoice['payment_status']) || isset($invoice['amount_paid']) || isset($invoice['balance_due']))
            @php
                $statusColors = [
                    'pending' => '#f6c23e',
                    'unpaid' => '#e74a3b',
                    'partial' => '#36b9cc',
                    'paid' => '#1cc88a',
                    'overdue' => '#e74a3b',
                    'cancelled' => '#858796'
                ];
                $statusColor = $statusColors[$invoice['payment_status']] ?? '#858796';
                $statusLabel = ucfirst($invoice['payment_status']);
            @endphp
            <div class="info-grid-wrapper payment-info-section">
                <div class="section-title">{{ __('invoices.payment_information') }}</div>
                <table class="payment-info-table">
                    <tr>
                        <td width="33%" style="vertical-align: top; padding: 5px;">
                            <div class="payment-info-card">
                                <div class="payment-info-title">{{ __('invoices.payment_status') }}</div>
                                <div class="payment-info-value" style="color: {{ $statusColor }}; font-weight: bold;">
                                    {{ $statusLabel }}
                                </div>
                            </div>
                        </td>
                        <td width="33%" style="vertical-align: top; padding: 5px;">
                            <div class="payment-info-card">
                                <div class="payment-info-title">{{ __('invoices.amount_paid') }}</div>
                                <div class="payment-info-value">
                                    {{ $invoice['currency'] }} {{ number_format($invoice['amount_paid'] ?? 0, 2) }}
                                </div>
                            </div>
                        </td>
                        <td width="33%" style="vertical-align: top; padding: 5px;">
                            <div class="payment-info-card">
                                <div class="payment-info-title">{{ __('invoices.balance_due') }}</div>
                                <div class="payment-info-value" style="font-weight: bold; {{ isset($invoice['remaining']) && $invoice['remaining'] > 0 ? 'color: #e74a3b;' : 'color: #1cc88a;' }}">
                                    {{ $invoice['currency'] }} {{ number_format($invoice['remaining'] ?? 0, 2) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <!-- Shipping and Bank Information -->
        <div class="info-grid-wrapper">
            <table class="info-grid">
                <tr>
                    <td width="50%" style="vertical-align: top;">
                        <div class="section-title">{{ __('invoices.shipping_information') }}</div>

                        <div>{{ __('invoices.shipping_term') }}: {{ $invoice->shipping_term ?? 'DDP' }}</div>
                        <div class="section-title">{{ __('invoices.payment_terms') }}</div>
                        <div>{{ __('invoices.invoice_total_amount') }}: <span class="number">{{ $invoice->currency }} {{ number_format($invoice['grand_total'], 2) }}</span></div>
                    </td>
                    <td width="50%" style="vertical-align: top;">
                        <div class="section-title">{{ __('emails.shipping_address') }}</div>
                        <div style="color: #666; font-size: 14px;">
                            {{ $invoice->customer['name'] }}<br>
                            {{ $invoice->customer['company'] ?? '' }}<br>
                            {{ $invoice->customer['address'] ?? '' }}<br>

                        </div>
                    </td>
                </tr>
            </table>
        </div><!--shipping and bank info-->

        <div class="info-grid-wrapper">
            <!-- Products Table -->
            <div class="section-title">{{ __('invoices.description_of_goods') }}</div>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>{{ __('invoices.item') }}</th>
                        <th>{{ __('invoices.description') }}</th>
                        <th>{{ __('invoices.quantity') }}</th>
                        <th>{{ __('invoices.unit_price') }}</th>
                        <th>{{ __('invoices.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice['Items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                         <td>
                            {{ $item['description'] ?? '' }}
                          
                            @if(!empty($item['discounts']))
                                <br>
                                @foreach($item['discounts'] as $discount)
                                    <span style="color: #e74a3b; font-size: 11px;">
                                        @if($discount['discount_type'] === 'percentage')
                                            Discount: {{ $discount['discount_value'] }}%
                                        @else
                                            Discount: {{ $invoice['currency'] }} {{ number_format($discount['discount_value'], 2) }}
                                        @endif
                                        @if(!empty($discount['discount_name']))
                                            ({{ $discount['discount_name'] }})
                                        @endif
                                        - {{ $invoice['currency'] }} {{ number_format($discount['discount_amount'], 2) }}
                                        - {{ $invoice['currency'] }} {{ number_format($item['subtotal'], 2) }}
                                    </span>
                                @endforeach
                                
                            @endif
                            @if(isset($item['is_excluded_invoice_discount']) && $item['is_excluded_invoice_discount'])
                                @if(!empty($item['description']))
                                    <br>
                                @endif
                                <span style="color: #888; font-size: 11px; font-style: italic;">
                                    {{ __('invoices.item_excluded_discount') }}
                                </span>
                            @endif
                        </td>
                        <td class="number qty">{{ $item['quantity'] }}</td>
                        <td class="number">{{ $invoice['currency'] }} {{ number_format($item['price'], 2) }}</td>
                        <td class="number">
                            {{ $invoice['currency'] }} {{ number_format($item['total'], 2) }}

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!--items-->

        <div class="info-grid-wrapper">
            <!-- Summary -->
            <table class="summary">
                
             
                    
                <tr>
                    <td>{{ __('invoices.subtotal') }}:</td>
                    <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['item_subtotal'], 2) }}</td>
                </tr>
                 <tr>
                    <td>{{ __('invoices.total_quantity') }}:</td>
                    <td class="number">{{ $invoice['total_quantity'] }}</td>
                </tr>

                   @if(!empty($invoice['discounts']))
                    <tr>
                        <td>{{ __('invoices.discounts') }}:</td>
                        <td class="number">
                            @foreach($invoice['discounts'] as $discount)
                                @php
                                    $typeLabel = $discount['discount_type'] === 'percentage'
                                        ? $discount['discount_value'] . '%'
                                        : $invoice['currency'] . ' ' . number_format($discount['discount_value'], 2);
                                @endphp
                                @if(!empty($discount['discount_name']))
                                    ({{ $discount['discount_name'] }})
                                @endif
                                ({{ $typeLabel }})
                                - {{ $invoice['currency'] }} {{ number_format($discount['discount_amount'], 2) }}
                                <br>
                            @endforeach
                        </td>
                    </tr>
                 @endif
               
                @if($invoice['customer']['credit_balance'] > 0)
                <tr>
                    <td>{{ __('invoices.credit_balance') }}:</td>
                    <td class="number">
                        {{ $invoice['currency'] }} {{ number_format($invoice['customer']['credit_balance'], 2) }}<br>
                        <span style="color: #888; font-size: 11px; font-style: italic;">
                            {{ __('invoices.credit_note') }}
                        </span>
                    </td>
                </tr>
                @endif
                @if(!empty($invoice['taxes']))
                    @foreach($invoice['taxes'] as $tax)
                        <tr>
                            <td>
                                {{ __('invoices.tax') }}
                                @if(!empty($tax['tax_name']))
                                    ({{ $tax['tax_name'] }})
                                @endif
                                :
                            </td>
                            <td class="number">
                                {{ $invoice['currency'] }} {{ number_format($tax['tax_amount'], 2) }}
                                <span style="color: #888; font-size: 11px;">
                                    @if($tax['tax_type'] === 'percentage')
                                        ({{ $tax['tax_value'] }}%)
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endif
               

              

            <tr>
                <td>{{ __('invoices.grand_total') }}:</td>
                <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['grand_total'], 2) }}</td>
            </tr>


            </table>
        </div><!--summary-->

          <!-- Footer -->
       <div class="footer">
            <div class="footer-divider"></div>
            @if(!empty($invoice['notes']))
                <div class="footer-note">{{ $invoice['notes'] }}</div>
            @endif
            <div class="footer-thankyou">
                {{ __('invoices.thank_you_note', ['company' => $invoice->company['name'] ?? '']) }}
            </div>
        </div>

    </div><!--container-->

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Almarai", "normal");
            $size = 10;
            $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
            $x = 520;
            $y = 820;
            $pdf->text($x, $y, $pageText, $font, $size);
        ');
    }
</script>

</body>

</html>