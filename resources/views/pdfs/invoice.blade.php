<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ __('invoices.title', ['number' => $invoice->invoice_number]) }}</title>
    <style>
        @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path(' fonts/Almarai-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Almarai';
            src: url('{{ public_path(' fonts/Almarai-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'Almarai', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;

            direction: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'rtl': 'ltr'
                }
            }

            ;
        }

        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;

            align-items: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'flex-start': 'flex-start'
                }
            }

            ;

            direction: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'ltr': 'ltr'
                }
            }

            ;
        }

        .logo {
            display: flex;
            align-items: start;
            margin-bottom: 1px;

        }

        .logo img {
            width: 200px;
            height: 70px;
            object-fit: cover;
        }

        .slogan-wrapper {
            position: relative;
            padding: 1px 0;
            width: 100%;
            max-width: 200px;
        }

        .company-slogan {
            font-size: 12px;
            color: #444;

            font-family: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? "'Almarai'": "'Jersey 15'"
                }
            }

            ,
            sans-serif;

            letter-spacing: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? '0': '1.5px'
                }
            }

            ;

            text-transform: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'none': 'uppercase'
                }
            }

            ;
            position: relative;
            display: inline-block;
            padding-bottom: 4px;

            text-align: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'right': 'left'
                }
            }

            ;

            font-style: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'normal': 'italic'
                }
            }

            ;
        }

        .company-slogan::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;

            background: linear-gradient(to {
                        {
                        in_array(app()->getLocale(), ['ar']) ? 'left' : 'right'
                    }
                }

                , #ff0000 0%, rgba(255, 0, 0, 0.7) 50%, rgba(255, 0, 0, 0) 100%);
        }

        [dir="rtl"] .company-slogan {
            font-family: 'Almarai', sans-serif;
            letter-spacing: 0;
            font-style: normal;
            text-transform: none;
        }

        @media print {
            .company-slogan {
                color: #333;
            }

            .company-slogan::after {
                background: linear-gradient(to {
                            {
                            in_array(app()->getLocale(), ['ar']) ? 'left' : 'right'
                        }
                    }

                    , rgba(200, 0, 0, 0.8) 0%, rgba(200, 0, 0, 0.3) 50%, rgba(200, 0, 0, 0) 100%);
            }
        }

        .invoice-info {
            display: table-cell;
            vertical-align: top;

            text-align: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'right': 'right'
                }
            }

            ;
        }

        .invoice-info {
            display: table-cell;
            vertical-align: top;

            text-align: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'right': 'right'
                }
            }

            ;
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

        .product-table th,
        .product-table td {
            border-width: 1px;
            border-style: solid;
            border-color: #ddd;
            padding: 3px;

            text-align: {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'right': 'left'
                }
            }

            ;
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

            margin- {
                    {
                    in_array(app()->getLocale(), ['ar']) ? 'right': 'left'
                }
            }

            : auto;
        }

        .footer {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ public_path('images/logos/newyou.png') }}" alt="{{ config('app.company_name') }}">
                </div>
            </div>
            <div class="slogan-wrapper">
                <div class="slogan" style="
                    font-size: {{in_array(app()->getLocale(), ['ar']) ? '16px' : '12px' }}; 
                    color: #666;
                    margin-top: 5px;
                    text-align: {{ in_array(app()->getLocale(), ['ar']) ? 'right' : 'left' }};
                    direction: {{ in_array(app()->getLocale(), ['ar']) ?  'rtl' : 'ltr' }};
                    
                    ">
                    {{ __('messages.company_slogan') }}
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-number">
                    {!! __('invoices.invoice_title', ['number' => $invoice->invoice_number]) !!}
                </div>
                <div class="invoice-date">
                    {!! __('invoices.invoice_date') !!}: {{ $invoice->created_at }}
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

        <!-- Shipping and Bank Information -->
        <div class="info-grid-wrapper">
            <table class="info-grid">
                <tr>
                    <td width="50%" style="vertical-align: top;">
                        <div class="section-title">{{ __('invoices.shipping_information') }}</div>

                        <div>{{ __('invoices.shipping_term') }}: {{ $invoice->shipping_term ?? 'DDP' }}</div>
                        <div class="section-title">{{ __('invoices.payment_terms') }}</div>
                        <div>{{ __('invoices.invoice_total_amount') }}: <span class="number">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span></div>
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
                        </td>
                        <td class="number qty">{{ $item['quantity'] }}</td>
                        <td class="number">{{ $invoice['currency'] }} {{ number_format($item['price'], 2) }}</td>
                        <td class="number">{{ $invoice['currency'] }} {{ number_format($item['subtotal'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!--items-->

        <div class="info-grid-wrapper">
            <!-- Summary -->
            <table class="summary">
                <tr>
                    <td>{{ __('invoices.amount_paid') }}:</td>
                    <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['paid_amount'], 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('invoices.balance_due') }}:</td>
                    <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['balance_due'], 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('invoices.subtotal') }}:</td>
                    <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['total'], 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('invoices.total_quantity') }}:</td>
                    <td class="number">{{ $invoice['total_quantity'] }}</td>
                </tr>
                @if($invoice['customer']['credit_balance'] > 0)
                <tr>
                    <td>{{ __('invoices.credit_balance') }}:</td>
                    <td class="number">{{ $invoice['currency'] }} {{ number_format($invoice['customer']['credit_balance'], 2) }}</td>
                </tr>
                @endif


            </table>
        </div><!--summary-->

    </div><!--container-->

</body>

</html>