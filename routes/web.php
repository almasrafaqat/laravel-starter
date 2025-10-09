<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])->name('verification.verify');





Route::get('/invoice/{id}/{action}', function ($id, $action) {
    $invoice = Invoice::find($id);
    $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);
    if ($action === 'download') {

        return $downloadInvoice = (new InvoiceService())->downloadInvoice($formattedInvoice);
    }

    if ($action == 'view') {

        return response()->json($formattedInvoice);
    }

    $pdf = Pdf::loadView('pdf.test', ['name' => 'Test User']);
    return $pdf->download('test.pdf');
});

Route::get('/send/invoice/{id}', function ($id) {
    $invoice = Invoice::find($id);
    $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);


    // Get the user who owns the invoice or the current user

    // Send to customer email (replace with actual customer email if needed)
    $toEmail = $invoice->customer['email'] ?? 'customer@example.com';

    $data = (new InvoiceService())->sendInvoiceEmail($formattedInvoice, $toEmail);

    return response()->json(['message' => 'Invoice sent successfully', 'data' => $data]);
});



/**for resent the verification link */
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
