<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Trait\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use RuntimeException;

use function Laravel\Prompts\warning;

class InvoiceMutation
{
    use ApiResponse;

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
        $service = new InvoiceService();

        try {
            $invoice = Invoice::with(['company.mailSettings', 'customer'])->findOrFail($args['id']);
            $toEmail = $args['to_email'] ?? optional($invoice->customer)->email;
            if (!$toEmail) {
                return $this->gqlWarning(
                    'Warning:',
                    'Customer email is missing.',
                    ['hint' => 'Add an email to the customer or pass to_email in the mutation.']
                );
            }

            $formattedInvoice = $service->formatInvoice($invoice);
            $service->sendInvoiceEmail($formattedInvoice, $toEmail);

            return $this->gqlSuccess('Success', 'Invoice email sent successfully.', ['invoice_id' => $invoice->id]);
        } catch (TransportExceptionInterface $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'certificate verify failed') !== false) {
                return $this->gqlWarning(
                    'Warning:',
                    'Secure connection to the mail server failed (certificate not trusted).',
                    [
                        'hint' => 'Use a valid SMTP host (e.g., smtp.gmail.com), correct port/encryption, or install system CA bundle. On Windows/Laragon set curl.cainfo and openssl.cafile to a valid cacert.pem.',
                        'details' => 'TLS certificate verify failed'
                    ]
                );
            }
            if (stripos($msg, 'STARTTLS') !== false) {
                return $this->gqlWarning(
                    'Warning:',
                    'STARTTLS negotiation failed with the mail server.',
                    [
                        'hint' => 'Match port and encryption: 587 -> TLS, 465 -> SSL. Ensure the server supports STARTTLS.',
                        'details' => 'STARTTLS failed'
                    ]
                );
            }
            return $this->gqlWarning(
                'Warning:',
                'Unable to connect to the mail server. Check SMTP host/port/username/password.',
                ['hint' => 'Verify company mail settings and network connectivity.']
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->gqlNotFound('Not Found', 'Invoice not found.');
        } catch (\RuntimeException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'No SMTP settings')) {
                return $this->gqlWarning(
                    'Warning:',
                    'No default active SMTP settings found for this company.',
                    ['hint' => 'Mark one Mail Setting as Default and Active in Company > Mail Settings.']
                );
            }
            return $this->gqlWarning('Warning:', $msg);
        } catch (\Throwable $e) {
            return $this->gqlError('Error Found', 'Failed to send email. Please try again later.');
        }
    }

    // public function sendInvoice($_, array $args)
    // {
    //     $invoice = Invoice::findOrFail($args['id']);
    //     $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);
    //     $toEmail = $invoice->customer['email'] ?? 'customer@example.com';
    //     $result = (new InvoiceService())->sendInvoiceEmail($formattedInvoice, $toEmail);

    //      return $this->successResponse(
    //             ['invoice_id' => $invoice->id],
    //             'Invoice email sent successfully.'
    //         );
    // }

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
