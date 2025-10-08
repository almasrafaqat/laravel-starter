<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

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





Route::get('/test-pdf', function () {
    $invoice = Invoice::find(5);
    $formattedInvoice = (new InvoiceService())->formatInvoice($invoice);
    return $downloadInvoice = (new InvoiceService())->downloadInvoice($formattedInvoice);

    return response()->json($formattedInvoice);
    $pdf = Pdf::loadView('pdf.test', ['name' => 'Test User']);
    return $pdf->download('test.pdf');
});



/**for resent the verification link */
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
