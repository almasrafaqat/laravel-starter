<x-mail::message :footerData="$footerData">
# Invoice from {{ $invoice['company']['name'] ?? '' }}

Dear {{ $invoice['customer']['name'] ?? 'Valued Customer' }},

Thank you for your business. Please find your invoice details below:

<x-mail::panel>
## Invoice Summary
**Invoice Number:** {{ $invoice['invoice_number'] ?? 'N/A' }}  
**Title:** {{ $invoice['title'] ?? 'N/A' }}  
**Date:** {{ $invoice['date'] ?? date('Y-m-d') }}  
**Valid Until:** {{ $invoice['valid_until'] ?? 'N/A' }}
</x-mail::panel>

<x-mail::panel>
## Financial Details
**Subtotal:** {{ $invoice['currency'] ?? 'USD' }} {{ number_format($invoice['item_subtotal'] ?? $invoice['subtotal'], 2) }}

@if(!empty($invoice['discounts']))
    @foreach($invoice['discounts'] as $discount)
        **Discount ({{ $discount['discount_name'] ?? '' }} {{ $discount['discount_type'] === 'percentage' ? $discount['discount_value'] . '%' : $invoice['currency'] . ' ' . number_format($discount['discount_value'], 2) }}):** -{{ $invoice['currency'] }} {{ number_format($discount['discount_amount'], 2) }}
    @endforeach
@endif

@if(!empty($invoice['taxes']))
    @foreach($invoice['taxes'] as $tax)
        **Tax ({{ $tax['tax_name'] ?? '' }} {{ $tax['tax_type'] === 'percentage' ? $tax['tax_value'] . '%' : $invoice['currency'] . ' ' . number_format($tax['tax_value'], 2) }}):** {{ $invoice['currency'] }} {{ number_format($tax['tax_amount'], 2) }}
    @endforeach
@endif

**Total Amount:** {{ $invoice['currency'] }} {{ number_format($invoice['grand_total'] ?? $invoice['total'], 2) }}

@if(isset($invoice['payment_status']))
## Payment Information
**Status:** {{ ucfirst($invoice['payment_status']) }}
@if(isset($invoice['amount_paid']) && $invoice['amount_paid'] > 0)
**Amount Paid:** {{ $invoice['currency'] }} {{ number_format($invoice['amount_paid'], 2) }}
**Balance Due:** {{ $invoice['currency'] }} {{ number_format($invoice['balance_due'], 2) }}
@endif
@endif
</x-mail::panel>

<x-mail::table>
| Item      | Description   | Quantity | Price | Total |
| --------- | ------------- | -------- | ----- | ----- |
@foreach($invoice['items'] as $item)
| {{ $item['name'] }} | {{ $item['description'] ?? '-' }} | {{ $item['quantity'] }} | {{ $invoice['currency'] }} {{ number_format($item['price'], 2) }} | {{ $invoice['currency'] }} {{ number_format($item['total'], 2) }} |
@endforeach
</x-mail::table>

@if(!empty($invoice['notes']))
<x-mail::panel>
**Notes:** {{ $invoice['notes'] }}
</x-mail::panel>
@endif

For a detailed breakdown, please refer to the attached PDF.

If you have any questions, contact us at {{ $invoice['company']['email'] ?? '' }} or {{ $invoice['company']['phone'] ?? '' }}.

Best regards,  
{{ $invoice['company']['name'] ?? '' }} Team

<x-mail::subcopy>
This invoice is valid until {{ $invoice['valid_until'] ?? 'N/A' }}. After this date, prices and availability may change.
</x-mail::subcopy>
</x-mail::message>
