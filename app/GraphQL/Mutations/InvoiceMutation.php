<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceMutation
{
    public function createInvoice($_, array $args)
    {

        $user = Auth::user();
        // return response()->json($args);
        $input = $args['input'];
        $invoice = Invoice::create([
            'title' => $input['title'],
            'invoice_number' => $input['invoice_number'] ?? 'INV-0010',
            'customer_id' => $input['customer_id'] ?? 1,
            'company_id' => $input['company_id'] ?? 1,
            'creator_id' => $user->id,
            // ...other fields
        ]);
        return $invoice;
        // Create items
        // foreach ($args['items'] ?? [] as $itemData) {
        //     $invoice->items()->create($itemData);
        // }

        // // Create discounts
        // foreach ($args['discounts'] ?? [] as $discountData) {
        //     $invoice->discounts()->create($discountData);
        // }

        // // Create reminders
        // foreach ($args['reminders'] ?? [] as $reminderData) {
        //     $invoice->reminders()->create($reminderData);
        // }

        // // Create links
        // foreach ($args['links'] ?? [] as $linkData) {
        //     $invoice->links()->create($linkData);
        // }

        // // Create charities
        // foreach ($args['charities'] ?? [] as $charityData) {
        //     $invoice->charities()->create($charityData);
        // }

        // // Create checklistables
        // foreach ($args['checklistables'] ?? [] as $checklistableData) {
        //     $invoice->checklistables()->create($checklistableData);
        // }

    }
}
