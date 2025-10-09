<?php

namespace App\Services;

use App\Mail\InvoiceMail;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

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



    private static function handleCustomers($invoice, $input, $user, $mainFields)
    {
        foreach ($input['customers'] ?? [] as $customerData) {
            $customerId = $customerData['id'] ?? $customerData['customer_id'] ?? null;
            if ($customerId) {
                $customer = Customer::find($customerId);
            } else {
                $customerData['creator_id'] = $user->id;
                $customerData['company_id'] = $mainFields['company_id'] ?? null;
                $customer = Customer::create($customerData);
            }
            if (!$invoice->customer_id) {
                $invoice->customer_id = $customer->id;
                $invoice->save();
            }
            if (!empty($input['company_id'])) {
                $customer->companies()->syncWithoutDetaching([$input['company_id']]);
            }
        }
    }

    private static function handleDiscounts($invoice, $discounts, $deleteOld = false)
    {
        if ($deleteOld) {
            $invoice->discounts()->delete();
        }
        foreach ($discounts ?? [] as $discountData) {
            if (
                empty($discountData['discount_type']) ||
                $discountData['discount_type'] === 'none'
            ) {
                continue;
            }
            $invoice->discounts()->create($discountData);
        }
    }

    private static function handleTaxes($invoice, $taxes, $deleteOld = false)
    {
        if ($deleteOld) {
            $invoice->taxes()->delete();
        }
        foreach ($taxes ?? [] as $taxData) {
            if (
                empty($taxData['tax_type']) ||
                $taxData['tax_type'] === 'none'
            ) {
                continue;
            }
            $invoice->taxes()->create($taxData);
        }
    }

    private static function handleItems($invoice, $items, $deleteOld = false)
    {
        if ($deleteOld) {
            $invoice->items()->delete();
        }
        foreach ($items ?? [] as $itemData) {
            // Extract discount data from item if present
            $discountData = null;
            $total = 0;
            $subtotal =  $itemData['subtotal'] ?? 0;
            $discounted_amount =  $itemData['discount_amount'] ?? 0;
            $total =  $subtotal - $discounted_amount;


            if (
                !empty($itemData['discount_type']) &&
                $itemData['discount_type'] !== 'none'
            ) {
                $discountData = [
                    'discount_type' => $itemData['discount_type'],
                    'discount_value' => $itemData['discount_value'],
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'discount_name' => $itemData['discount_name'] ?? '',
                    // Add other discount fields if needed
                ];
            }

            // Remove discount fields from itemData before creating item
            $itemFields = collect($itemData)->except([
                'discount_type',
                'discount_value',
                'discount_amount',
                'discount_name'
            ])->toArray();
            $itemFields['total'] = $total;

            $item = $invoice->items()->create($itemFields);

            // If discount data exists, create discount for this item
            if ($discountData) {
                // You may need to associate the discount with the item, not the invoice
                // If you have item-discounts relation:
                $item->discounts()->create($discountData);
                // If not, fallback to invoice-level:
                // self::handleDiscounts($invoice, [$discountData]);
            }
        }
    }

    private static function handleReminders($invoice, $reminders, $deleteOld = false)
    {
        if ($deleteOld) {
            $invoice->reminders()->delete();
        }
        foreach ($reminders ?? [] as $reminderData) {
            $invoice->reminders()->create($reminderData);
        }
    }

    public static function createInvoice(array $input)
    {
        $input['date'] = isset($input['date']) ? Carbon::parse($input['date'])->format('Y-m-d') : null;
        $input['valid_until'] = isset($input['valid_until']) ? Carbon::parse($input['valid_until'])->format('Y-m-d H:i:s') : null;
        $input['paid_on'] = isset($input['paid_on']) ? Carbon::parse($input['paid_on'])->format('Y-m-d H:i:s') : null;
        $input['timeframe'] = isset($input['timeframe']) ? Carbon::parse($input['timeframe'])->format('Y-m-d H:i:s') : null;
        $user = Auth::user();
        // Remove arrays before saving
        $mainFields = collect($input)->except([
            'customers',
            'items',
            'taxes',
            'discounts',
            'reminders'
        ])->toArray();

        // Add required fields not in input
        $mainFields['creator_id'] = $user->id;
        $mainFields['company_id'] = $input['company_id'] ?? null;
        $invoice = Invoice::create($mainFields);

        self::handleItems($invoice, $input['items']);
        self::handleDiscounts($invoice, $input['discounts']);
        self::handleCustomers($invoice, $input, $user, $mainFields);
        self::handleReminders($invoice, $input['reminders']);
        self::handleTaxes($invoice, $input['taxes']);

        return $invoice;
    }

    /**
     * Convert invoice items to array format
     */

    public function formatInvoiceItems($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('items')) {
                $invoiceData->load('items');
            }

            $invoiceData['items'] = $invoiceData->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => (float)$item->quantity,
                    'price' => (float)$item->price,
                    'subtotal' => (float)$item->subtotal,
                    'is_discounted' => (bool)$item->is_discounted,
                    'is_excluded_invoice_discount' => (bool)$item->is_excluded_invoice_discount,
                    'is_taxed' => (bool)$item->is_taxed,
                    'is_excluded_invoice_taxed' => (bool)$item->is_excluded_invoice_taxed,
                    'total' => (float)$item->total,
                    'discounts' => $item->discounts->map(function ($discount) {
                        return [
                            'id' => $discount->id,
                            'discount_type' => $discount->discount_type,
                            'discount_value' => (float)$discount->discount_value,
                            'discount_amount' => (float)$discount->discount_amount,
                            'discount_name' => $discount->discount_name,
                        ];
                    })->toArray(),
                ];
            })->toArray();
        }

        $invoiceData['items'] = $invoiceData['items'] ?? [];

        return $invoiceData;
    }

    public function formatInvoiceDiscounts($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('discounts')) {
                $invoiceData->load('discounts');
            }

            $invoiceData['discounts'] = $invoiceData->discounts->map(function ($discount) {
                return [
                    'id' => $discount->id,
                    'discount_type' => $discount->discount_type,
                    'discount_value' => (float)$discount->discount_value,
                    'discount_amount' => (float)$discount->discount_amount,
                    'discount_name' => $discount->discount_name,
                ];
            })->toArray();
        }

        $invoiceData['discounts'] = $invoiceData['discounts'] ?? [];

        return $invoiceData;
    }

    public function formatInvoiceTaxes($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('taxes')) {
                $invoiceData->load('taxes');
            }

            $invoiceData['taxes'] = $invoiceData->taxes->map(function ($tax) {
                return [
                    'id' => $tax->id,
                    'tax_type' => $tax->tax_type,
                    'tax_value' => (float)$tax->tax_value,
                    'tax_amount' => (float)$tax->tax_amount,
                    'tax_name' => $tax->tax_name,
                ];
            })->toArray();
        }

        $invoiceData['taxes'] = $invoiceData['taxes'] ?? [];

        return $invoiceData;
    }

    public function formatInvoiceReminders($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('reminders')) {
                $invoiceData->load('reminders');
            }

            $invoiceData['reminders'] = $invoiceData->reminders->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'days_before' => (int)$reminder->days_before,
                    'subject' => $reminder->subject,
                    'body' => $reminder->body,
                    'is_sent' => (bool)$reminder->is_sent,
                ];
            })->toArray();
        }

        $invoiceData['reminders'] = $invoiceData['reminders'] ?? [];

        return $invoiceData;
    }

    public function formatInvoiceCustomer($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('customer')) {
                $invoiceData->load('customer');
            }
            $invoiceData['customer'] = $invoiceData->customer ? [
                'id' => $invoiceData->customer->id,
                'name' => $invoiceData->customer->name,
                'company' => $invoiceData->customer->company,
                'email' => $invoiceData->customer->email,
                'cc' => $invoiceData->customer->cc,
                'bcc' => $invoiceData->customer->bcc,
                'phone' => $invoiceData->customer->phone,
                'address' => $invoiceData->customer->address,
                'credit_balance' => $invoiceData->customer->credit_balance,
            ] : null;
        }
        $invoiceData['customer'] = $invoiceData['customer'] ?? [];

        return $invoiceData;
    }

    public function formatInvoiceCreator($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('creator')) {
                $invoiceData->load('creator');
            }
            $invoiceData['creator'] = $invoiceData->creator ? [
                'id' => $invoiceData->creator->id,
                'name' => $invoiceData->creator->name,
                'email' => $invoiceData->creator->email,
            ] : null;
        }
        $invoiceData['creator'] = $invoiceData['creator'] ?? [];
        return $invoiceData;
    }

    public function formatCompany($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            if (!$invoiceData->relationLoaded('company')) {
                $invoiceData->load('company.mailSettings');
            }
            $invoiceData['company'] = $invoiceData->company ? [
                'id' => $invoiceData->company->id,
                'name' => $invoiceData->company->name,
                'email' => $invoiceData->company->email,
                'phone' => $invoiceData->company->phone,
                'address' => $invoiceData->company->address,
                'website' => $invoiceData->company->website,
                'logo' => $invoiceData->company->logo,
                'slogan' => "Best Solutions for Your Business", // $invoiceData->company->slogan,
                'tax_number' => $invoiceData->company->tax_number,
                'registration_number' => $invoiceData->company->registration_number,
                'country' => $invoiceData->company->country,
                'state' => $invoiceData->company->state,
                'city' => $invoiceData->company->city,
                'zip_code' => $invoiceData->company->zip_code,
                'description' => $invoiceData->company->description,
                'language' => $invoiceData->company->language,
                'currency' => $invoiceData->company->currency,
                'footerData' => [
                    'support_email' => $invoiceData->company->email,
                    'support_phone' => $invoiceData->company->phone,
                    'company_name' => $invoiceData->company->name,
                    'support_url' => $invoiceData->company->website,
                    'address' => $invoiceData->company->address,
                    "facebook_url" => 'www.facebook.com',
                    "twitter_url" => 'www.twitter.com',
                    "instagram_url" => 'www.instagram.com',
                    "whatsapp_number" => '1234567890',
                    'footer_text' => 'Thank you for your business!',
                    // 'twitter_url' => $invoiceData->company->twitter_url,
                    // 'facebook_url' => $invoiceData->company->facebook_url,
                    // 'instagram_url' => $invoiceData->company->instagram_url,
                    // 'whatsapp_number' => $invoiceData->company->whatsapp_number,
                    // 'footer_text' => $invoiceData->company->footer_text,
                ]

            ] : null;
        }
        $invoiceData['company'] = $invoiceData['company'] ?? [];
        return $invoiceData;
    }

    public function formatInvoice($invoiceData)
    {
        if ($invoiceData instanceof \Illuminate\Database\Eloquent\Model) {
            $invoiceData = $this->formatInvoiceItems($invoiceData);
            $invoiceData = $this->formatInvoiceDiscounts($invoiceData);
            $invoiceData = $this->formatInvoiceTaxes($invoiceData);
            $invoiceData = $this->formatInvoiceReminders($invoiceData);
            $invoiceData = $this->formatInvoiceCustomer($invoiceData);
            $invoiceData = $this->formatInvoiceCreator($invoiceData);
            $invoiceData = $this->formatCompany($invoiceData);
            $totalQuantity = array_sum(array_column($invoiceData['items'], 'quantity'));
            $subtotal = array_sum(array_column($invoiceData['items'], 'total'));
            $invoiceData['total_quantity'] = $totalQuantity;
            $invoiceData['item_subtotal'] = (float)$subtotal;
            $total = $invoiceData['total'] ?? 0;


            // Calculate total tax amount
            $taxTotal = !empty($invoiceData['taxes'])
                ? array_sum(array_column($invoiceData['taxes'], 'tax_amount'))
                : 0;

            // Calculate grand total (subtotal + tax)
            $grandTotal = $total + $taxTotal;
            $invoiceData['grand_total'] = round($grandTotal, 2);

            $remaining = $grandTotal - ($invoiceData['amount_paid'] ?? 0);
            $invoiceData['remaining'] = round($remaining, 2);
        }
        return $invoiceData;
    }

    public function downloadInvoice($invoiceData)
    {
        $viewData = [
            'invoice' => $invoiceData,
            'bankDetails' => $this->getBankDetail(),
            'isPdf' => true,
        ];

        $html = view('pdfs.invoices.default', $viewData)->render();
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'Almarai',
            'isPhpEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'fontDir' => public_path('fonts'),
            'fontCache' => storage_path('app/dompdf/fonts'),
            'tempDir' => storage_path('app/dompdf/temp'),
            'logOutputFile' => storage_path('logs/dompdf.htm'),
        ]);

        $filename = 'invoice_' . ($invoiceData['title'] ? $invoiceData['title'] : $invoiceData['id']) . '.pdf';

        return $pdf->download($filename);
    }



    public function sendInvoiceEmail($invoiceData, $toEmail)
    {



        $company = Company::find($invoiceData['company']['id'] ?? null);
        $smtp = $this->getCompanyDefaultSmtp($company);

        if (!$smtp) {
            // Fallback to some default SMTP settings or throw an error
            throw new \Exception('No SMTP settings found for the company.');
        }

        // Set mail config dynamically
        Config::set('mail.mailers.smtp.host', $smtp['host']);
        Config::set('mail.mailers.smtp.port', $smtp['port']);
        Config::set('mail.mailers.smtp.username', $smtp['username']);
        Config::set('mail.mailers.smtp.password', $smtp['password']);
        Config::set('mail.mailers.smtp.encryption', $smtp['encryption']);
        Config::set('mail.from.address', $smtp['from_address']);
        Config::set('mail.from.name', $smtp['from_name']);

        // Handle CC and BCC if provided
        $ccEmails = !empty($invoiceData['customer']['cc']) ? $this->parseEmails($invoiceData['customer']['cc']) : [];
        $bccEmails = !empty($invoiceData['customer']['bcc']) ? $this->parseEmails($invoiceData['customer']['bcc']) : [];

        // Prepare PDF as attachment
        $html = view('pdfs.invoices.default', [
            'invoice' => $invoiceData,
            'bankDetails' => $this->getBankDetail(),
            'isPdf' => true,
        ])->render();
        $pdf = Pdf::loadHTML($html);

        $footerData = $invoiceData['company']['footerData'] ?? [];
        // return $footerData;
        // $pdfContent = $pdf->output();

        Mail::to($toEmail)
            ->cc($ccEmails)
            ->bcc($bccEmails)
            ->send(new InvoiceMail($invoiceData, $pdf, $footerData));


        // Send email
        // Mail::send('emails.invoices.default', ['invoice' => $invoiceData], function (Message $message) use ($toEmail, $pdfContent, $invoiceData, $smtp) {
        //     $message->to($toEmail)
        //         ->subject('Your Invoice')
        //         ->from($smtp['from_address'], $smtp['from_name'])
        //         ->attachData($pdfContent, 'invoice_' . ($invoiceData['title'] ?? $invoiceData['id']) . '.pdf', [
        //             'mime' => 'application/pdf',
        //         ]);
        // });
    }

    public function getCompanyDefaultSmtp($company)
    {
        // Ensure mailSettings relation is loaded
        if ($company && (!$company->relationLoaded('mailSettings'))) {
            $company->load('mailSettings');
        }

        // Get the first/default mail setting
        $mailSetting = $company->mailSettings->first();

        if ($mailSetting) {
            return [
                'host'        => $mailSetting->host,
                'port'        => $mailSetting->port,
                'username'    => $mailSetting->username,
                'password'    => $mailSetting->password,
                'encryption'  => $mailSetting->encryption,
                'from_address' => $mailSetting->from_address,
                'from_name'   => $mailSetting->from_name,
            ];
        }

        return null;
    }

    /**
     * Get bank details for display in quotations
     *
     * @return array
     */
    public function getBankDetail()
    {
        return [
            'bank_name' => config('app.bank_name'),
            'account_name' => config('app.account_name'),
            'account_number' => config('app.account_number'),
            'iban' => config('app.iban'),
            'swift_code' => config('app.swift_code'),
        ];
    }

    /**
     * Parse comma-separated email addresses into an array
     *
     * @param string $emailString
     * @return array
     */
    private function parseEmails($emailString)
    {
        if (empty($emailString)) {
            return [];
        }

        // Split by comma and trim whitespace
        $emails = array_map('trim', explode(',', $emailString));

        // Filter out empty values
        return array_filter($emails, function ($email) {
            return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }


    // public static function updateInvoice(int $invoiceId, array $input)
    // {
    //     // ...existing code...
    //     $invoice->update($mainFields);

    //     self::handleItems($invoice, $input['items'], true);
    //     self::handleDiscounts($invoice, $input['discounts'], true);
    //     self::handleCustomers($invoice, $input, $user, $mainFields);
    //     self::handleReminders($invoice, $input['reminders'], true);
    //     self::handleTaxes($invoice, $input['taxes'], true);

    //     return $invoice;
    // }



    // public static function createInvoice(array $input)
    // {

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

    // public static function updateInvoice(int $invoiceId, array $input)
    // {
    //     $invoice = Invoice::find($invoiceId);
    //     if (!$invoice) {
    //         throw new \Exception('Invoice not found');
    //     }

    //     $input['date'] = isset($input['date']) ? Carbon::parse($input['date'])->format('Y-m-d') : null;
    //     $input['valid_until'] = isset($input['valid_until']) ? Carbon::parse($input['valid_until'])->format('Y-m-d H:i:s') : null;
    //     $input['paid_on'] = isset($input['paid_on']) ? Carbon::parse($input['paid_on'])->format('Y-m-d H:i:s') : null;
    //     $input['timeframe'] = isset($input['timeframe']) ? Carbon::parse($input['timeframe'])->format('Y-m-d H:i:s') : null;

    //     $user = Auth::user();
    //     $mainFields = collect($input)->except([
    //         'customers',
    //         'items',
    //         'taxes',
    //         'discounts',
    //         'reminders'
    //     ])->toArray();

    //     $mainFields['creator_id'] = $user->id;
    //     $mainFields['company_id'] = $input['company_id'] ?? null;

    //     $invoice->update($mainFields);

    //     // Update items (delete old and add new)
    //     $invoice->items()->delete();
    //     foreach ($input['items'] ?? [] as $itemData) {
    //         $invoice->items()->create($itemData);
    //     }

    //     // Update discounts
    //     $invoice->discounts()->delete();
    //     foreach ($input['discounts'] ?? [] as $discountData) {
    //         if (
    //             empty($discountData['discount_type']) ||
    //             $discountData['discount_type'] === 'none'
    //         ) {
    //             continue;
    //         }
    //         $invoice->discounts()->create($discountData);
    //     }

    //     // Update customers (set customer_id to first, attach company if needed)
    //     if (!empty($input['customers'])) {
    //         $customerData = $input['customers'][0];
    //         $customerId = $customerData['id'] ?? $customerData['customer_id'] ?? null;
    //         if ($customerId) {
    //             $customer = Customer::find($customerId);
    //         } else {
    //             $customerData['creator_id'] = $user->id;
    //             $customerData['company_id'] = $mainFields['company_id'] ?? null;
    //             $customer = Customer::create($customerData);
    //         }
    //         $invoice->customer_id = $customer->id;
    //         $invoice->save();

    //         if (!empty($input['company_id'])) {
    //             $customer->companies()->syncWithoutDetaching([$input['company_id']]);
    //         }
    //     }

    //     // Update reminders
    //     $invoice->reminders()->delete();
    //     foreach ($input['reminders'] ?? [] as $reminderData) {
    //         $invoice->reminders()->create($reminderData);
    //     }

    //     // Update taxes
    //     $invoice->taxes()->delete();
    //     foreach ($input['taxes'] ?? [] as $taxData) {
    //         if (
    //             empty($taxData['tax_type']) ||
    //             $taxData['tax_type'] === 'none'
    //         ) {
    //             continue;
    //         }
    //         $invoice->taxes()->create($taxData);
    //     }

    //     return $invoice;
    // }
}
