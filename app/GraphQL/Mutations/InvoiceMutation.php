<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceMutation
{
    public function createInvoice($_, array $args)
    {
        return InvoiceService::createInvoice($args['input']);
    }

    public function sendInvoice($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['id']);
        $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);
        $toEmail = $invoice->customer['email'] ?? 'customer@example.com';
        $result = (new InvoiceService())->sendInvoiceEmail($formattedInvoice, $toEmail);

        return [
            'message' => $result ? 'Invoice sent successfully' : 'Failed to send invoice',
            'success' => (bool)$result,
        ];
    }

    public function downloadInvoice($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['id']);
        $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);

        // Generate and store PDF, return a download URL
        $pdf = (new InvoiceService())->downloadInvoice($formattedInvoice);
        return $downloadInvoice = (new InvoiceService())->downloadInvoice($formattedInvoice);
        $url = ''; // Implement this method to save PDF and return URL
        // $url = $this->storeAndGetUrl($pdf); // Implement this method to save PDF and return URL

        return [
            'url' => $url,
            'success' => (bool)$url,
        ];
    }
}
