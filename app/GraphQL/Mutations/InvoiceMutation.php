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


    public function updateInvoice($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['id']);
        return InvoiceService::updateInvoice($invoice->id, $args['input']);
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

    public function duplicateInvoice($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['id']);
        return (new InvoiceService())->duplicateInvoice($invoice);
    }
    public function deleteInvoice($_, array $args)
    {
        $invoice = Invoice::find($args['id']);
        (new InvoiceService())->deleteInvoice($invoice);
        return true;
    }

    public function searchCustomers($_, array $args)
    {
        $query = $args['query'];
        $companyId = $args['companyId'];
        $userId = $args['userId'];

        return (new InvoiceService())->searchCustomers($query, $companyId, $userId);
    }
}
