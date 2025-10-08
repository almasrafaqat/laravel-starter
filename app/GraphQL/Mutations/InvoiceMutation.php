<?php

namespace App\GraphQL\Mutations;

use App\Models\Customer;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceMutation
{
    public function createInvoice($_, array $args)
    {
        return InvoiceService::createInvoice($args['input']);
    }

    // public function createInvoice($_, array $args)
    // {


    //     $input = $args['input'];

    //     $input['date'] = isset($input['date']) ? Carbon::parse($input['date'])->format('Y-m-d') : null;
    //     $input['valid_until'] = isset($input['valid_until']) ? Carbon::parse($input['valid_until'])->format('Y-m-d H:i:s') : null;
    //     $input['paid_on'] = isset($input['paid_on']) ? Carbon::parse($input['paid_on'])->format('Y-m-d H:i:s') : null;
    //     $input['timeframe'] = isset($input['timeframe']) ? Carbon::parse($input['timeframe'])->format('Y-m-d H:i:s') : null;



    //     $user = Auth::user();
    //     // Remove arrays before saving
    //     $mainFields = collect($input)->except([
    //         'customers',
    //         'items',
    //         'taxes',
    //         'discounts',
    //         'reminders'
    //     ])->toArray();

    //     // Add required fields not in input
    //     $mainFields['creator_id'] = $user->id;
    //     $mainFields['company_id'] = $input['company_id'] ?? null;

    //     $invoice = Invoice::create($mainFields);


    //     // Create items
    //     foreach ($input['items'] ?? [] as $itemData) {
    //         $invoice->items()->create($itemData);
    //     }

    //     // // Create discounts
    //     foreach ($input['discounts'] ?? [] as $discountData) {
    //         if (
    //             empty($discountData['discount_type']) ||
    //             $discountData['discount_type'] === 'none'
    //         ) {
    //             continue; // Skip this discount
    //         }
    //         $invoice->discounts()->create($discountData);
    //     }


    //     // Create Customers and associate them with the invoice
    //     foreach ($input['customers'] ?? [] as $customerData) {
    //         // If customerData has 'id' or 'customer_id', use existing customer
    //         $customerId = $customerData['id'] ?? $customerData['customer_id'] ?? null;
    //         if ($customerId) {
    //             $customer = Customer::find($customerId);
    //             // Optionally update customer info if needed
    //         } else {
    //             // Create new customer
    //             $customerData['creator_id'] = $user->id;
    //             $customerData['company_id'] = $mainFields['company_id'] ?? null;
    //             $customer = Customer::create($customerData);
    //         }


    //         // Optionally set the first customer as the invoice's main customer
    //         if (!$invoice->customer_id) {
    //             $invoice->customer_id = $customer->id;
    //             $invoice->save();
    //         }

    //         // Attach customer to company (many-to-many)
    //         if (!empty($input['company_id'])) {
    //             $customer->companies()->attach($input['company_id']);
    //         }
    //     }


    //     // Create reminders
    //     foreach ($input['reminders'] ?? [] as $reminderData) {
    //         $invoice->reminders()->create($reminderData);
    //     }


    //     foreach ($input['taxes'] ?? [] as $taxData) {
    //         if (
    //             empty($taxData['tax_type']) ||
    //             $taxData['tax_type'] === 'none'
    //         ) {
    //             continue; // Skip this tax
    //         }
    //         $invoice->taxes()->create($taxData);
    //     }

    //     return $invoice;

    //     // // Create links
    //     // foreach ($args['links'] ?? [] as $linkData) {
    //     //     $invoice->links()->create($linkData);
    //     // }

    //     // // Create charities
    //     // foreach ($args['charities'] ?? [] as $charityData) {
    //     //     $invoice->charities()->create($charityData);
    //     // }

    //     // // Create checklistables
    //     // foreach ($args['checklistables'] ?? [] as $checklistableData) {
    //     //     $invoice->checklistables()->create($checklistableData);
    //     // }

    // }
}
