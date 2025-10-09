<?php
namespace App\GraphQL\Queries;

use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceQuery
{
    public function viewInvoice($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['id']);
        return (new InvoiceService())->formatInvoice($invoice);
    }
}