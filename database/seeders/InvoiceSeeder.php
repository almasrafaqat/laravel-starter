<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Discount;
use App\Models\Reminder;
use App\Models\Link;
use App\Models\Charity;
use App\Models\Category;
use App\Models\Checklist;
use App\Models\Checklistable;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Create Customer
        $customer = Customer::create([
            'name' => 'Acme Corp',
            'email' => 'billing@acme.com',
            'address' => '123 Main St'
        ]);

        // Create Invoice
        $invoice = Invoice::create([
            'title' => 'Web Development Services',
            'invoice_number' => 'INV-1001',
            'date' => now(),
            'valid_until' => now()->addDays(30),
            'customer_id' => $customer->id,
            'company_id' => 1,
            'creator_id' => 1,
            'payment_status' => 'unpaid',
            'subtotal' => 1000,
            'tax_rate' => 10,
            'tax_amount' => 100,
            'total' => 1100,
            'balance_due' => 1100,
            'currency' => 'USD',
            'currency_rate' => 1,
            'total_pkr' => 310000,
            'notes' => 'Thank you for your business!'
        ]);

        // Create Items
        $item1 = Item::create([
            'invoice_id' => $invoice->id,
            'name' => 'Website Design',
            'description' => 'Design of homepage and 5 inner pages',
            'quantity' => 1,
            'price' => 600
        ]);
        $item2 = Item::create([
            'invoice_id' => $invoice->id,
            'name' => 'Website Development',
            'description' => 'Development of responsive website',
            'quantity' => 1,
            'price' => 400
        ]);

        // Create Discount (polymorphic)
        $discount = Discount::create([
            'discount_type' => 'percentage',
            'discount' => 10,
            'discount_amount' => 110
        ]);
        $invoice->discounts()->save($discount);

        // Create Reminder (polymorphic)
        $reminder = Reminder::create([
            'schedule_date' => now()->addDays(7),
            'timezone' => 'UTC',
            'message' => 'Your invoice is due soon!'
        ]);
        $invoice->reminders()->save($reminder);

        // Create Link (polymorphic)
        $link = Link::create([
            'link' => 'https://pay.example.com/invoice/INV-1001',
            'is_active' => true,
            'expires_at' => now()->addDays(30)
        ]);
        $invoice->links()->save($link);

        // Create Charity
        $charity = Charity::create([
            'invoice_id' => $invoice->id,
            'cause_name' => 'Education Fund',
            'type' => 'percentage',
            'value' => 5,
            'amount_usd' => 55,
            'amount_pkr' => 15500,
            'paid' => 0,
            'remaining' => 55,
            'currency_rate' => 1,
            'is_contributed' => false,
            'contribution_date' => null,
            'notes' => '5% of invoice goes to charity'
        ]);

        // Create Category and Checklist
        $category = Category::create([
            'name' => 'Invoice Tasks',
            'slug' => 'invoice-tasks',
            'description' => 'Tasks related to invoice processing'
        ]);
        $checklist = Checklist::create([
            'title' => 'Send Invoice',
            'slug' => 'send-invoice',
            'description' => 'Send invoice to customer',
            'category_id' => $category->id,
            'is_active' => true,
            'order' => 1
        ]);
        // Attach checklist to invoice (polymorphic)
        Checklistable::create([
            'checklist_id' => $checklist->id,
            'checklistable_id' => $invoice->id,
            'checklistable_type' => Invoice::class
        ]);

        /**2nd */


        // Second Invoice
        $customer2 = Customer::create([
            'name' => 'Beta LLC',
            'email' => 'accounts@beta.com',
            'address' => '456 Beta Road'
        ]);

        $invoice2 = Invoice::create([
            'title' => 'Mobile App Development',
            'invoice_number' => 'INV-1002',
            'date' => now()->subDays(10),
            'valid_until' => now()->addDays(20),
            'customer_id' => $customer2->id,
            'company_id' => 1,
            'creator_id' => 1,
            'payment_status' => 'paid',
            'subtotal' => 2000,
            'tax_rate' => 8,
            'tax_amount' => 160,
            'total' => 2160,
            'balance_due' => 0,
            'currency' => 'USD',
            'currency_rate' => 1,
            'total_pkr' => 610000,
            'notes' => 'Paid in full.'
        ]);

        Item::create([
            'invoice_id' => $invoice2->id,
            'name' => 'App Design',
            'description' => 'UI/UX for mobile app',
            'quantity' => 1,
            'price' => 800
        ]);
        Item::create([
            'invoice_id' => $invoice2->id,
            'name' => 'App Development',
            'description' => 'iOS and Android app',
            'quantity' => 1,
            'price' => 1200
        ]);

        $discount2 = Discount::create([
            'discount_type' => 'fixed',
            'discount' => 100,
            'discount_amount' => 100
        ]);
        $invoice2->discounts()->save($discount2);

        $reminder2 = Reminder::create([
            'schedule_date' => now()->addDays(5),
            'timezone' => 'UTC',
            'message' => 'Your app invoice is due soon!'
        ]);
        $invoice2->reminders()->save($reminder2);

        $link2 = Link::create([
            'link' => 'https://pay.example.com/invoice/INV-1002',
            'is_active' => true,
            'expires_at' => now()->addDays(20)
        ]);
        $invoice2->links()->save($link2);

        Charity::create([
            'invoice_id' => $invoice2->id,
            'cause_name' => 'Health Fund',
            'type' => 'fixed',
            'value' => 50,
            'amount_usd' => 50,
            'amount_pkr' => 14000,
            'paid' => 0,
            'remaining' => 50,
            'currency_rate' => 1,
            'is_contributed' => false,
            'contribution_date' => null,
            'notes' => 'Fixed charity donation'
        ]);

        // Third Invoice
        $customer3 = Customer::factory()->create([
            'name' => 'Gamma Inc',
            'email' => 'finance@gamma.com',
            'address' => '789 Gamma Ave'
        ]);

        $invoice3 = Invoice::create([
            'title' => 'SEO & Marketing',
            'invoice_number' => 'INV-1003',
            'date' => now()->subDays(20),
            'valid_until' => now()->addDays(10),
            'customer_id' => $customer3->id,
            'company_id' => 1,
            'creator_id' => 1,
            'payment_status' => 'unpaid',
            'subtotal' => 1500,
            'tax_rate' => 5,
            'tax_amount' => 75,
            'total' => 1575,
            'balance_due' => 1575,
            'currency' => 'USD',
            'currency_rate' => 1,
            'total_pkr' => 450000,
            'notes' => 'SEO campaign for 3 months.'
        ]);

        Item::create([
            'invoice_id' => $invoice3->id,
            'name' => 'SEO Optimization',
            'description' => 'On-page and off-page SEO',
            'quantity' => 1,
            'price' => 900
        ]);
        Item::create([
            'invoice_id' => $invoice3->id,
            'name' => 'Marketing',
            'description' => 'Social media and email marketing',
            'quantity' => 1,
            'price' => 600
        ]);

        $discount3 = Discount::create([
            'discount_type' => 'percentage',
            'discount' => 5,
            'discount_amount' => 78.75
        ]);
        $invoice3->discounts()->save($discount3);

        $reminder3 = Reminder::create([
            'schedule_date' => now()->addDays(2),
            'timezone' => 'UTC',
            'message' => 'Final reminder for SEO invoice!'
        ]);
        $invoice3->reminders()->save($reminder3);

        $link3 = Link::create([
            'link' => 'https://pay.example.com/invoice/INV-1003',
            'is_active' => false,
            'expires_at' => now()->addDays(10)
        ]);
        $invoice3->links()->save($link3);

        Charity::create([
            'invoice_id' => $invoice3->id,
            'cause_name' => 'Environment Fund',
            'type' => 'percentage',
            'value' => 2,
            'amount_usd' => 31.5,
            'amount_pkr' => 9000,
            'paid' => 0,
            'remaining' => 31.5,
            'currency_rate' => 1,
            'is_contributed' => false,
            'contribution_date' => null,
            'notes' => '2% for environment'
        ]);
    }
}
