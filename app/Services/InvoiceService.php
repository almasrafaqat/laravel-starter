<?php

namespace App\Services;

class InvoiceService
{
    public static function paymentStatus()
    {
        return [
            'pending' => 'Pending',
            'unpaid' => 'Unpaid',
            'partial' => 'Partially Paid',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled'
        ];
    }

    public function saveQuotation($data) {}
}
