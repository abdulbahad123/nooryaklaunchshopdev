<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteBuilder\FrontendController;
use App\Http\Controllers\WebsiteBuilder\Admin\LoginController as WbAdminLoginController;
use App\Http\Controllers\WebsiteBuilder\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\WebsiteBuilder\Admin\LandingSettingsController;
use App\Http\Controllers\WebsiteBuilder\Admin\CustomerController;
use App\Http\Controllers\WebsiteBuilder\Admin\TemplateController;
use App\Http\Controllers\WebsiteBuilder\Admin\PackageController;
use App\Http\Controllers\WebsiteBuilder\Admin\StaffController;
use App\Http\Controllers\WebsiteBuilder\Admin\WbRoleController;
use App\Http\Controllers\WebsiteBuilder\Admin\WbPaymentGatewayController;
use App\Http\Controllers\WebsiteBuilder\Admin\WbDomainController;
use App\Http\Controllers\WebsiteBuilder\Admin\AgencyAccessController;
use App\Http\Controllers\WebsiteBuilder\User\BuilderController;

/*
|--------------------------------------------------------------------------
| Website Builder Product Suite Routes (100% Isolated Architecture)
|--------------------------------------------------------------------------
*/

Route::prefix('website-builder')->name('website-builder.')->group(function () {

    // Public Landing Page, Template Showcase & Pricing (Ref Image 1 Match)
    Route::get('/', [FrontendController::class, 'index'])->name('index');
    Route::get('/templates', [FrontendController::class, 'templates'])->name('templates');
    Route::get('/templates/design-agency', [FrontendController::class, 'agencyTemplate'])->name('templates.design-agency');
    Route::get('/templates/design-agency/about', [FrontendController::class, 'agencyAbout'])->name('templates.design-agency.about');
    Route::get('/templates/design-agency/contact', [FrontendController::class, 'agencyContact'])->name('templates.design-agency.contact');
    Route::post('/templates/design-agency/contact', [FrontendController::class, 'agencyContactSubmit'])->name('templates.design-agency.contact.submit');
    Route::get('/pricing', [FrontendController::class, 'pricing'])->name('pricing');
    Route::get('/secret-login', [FrontendController::class, 'secretLogin'])->name('secret-login');

    // Dedicated DesignAGENCY Template Admin Dashboard (Isolated)
    Route::get('/agency-admin', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'dashboard'])->name('agency-admin.index');
    Route::get('/agency-admin/home', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'homePage'])->name('agency-admin.home');
    Route::get('/agency-admin/about', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'aboutPage'])->name('agency-admin.about');
    Route::get('/agency-admin/contact', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'contactPage'])->name('agency-admin.contact');
    Route::get('/agency-admin/footer', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'footerPage'])->name('agency-admin.footer');
    Route::get('/agency-admin/inquiries', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'inquiriesPage'])->name('agency-admin.inquiries');
    Route::delete('/agency-admin/inquiries/{id}', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'deleteInquiry'])->name('agency-admin.inquiries.delete');
    Route::post('/agency-admin', [\App\Http\Controllers\WebsiteBuilder\AgencyAdminController::class, 'update'])->name('agency-admin.update');

    // Super Admin Management Panel Authentication & Modules
    Route::prefix('admin')->name('admin.')->group(function () {
        // Authentication & Auto-Login Routes
        Route::get('/login', [WbAdminLoginController::class, 'login'])->name('login');
        Route::post('/login', [WbAdminLoginController::class, 'authenticate'])->name('authenticate');
        Route::get('/auto-login', [WbAdminLoginController::class, 'autoLogin'])->name('auto-login');
        Route::get('/sso-login', [WbAdminLoginController::class, 'ssoLogin'])->name('sso-login');
        Route::post('/logout', [WbAdminLoginController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Step 2: Dynamic Data & Color Management
        Route::get('/landing-settings', [LandingSettingsController::class, 'edit'])->name('landing-settings.edit');
        Route::post('/landing-settings', [LandingSettingsController::class, 'update'])->name('landing-settings.update');

        // Step 3: Customer Directory & Secret Login
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{id}/secret-login', [CustomerController::class, 'secretLogin'])->name('customers.secret-login');
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // Step 4: Template Management & Counts
        Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
        Route::patch('/templates/{id}/toggle', [TemplateController::class, 'toggleStatus'])->name('templates.toggle');
        Route::delete('/templates/{id}', [TemplateController::class, 'destroy'])->name('templates.destroy');

        // Step 5: Package Management (Starter, Pro, Business)
        Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
        Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');

        // Step 6: Staff Management & Permissions
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
        
        // Roles & Permissions
        Route::get('/roles', [WbRoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [WbRoleController::class, 'store'])->name('roles.store');
        Route::post('/roles/{id}/permissions', [WbRoleController::class, 'updatePermissions'])->name('roles.permissions');
        Route::delete('/roles/{id}', [WbRoleController::class, 'destroy'])->name('roles.destroy');

        // Payment Gateways (Razorpay)
        Route::get('/payment-gateways', [WbPaymentGatewayController::class, 'index'])->name('payment-gateways.index');
        Route::post('/payment-gateways', [WbPaymentGatewayController::class, 'update'])->name('payment-gateways.update');
        Route::post('/payment-gateways/razorpay/verify', [WbPaymentGatewayController::class, 'verifyRazorpay'])->name('payment-gateways.razorpay.verify');

        // Custom Domains & Subdomains Requests
        Route::get('/domains', [WbDomainController::class, 'index'])->name('domains.index');
        Route::post('/domains/{id}/status', [WbDomainController::class, 'updateStatus'])->name('domains.status');

        // Step 7: Agency Access / Authorized SaaS Products Card (Ref Image 2 Match)
        Route::get('/agency-access', [AgencyAccessController::class, 'index'])->name('agency-access');
    });

    // Tenant Customer Dashboard & Drag-and-Drop Page Builder
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [BuilderController::class, 'dashboard'])->name('dashboard');
        Route::get('/pages', [BuilderController::class, 'pages'])->name('pages.index');
        Route::post('/pages', [BuilderController::class, 'storePage'])->name('pages.store');
        Route::get('/pages/{id}/editor', [BuilderController::class, 'editor'])->name('pages.editor');
        Route::post('/sections/{id}/update', [BuilderController::class, 'updateSection'])->name('sections.update');
    });
});
