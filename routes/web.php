<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentPayoutController;
use App\Http\Controllers\AgreementPDFController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GatewayPaymentController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\DocumentController as PortalDocumentController;
use App\Http\Controllers\Portal\ForgotPasswordController as PortalForgotPasswordController;
use App\Http\Controllers\Portal\OwnerRentController;
use App\Http\Controllers\Portal\QuotationController as PortalQuotationController;
use App\Http\Controllers\Portal\TenantRentController;
use App\Http\Controllers\Portal\VisitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RentAgreementController;
use App\Http\Controllers\RentPaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public website routes (outside auth)
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/about', [WebsiteController::class, 'about'])->name('website.about');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('website.contact');
Route::post('/contact', [WebsiteController::class, 'submitContact'])->middleware('throttle:3,1')->name('website.contact.submit');
Route::get('/listings', [WebsiteController::class, 'properties'])->name('website.properties');
Route::get('/listings/{property}', [WebsiteController::class, 'property'])->name('website.property');
Route::post('/listings/{property}/enquiry', [WebsiteController::class, 'submitEnquiry'])->middleware('throttle:5,1')->name('website.property.enquiry');
Route::get('/privacy', [WebsiteController::class, 'privacy'])->name('website.privacy');
Route::get('/terms', [WebsiteController::class, 'terms'])->name('website.terms');
Route::get('/sitemap.xml', [WebsiteController::class, 'sitemap'])->name('website.sitemap');

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

    // Profile routes (available to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Admin routes — /admin prefix (admin + staff + agent shared)
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Admin-only features
        Route::middleware('permission:view_activity_log')->group(function () {
            Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
        });

        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::resource('expenses', ExpenseController::class)->except(['show']);
        Route::resource('cities', CityController::class);

        Route::resource('item-templates', ItemTemplateController::class)->except(['show', 'create']);
        Route::get('/settings', [SettingsController::class, 'index'])->middleware('permission:view_settings')->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->middleware('permission:edit_settings');
        Route::get('/settings/items', [ItemTemplateController::class, 'index'])->name('settings.items');

        Route::middleware('permission:manage_roles')->group(function () {
            Route::resource('roles', RoleController::class)->except(['show']);
            Route::get('/roles/{role}/permissions', [RoleController::class, 'assignPermissionsForm'])->name('roles.permissions');
            Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.permissions.assign');
        });

        Route::middleware('permission:manage_permissions')->group(function () {
            Route::resource('permissions', PermissionController::class)->except(['show']);
        });

        Route::middleware('permission:assign_user_roles')->group(function () {
            Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        });
    });

    // Shared routes — /admin prefix (admin + staff + agent)
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

        Route::get('/team', [TeamController::class, 'index'])->middleware('permission:view_agents|view_staff')->name('team.index');
        Route::resource('agents', AgentController::class)->except(['index', 'show'])->middleware('permission:manage_agents');
        Route::get('/agents/{agent}', [AgentController::class, 'show'])->middleware('permission:view_agents')->name('agents.show');
        Route::resource('clients', ClientController::class)->middleware('permission:view_clients');
        Route::get('/clients/export-excel', [ClientController::class, 'exportExcel'])->middleware('permission:export_reports')->name('clients.export-excel');
        Route::resource('properties', PropertyController::class)->middleware('permission:view_properties|view_own_properties');
        Route::get('/properties/export', [PropertyController::class, 'exportExcel'])->middleware('permission:export_reports')->name('properties.export-excel');
        Route::post('/properties/media/{media}/primary', [PropertyController::class, 'setPrimary'])->middleware('permission:manage_property_media')->name('properties.media.primary');
        Route::delete('/properties/media/{media}', [PropertyController::class, 'destroyMedia'])->middleware('permission:manage_property_media')->name('properties.media.destroy');

        Route::resource('quotations', QuotationController::class)->middleware('permission:view_quotations');
        Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])->middleware('permission:view_quotations')->name('quotations.pdf');
        Route::patch('/quotations/{quotation}/mark-sent', [QuotationController::class, 'markSent'])->middleware('permission:send_quotations')->name('quotations.mark-sent');
        Route::get('/quotations/{quotation}/versions', [QuotationController::class, 'versions'])->middleware('permission:view_quotations')->name('quotations.versions');

        Route::middleware('permission:view_invoices')->group(function () {
            Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
            Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
            Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
            Route::get('/invoices/export-excel', [InvoiceController::class, 'exportExcel'])->name('invoices.export-excel');
            Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
            Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
            Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
            Route::get('/invoices/convert/{quotation}', [InvoiceController::class, 'convertFromQuotation'])->name('invoices.convert');
            Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
            Route::post('/invoices/{invoice}/payments', [InvoiceController::class, 'addPayment'])->name('invoices.payments.store');
            Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        });

        Route::middleware('permission:view_all_payments')->group(function () {
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
            Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
            Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
            Route::get('/payments/export-excel', [PaymentController::class, 'exportExcel'])->name('payments.export-excel');
        });

        Route::middleware('permission:view_installments')->group(function () {
            Route::get('/installments', [InstallmentController::class, 'index'])->name('installments.index');
            Route::get('/installments/create/{deal?}', [InstallmentController::class, 'create'])->name('installments.create');
            Route::post('/installments', [InstallmentController::class, 'store'])->name('installments.store');
            Route::get('/installments/{installmentPlan}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
            Route::put('/installments/{installmentPlan}', [InstallmentController::class, 'update'])->name('installments.update');
            Route::delete('/installments/{installmentPlan}', [InstallmentController::class, 'destroy'])->name('installments.destroy');
            Route::patch('/installments/{installment}/pay', [InstallmentController::class, 'markPaid'])->name('installments.pay');
            Route::get('/installments/export-excel', [InstallmentController::class, 'exportExcel'])->name('installments.export-excel');
        });

        Route::get('/deals/export', [DealController::class, 'export'])->middleware('permission:export_reports')->name('deals.export');
        Route::get('/deals/export-excel', [DealController::class, 'exportExcel'])->middleware('permission:export_reports')->name('deals.export-excel');
        Route::get('/deals/trash', [DealController::class, 'trash'])->middleware('permission:view_deals')->name('deals.trash');
        Route::patch('/deals/{deal}/restore', [DealController::class, 'restore'])->middleware('permission:view_deals')->name('deals.restore');
        Route::delete('/deals/{deal}/force-delete', [DealController::class, 'forceDelete'])->middleware('permission:delete_deals')->name('deals.force-delete');
        Route::resource('deals', DealController::class)->middleware('permission:view_deals|view_own_deals');

        Route::resource('tokens', TokenController::class)->middleware('permission:view_deals');
        Route::resource('rent-agreements', RentAgreementController::class)->middleware('permission:view_rent_agreements');
        Route::post('/rent-agreements/{rent_agreement}/regenerate-schedule', [RentPaymentController::class, 'regenerateSchedule'])->middleware('permission:record_rent_payments')->name('rent-agreements.regenerate-schedule');
        Route::post('/rent-agreements/{rent_agreement}/generate-next-month', [RentPaymentController::class, 'generateNextMonth'])->middleware('permission:record_rent_payments')->name('rent-agreements.generate-next-month');
        Route::post('/rent-agreements/{rent_agreement}/renew', [RentAgreementController::class, 'renew'])->middleware('permission:edit_rent_agreements')->name('rent-agreements.renew');
        Route::post('/rent-agreements/{rent_agreement}/deposit-receive', [RentAgreementController::class, 'receiveDeposit'])->middleware('permission:record_rent_payments')->name('rent-agreements.deposit-receive');

        Route::middleware('permission:settle_deposits')->group(function () {
            Route::post('/rent-agreements/{rent_agreement}/move-out', [RentAgreementController::class, 'moveOut'])->name('rent-agreements.move-out');
            Route::post('/rent-agreements/{rent_agreement}/deductions', [RentAgreementController::class, 'storeDeduction'])->name('rent-agreements.deductions.store');
            Route::delete('/rent-agreements/{rent_agreement}/deductions/{rentDepositDeduction}', [RentAgreementController::class, 'destroyDeduction'])->name('rent-agreements.deductions.destroy');
            Route::post('/rent-agreements/{rent_agreement}/return-deposit', [RentAgreementController::class, 'returnDeposit'])->name('rent-agreements.return-deposit');
        });
        Route::post('/rent-agreements/{rent_agreement}/notices/{rentNotice}/respond', [RentAgreementController::class, 'respondNotice'])->middleware('permission:generate_rent_notices')->name('rent-agreements.notices.respond');

        Route::middleware('permission:view_rent_agreements')->group(function () {
            Route::resource('rent-payments', RentPaymentController::class)->except(['create', 'store']);
            Route::patch('/rent-payments/{rentPayment}/pay', [RentPaymentController::class, 'updateStatus'])->middleware('permission:record_rent_payments')->name('rent-payments.pay');
            Route::patch('/rent-payments/{rentPayment}/waive', [RentPaymentController::class, 'waive'])->middleware('permission:waive_rent')->name('rent-payments.waive');
            Route::get('/rent-payments/{rentPayment}/receipt', [RentPaymentController::class, 'receipt'])->name('rent-payments.receipt');
            Route::get('/rent-payments/export-excel', [RentPaymentController::class, 'exportExcel'])->name('rent-payments.export-excel');
        });

        Route::resource('property-visits', PropertyVisitController::class)->middleware('permission:view_visits');
        Route::resource('commissions', CommissionController::class)->middleware('permission:view_all_commissions|view_own_commissions');
        Route::patch('/commissions/{commission}/mark-paid', [CommissionController::class, 'markPaid'])->middleware('permission:mark_commission_paid')->name('commissions.mark-paid');
        Route::resource('agent-payouts', AgentPayoutController::class)->middleware('permission:view_payouts');

        Route::get('/search', [SearchController::class, 'index'])->name('search.index');

        Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/agent-performance', [ReportController::class, 'agentPerformance'])->name('agent-performance');
            Route::get('/commissions', [ReportController::class, 'commissionReport'])->name('commissions');
            Route::get('/rent-roll', [ReportController::class, 'rentRoll'])->name('rent-roll');
            Route::get('/sales/pdf', [ReportController::class, 'exportSalesPdf'])->middleware('permission:export_reports')->name('sales.pdf');
        });

        Route::middleware('permission:view_deals')->group(function () {
            Route::get('/pdf/sale-agreement/{deal}', [AgreementPDFController::class, 'saleAgreement'])->name('pdf.sale-agreement');
            Route::get('/pdf/token-receipt/{deal}', [AgreementPDFController::class, 'tokenReceipt'])->name('pdf.token-receipt');
            Route::get('/pdf/possession-letter/{deal}', [AgreementPDFController::class, 'possessionLetter'])->name('pdf.possession-letter');
        });
        Route::get('/pdf/rent-agreement/{rentAgreement}', [AgreementPDFController::class, 'rentAgreement'])->middleware('permission:view_rent_agreements')->name('pdf.rent-agreement');
        Route::get('/pdf/commission-invoice/{commission}', [AgreementPDFController::class, 'commissionInvoice'])->middleware('permission:view_all_commissions')->name('pdf.commission-invoice');

        Route::get('/payments/raast-redirect', function (Request $request) {
            $amount = $request->amount;
            $reference = $request->reference;
            $iban = $request->iban;

            return view('payments.raast-redirect', compact('amount', 'reference', 'iban'));
        })->name('payments.raast.redirect');

        Route::post('/payments/gateway/{gateway}', [GatewayPaymentController::class, 'create'])
            ->middleware('permission:record_rent_payments')
            ->name('gateway.create');
    });
});

// Gateway callback/return are reached via server-to-server traffic (callback)
// and browser redirect (return). Verification happens via order_id lookup +
// driver::verify() so these stay unauthenticated but must match a pending order.
Route::group(['prefix' => 'gateway'], function () {
    Route::post('/{gateway}/callback', [GatewayPaymentController::class, 'callback'])->name('gateway.callback');
    Route::get('/{gateway}/return', [GatewayPaymentController::class, 'return'])->name('gateway.return');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [PortalAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login']);

        Route::get('/forgot-password', [PortalForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [PortalForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
        Route::get('/reset-password/{token}', [PortalForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [PortalForgotPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
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
        Route::resource('documents', PortalDocumentController::class)->only(['index', 'create', 'store', 'destroy']);

        Route::prefix('rent')->name('rent.')->group(function () {
            Route::get('/dashboard', [TenantRentController::class, 'dashboard'])->name('dashboard');
            Route::get('/agreements', [TenantRentController::class, 'agreements'])->name('agreements');
            Route::get('/agreements/{rentAgreement}', [TenantRentController::class, 'agreement'])->name('agreement');
            Route::post('/agreements/{rentAgreement}/notice', [TenantRentController::class, 'submitNotice'])->name('notice');
            Route::get('/payments', [TenantRentController::class, 'payments'])->name('payments');
            Route::get('/payments/{rentPayment}/receipt', [TenantRentController::class, 'receipt'])->name('receipt');
        });

        Route::prefix('owner')->name('owner.')->group(function () {
            Route::get('/dashboard', [OwnerRentController::class, 'dashboard'])->name('dashboard');
            Route::get('/properties', [OwnerRentController::class, 'properties'])->name('properties');
            Route::get('/properties/{rentAgreement}', [OwnerRentController::class, 'property'])->name('property');
            Route::get('/income', [OwnerRentController::class, 'income'])->name('income');
            Route::get('/tenants', [OwnerRentController::class, 'tenants'])->name('tenants');
        });
    });
});
