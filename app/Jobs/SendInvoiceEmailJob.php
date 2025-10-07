<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Queueable;

    protected $invoice, $recipientEmail, $mailSetting;

    /**
     * Create a new job instance.
     */
    public function __construct($invoice, $recipientEmail, $mailSetting)
    {
        $this->invoice = $invoice;
        $this->recipientEmail = $recipientEmail;
        $this->mailSetting = $mailSetting;
    }


    /**
     * Execute the job.
     */
    public function handle()
    {
        $mailerConfig = [
            'transport' => $this->mailSetting->transport ?? 'smtp',
            'host' => $this->mailSetting->host,
            'port' => $this->mailSetting->port,
            'encryption' => $this->mailSetting->encryption,
            'username' => $this->mailSetting->username,
            'password' => $this->mailSetting->password,
        ];

        $mailer = app('mailer')->mailer('smtp')->setConfig($mailerConfig);

        $mailer->to($this->recipientEmail)
            ->send(new InvoiceMail($this->invoice));
    }
}
