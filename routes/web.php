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
use App\Http\Controllers\CallLogController;
use App\Http\Controllers\RentalRecordController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyVisitController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/cron/{job}', [CronController::class, 'run'])->name('cron.run');

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(dashboard_route())
        : redirect()->route('login');
});

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

        Route::resource('expenses', ExpenseController::class)->except(['show']);
        Route::resource('cities', CityController::class);
        Route::resource('item-templates', ItemTemplateController::class)->except(['show', 'create']);
        Route::get('/settings', [SettingsController::class, 'index'])->middleware('permission:view_settings')->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->middleware('permission:edit_settings');
        Route::post('/settings/cron/{job}/run', [SettingsController::class, 'runCron'])->middleware('permission:edit_settings')->name('settings.cron.run');
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
        Route::get('/staff-dashboard', [DashboardController::class, 'staffIndex'])->name('staff.dashboard')->middleware('role:staff');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

        Route::prefix('team')->name('team.')->group(function () {
            Route::get('/', [TeamController::class, 'index'])->middleware('permission:view_agents|view_staff')->name('index');
            Route::get('/staff/create', [TeamController::class, 'staffCreate'])->middleware('permission:manage_staff')->name('staff.create');
            Route::post('/staff', [TeamController::class, 'staffStore'])->middleware('permission:manage_staff')->name('staff.store');
            Route::get('/staff/{user}/edit', [TeamController::class, 'staffEdit'])->middleware('permission:manage_staff')->name('staff.edit');
            Route::put('/staff/{user}', [TeamController::class, 'staffUpdate'])->middleware('permission:manage_staff')->name('staff.update');
            Route::delete('/staff/{user}', [TeamController::class, 'staffDestroy'])->middleware('permission:manage_staff')->name('staff.destroy');
        });
        Route::resource('agents', AgentController::class)->except(['index', 'show'])->middleware('permission:manage_agents');
        Route::get('/agents/{agent}', [AgentController::class, 'show'])->middleware('permission:view_agents')->name('agents.show');
        Route::get('/clients/export-excel', [ClientController::class, 'exportExcel'])->middleware('permission:export_reports')->name('clients.export-excel');
        Route::resource('clients', ClientController::class)->middleware('permission:view_clients');
        // Records: Available properties
        Route::get('/properties/available', [PropertyController::class, 'available'])->middleware('permission:view_properties|view_own_properties')->name('properties.available');
        Route::get('/properties/export', [PropertyController::class, 'exportExcel'])->middleware('permission:export_reports')->name('properties.export-excel');
        // Explicit property write routes declared before the resource so {property} does not capture "create"/"edit"
        Route::get('/properties/create', [PropertyController::class, 'create'])->middleware('permission:create_properties')->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->middleware('permission:create_properties')->name('properties.store');
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->middleware('permission:edit_any_properties|edit_own_properties')->name('properties.edit');
        Route::put('/properties/{property}', [PropertyController::class, 'update'])->middleware('permission:edit_any_properties|edit_own_properties')->name('properties.update');
        Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->middleware('permission:delete_properties')->name('properties.destroy');
        Route::resource('properties', PropertyController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view_properties|view_own_properties');
        // Records: Rented Records + Call Logs
        Route::resource('rental-records', RentalRecordController::class)->middleware('permission:view_deals');
        // Declared before the call-logs resource so {call_log} does not capture this literal
        Route::get('/call-logs/kanban', [CallLogController::class, 'kanban'])->middleware('permission:view_clients')->name('call-logs.kanban');
        Route::patch('/call-logs/{call_log}/status', [CallLogController::class, 'updateStatus'])->middleware('permission:view_clients')->name('call-logs.status');
        Route::get('/call-logs/{call_log}/convert', [DealController::class, 'create'])->middleware('permission:view_clients')->name('call-logs.convert');
        Route::get('/call-logs/{call_log}/add-property', [PropertyController::class, 'create'])->middleware('permission:view_clients')->name('call-logs.add-property');
        Route::get('/call-logs/match-properties', [CallLogController::class, 'matchProperties'])->middleware('permission:view_clients')->name('call-logs.match-properties');
        Route::resource('call-logs', CallLogController::class)->middleware('permission:view_clients');
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

        // Declared before the deals resource so {deal} does not capture these literals
        Route::get('/deals/export', [DealController::class, 'export'])->middleware('permission:export_reports')->name('deals.export');
        Route::get('/deals/export-excel', [DealController::class, 'exportExcel'])->middleware('permission:export_reports')->name('deals.export-excel');
        Route::get('/deals/trash', [DealController::class, 'trash'])->middleware('permission:view_deals')->name('deals.trash');
        Route::resource('deals', DealController::class)->middleware('permission:view_deals|view_own_deals');
        Route::patch('/deals/{deal}/restore', [DealController::class, 'restore'])->middleware('permission:view_deals')->name('deals.restore');
        Route::delete('/deals/{deal}/force-delete', [DealController::class, 'forceDelete'])->middleware('permission:delete_deals')->name('deals.force-delete');

        Route::resource('tokens', TokenController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view_deals');
        Route::get('/tokens/create', [TokenController::class, 'create'])->middleware('permission:manage_tokens')->name('tokens.create');
        Route::post('/tokens', [TokenController::class, 'store'])->middleware('permission:manage_tokens')->name('tokens.store');
        Route::get('/tokens/{token}/edit', [TokenController::class, 'edit'])->middleware('permission:manage_tokens')->name('tokens.edit');
        Route::put('/tokens/{token}', [TokenController::class, 'update'])->middleware('permission:manage_tokens')->name('tokens.update');
        Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->middleware('permission:manage_tokens')->name('tokens.destroy');
        Route::resource('property-visits', PropertyVisitController::class)->middleware('permission:view_visits');
        Route::resource('commissions', CommissionController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view_all_commissions|view_own_commissions');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->middleware('permission:manage_commissions')->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->middleware('permission:manage_commissions')->name('commissions.store');
        Route::get('/commissions/{commission}/edit', [CommissionController::class, 'edit'])->middleware('permission:manage_commissions')->name('commissions.edit');
        Route::put('/commissions/{commission}', [CommissionController::class, 'update'])->middleware('permission:manage_commissions')->name('commissions.update');
        Route::delete('/commissions/{commission}', [CommissionController::class, 'destroy'])->middleware('permission:manage_commissions')->name('commissions.destroy');
        Route::patch('/commissions/{commission}/mark-paid', [CommissionController::class, 'markPaid'])->middleware('permission:mark_commission_paid')->name('commissions.mark-paid');
        Route::post('/commissions/preview', [CommissionController::class, 'preview'])->name('commissions.preview');
        Route::resource('agent-payouts', AgentPayoutController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view_payouts');
        Route::get('/agent-payouts/create', [AgentPayoutController::class, 'create'])->middleware('permission:create_payouts')->name('agent-payouts.create');
        Route::post('/agent-payouts', [AgentPayoutController::class, 'store'])->middleware('permission:create_payouts')->name('agent-payouts.store');
        Route::get('/agent-payouts/{agent_payout}/edit', [AgentPayoutController::class, 'edit'])->middleware('permission:create_payouts')->name('agent-payouts.edit');
        Route::put('/agent-payouts/{agent_payout}', [AgentPayoutController::class, 'update'])->middleware('permission:create_payouts')->name('agent-payouts.update');
        Route::delete('/agent-payouts/{agent_payout}', [AgentPayoutController::class, 'destroy'])->middleware('permission:approve_payouts')->name('agent-payouts.destroy');

        Route::get('/search', [SearchController::class, 'index'])->name('search.index');

        Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/agent-performance', [ReportController::class, 'agentPerformance'])->name('agent-performance');
            Route::get('/commissions', [ReportController::class, 'commissionReport'])->name('commissions');
            Route::get('/sales/pdf', [ReportController::class, 'exportSalesPdf'])->middleware('permission:export_reports')->name('sales.pdf');
        });

        Route::middleware('permission:view_deals')->group(function () {
            Route::get('/pdf/sale-agreement/{deal}', [AgreementPDFController::class, 'saleAgreement'])->name('pdf.sale-agreement');
            Route::get('/pdf/token-receipt/{deal}', [AgreementPDFController::class, 'tokenReceipt'])->name('pdf.token-receipt');
            Route::get('/pdf/possession-letter/{deal}', [AgreementPDFController::class, 'possessionLetter'])->name('pdf.possession-letter');
        });
        Route::get('/pdf/commission-invoice/{commission}', [AgreementPDFController::class, 'commissionInvoice'])->middleware('permission:view_all_commissions')->name('pdf.commission-invoice');

        Route::get('/payments/raast-redirect', function (Request $request) {
            $amount = $request->amount;
            $reference = $request->reference;
            $iban = $request->iban;

            return view('payments.raast-redirect', compact('amount', 'reference', 'iban'));
        })->name('payments.raast.redirect');

    });
});

