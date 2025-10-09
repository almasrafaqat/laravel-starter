<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $pdf;
    public $footerData;

    /**
     * Create a new message instance.
     */
    public function __construct($invoice, $pdf, $footerData = [])
    {
        $this->invoice = $invoice;
        $this->pdf = $pdf;
        $this->footerData = $footerData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for ' . ($this->invoice['title'] ?? ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoices.default',
            
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate a descriptive filename
        $filename = 'Invoice';

        // Add invoice ID if available
        if (!empty($this->invoice['id'])) {
            $filename .= '_' . $this->invoice['id'];
        }

        $customer_name = $this->invoice->customer['name'] ?? 'customer';
        // Add client name (sanitized for filename)
        if (!empty($customer_name)) {
            // Remove special characters and spaces
            $clientName = preg_replace('/[^a-zA-Z0-9]/', '_', $customer_name);
            $filename .= '_' . $clientName;
        }

        // Add date
        if (!empty($this->invoice['date'])) {
            $filename .= '_' . $this->invoice['date'];
        }

        // Add file extension
        $filename .= '.pdf';
        return [
            Attachment::fromData(fn() => $this->pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
