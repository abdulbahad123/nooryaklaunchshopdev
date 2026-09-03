<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteBuilder\FrontendController;
use App\Http\Controllers\WebsiteBuilder\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\WebsiteBuilder\Admin\LandingSettingsController;
use App\Http\Controllers\WebsiteBuilder\Admin\CustomerController;
use App\Http\Controllers\WebsiteBuilder\Admin\TemplateController;
use App\Http\Controllers\WebsiteBuilder\Admin\PackageController;
use App\Http\Controllers\WebsiteBuilder\Admin\StaffController;
use App\Http\Controllers\WebsiteBuilder\Admin\AgencyAccessController;
use App\Http\Controllers\WebsiteBuilder\User\BuilderController;

/*
|--------------------------------------------------------------------------
| Website Builder Product Suite Routes (100% Isolated Architecture)
|--------------------------------------------------------------------------
*/

Route::prefix('website-builder')->name('website-builder.')->group(function () {

    // Public Landing Page, Template Showcase & Pricing (Ref Image 1 & 2 Match)
    Route::get('/', [FrontendController::class, 'index'])->name('index');
    Route::get('/templates', [FrontendController::class, 'templates'])->name('templates');
    Route::get('/pricing', [FrontendController::class, 'pricing'])->name('pricing');
    Route::post('/register', [FrontendController::class, 'register'])->name('register');
    Route::get('/secret-login', [FrontendController::class, 'secretLogin'])->name('secret-login');
    Route::post('/razorpay/process', [FrontendController::class, 'processRazorpay'])->name('razorpay.process');
    Route::post('/razorpay/callback', [FrontendController::class, 'razorpayCallback'])->name('razorpay.callback');

    // Super Admin Management Panel (Step 2 to Step 7)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/quick-login', [AdminDashboardController::class, 'quickLogin'])->name('quick-login');
        Route::post('/quick-login', [AdminDashboardController::class, 'quickLogin'])->name('quick-login.post');

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
