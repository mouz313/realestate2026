<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentPayoutController;
use App\Http\Controllers\AgreementPDFController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\QuotationController as PortalQuotationController;
use App\Http\Controllers\Portal\VisitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RentAgreementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public website routes (outside auth)
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/about', [WebsiteController::class, 'about'])->name('website.about');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('website.contact');
Route::post('/contact', [WebsiteController::class, 'submitContact'])->name('website.contact.submit');
Route::get('/listings', [WebsiteController::class, 'properties'])->name('website.properties');
Route::get('/listings/{property}', [WebsiteController::class, 'property'])->name('website.property');

Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.resend');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update']);

    Route::resource('clients', ClientController::class);

    Route::resource('quotations', QuotationController::class);
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotations.pdf');
    Route::patch('/quotations/{quotation}/mark-sent', [QuotationController::class, 'markSent'])->name('quotations.mark-sent');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('/invoices/convert/{quotation}', [InvoiceController::class, 'convertFromQuotation'])->name('invoices.convert');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/payments', [InvoiceController::class, 'addPayment'])->name('invoices.payments.store');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::resource('agents', AgentController::class);
    Route::resource('cities', CityController::class);
    Route::resource('properties', PropertyController::class);
    Route::post('/properties/media/{media}/primary', [PropertyController::class, 'setPrimary'])->name('properties.media.primary');
    Route::delete('/properties/media/{media}', [PropertyController::class, 'destroyMedia'])->name('properties.media.destroy');

    Route::resource('deals', DealController::class);
    Route::resource('tokens', TokenController::class);
    Route::get('/installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::get('/installments/create/{deal?}', [InstallmentController::class, 'create'])->name('installments.create');
    Route::post('/installments', [InstallmentController::class, 'store'])->name('installments.store');
    Route::get('/installments/{installmentPlan}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
    Route::put('/installments/{installmentPlan}', [InstallmentController::class, 'update'])->name('installments.update');
    Route::delete('/installments/{installmentPlan}', [InstallmentController::class, 'destroy'])->name('installments.destroy');
    Route::patch('/installments/{installment}/pay', [InstallmentController::class, 'markPaid'])->name('installments.pay');
    Route::resource('rent-agreements', RentAgreementController::class);
    Route::resource('property-visits', PropertyVisitController::class);
    Route::resource('commissions', CommissionController::class);
    Route::patch('/commissions/{commission}/mark-paid', [CommissionController::class, 'markPaid'])->name('commissions.mark-paid');
    Route::resource('agent-payouts', AgentPayoutController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/agent-performance', [ReportController::class, 'agentPerformance'])->name('agent-performance');
        Route::get('/commissions', [ReportController::class, 'commissionReport'])->name('commissions');
        Route::get('/rent-roll', [ReportController::class, 'rentRoll'])->name('rent-roll');
        Route::get('/sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('sales.pdf');
    });

    Route::get('/pdf/sale-agreement/{deal}', [AgreementPDFController::class, 'saleAgreement'])->name('pdf.sale-agreement');
    Route::get('/pdf/rent-agreement/{rentAgreement}', [AgreementPDFController::class, 'rentAgreement'])->name('pdf.rent-agreement');
    Route::get('/pdf/token-receipt/{deal}', [AgreementPDFController::class, 'tokenReceipt'])->name('pdf.token-receipt');
    Route::get('/pdf/commission-invoice/{commission}', [AgreementPDFController::class, 'commissionInvoice'])->name('pdf.commission-invoice');
    Route::get('/pdf/possession-letter/{deal}', [AgreementPDFController::class, 'possessionLetter'])->name('pdf.possession-letter');

    Route::get('/payments/raast-redirect', function (Request $request) {
        $amount = $request->amount;
        $reference = $request->reference;
        $iban = $request->iban;

        return view('payments.raast-redirect', compact('amount', 'reference', 'iban'));
    })->name('payments.raast.redirect');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware(['guest', 'throttle:5,1'])->group(function () {
        Route::get('/login', [PortalAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login']);
    });
    Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');

    Route::middleware('portal.auth')->group(function () {
        Route::get('/quotations', [PortalQuotationController::class, 'index'])->name('quotations');
        Route::get('/quotations/{quotation}', [PortalQuotationController::class, 'show'])->name('quotations.show');
        Route::post('/quotations/{quotation}/approve', [PortalQuotationController::class, 'approve'])->name('quotations.approve');
        Route::post('/quotations/{quotation}/reject', [PortalQuotationController::class, 'reject'])->name('quotations.reject');
        Route::get('/quotations/{quotation}/pdf', [PortalQuotationController::class, 'pdf'])->name('quotations.pdf');

        Route::get('/invoices', [App\Http\Controllers\Portal\InvoiceController::class, 'index'])->name('invoices');
        Route::get('/invoices/{invoice}', [App\Http\Controllers\Portal\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/pdf', [App\Http\Controllers\Portal\InvoiceController::class, 'pdf'])->name('invoices.pdf');

        Route::get('/properties', [App\Http\Controllers\Portal\PropertyController::class, 'index'])->name('properties');
        Route::get('/properties/{property}', [App\Http\Controllers\Portal\PropertyController::class, 'show'])->name('properties.show');
        Route::get('/visits', [VisitController::class, 'index'])->name('visits');
        Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
        Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
        Route::get('/deals', [App\Http\Controllers\Portal\DealController::class, 'index'])->name('deals');
        Route::get('/deals/{deal}', [App\Http\Controllers\Portal\DealController::class, 'show'])->name('deals.show');
    });
});
