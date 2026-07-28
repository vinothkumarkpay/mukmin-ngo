<?php

use App\Http\Controllers\Welfare\BlogController;
use App\Http\Controllers\Welfare\CampaignController;
use App\Http\Controllers\Welfare\HomeController;
use App\Http\Controllers\Welfare\PageController;
use App\Http\Controllers\Welfare\FormSubmissionController;
use App\Http\Controllers\Welfare\AdminAuthController;
use App\Http\Controllers\Welfare\AdminDashboardController;
use App\Http\Controllers\Welfare\AdminRoleController;
use App\Http\Controllers\Welfare\AdminUserController;
use App\Http\Controllers\Welfare\DonationController;
use App\Http\Controllers\Welfare\DonationDemoController;
use App\Http\Controllers\Welfare\MflsPartnerDocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Welfare Theme Routes
|--------------------------------------------------------------------------
|
| Copy this file to routes/welfare.php in your Laravel project, then add
| to bootstrap/app.php or routes/web.php:
|
|   require __DIR__.'/welfare.php';
|
| Or paste the Route::group block directly into routes/web.php
|
| */

Route::name('welfare.')->group(function () {
    Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming-soon');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/about/who-we-are', [PageController::class, 'whoWeAre'])->name('about.who-we-are');
    Route::get('/about/president-note', [PageController::class, 'presidentNote'])->name('about.president-note');
    Route::get('/about/leadership', [PageController::class, 'leadership'])->name('about.leadership');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact/submit', [FormSubmissionController::class, 'submitContact'])->name('contact.submit');
    Route::get('/legal-disclaimer', [PageController::class, 'legalDisclaimer'])->name('legal-disclaimer');
    Route::get('/donate', [DonationController::class, 'create'])->name('donate');
    Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');
    Route::get('/donate/thank-you', [DonationController::class, 'thankYou'])->name('donate.thank-you');
    Route::match(['GET', 'POST'], '/donate/payment/return', [DonationController::class, 'paymentReturn'])->name('donate.payment.return');
    Route::match(['GET', 'POST'], '/donate/payment/callback', [DonationController::class, 'paymentCallback'])->name('donate.payment.callback');

    // Standalone demo form for payment testing — not linked in site navigation
    Route::get('/donate-demo', [DonationDemoController::class, 'create'])->name('donate-demo');
    Route::post('/donate-demo', [DonationDemoController::class, 'store'])->name('donate-demo.store');
    Route::get('/donate-demo/thank-you', [DonationDemoController::class, 'thankYou'])->name('donate-demo.thank-you');
    Route::match(['GET', 'POST'], '/donate-demo/payment/return', [DonationDemoController::class, 'paymentReturn'])->name('donate-demo.payment.return');
    Route::match(['GET', 'POST'], '/donate-demo/payment/callback', [DonationDemoController::class, 'paymentCallback'])->name('donate-demo.payment.callback');
    
    // New placeholders
    Route::get('/ecosystem', [PageController::class, 'ecosystem'])->name('ecosystem');
    Route::get('/serve-together', [PageController::class, 'serve'])->name('serve');
    Route::get('/impact-areas', [PageController::class, 'impact'])->name('impact');
    Route::get('/impact-areas/mfls', [PageController::class, 'mfls'])->name('impact.mfls');
    Route::get('/impact-areas/mfls/partner/{partnerId}/programme-info', [MflsPartnerDocumentController::class, 'preview'])->name('impact.mfls.partner-programme-info');
    Route::get('/impact-areas/mfls/partner/{partnerId}/programme-info/view', [MflsPartnerDocumentController::class, 'viewHtml'])->name('impact.mfls.partner-programme-info.view');
    Route::get('/impact-areas/mfls/partner/{partnerId}/programme-info/download', [MflsPartnerDocumentController::class, 'download'])->name('impact.mfls.partner-programme-info.download');
    Route::get('/impact-areas/sirat-series', [PageController::class, 'sirat'])->name('impact.sirat');
    Route::get('/news', [PageController::class, 'news'])->name('news');
    Route::get('/changing-lives', [PageController::class, 'changing'])->name('changing');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/causes', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/causes/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');

    // Public User Forms
    Route::get('/feedback-suggestion', [FormSubmissionController::class, 'feedback'])->name('feedback');
    Route::post('/feedback-suggestion/submit', [FormSubmissionController::class, 'submitFeedback'])->name('feedback.submit');

    Route::get('/membership-ordinary', [FormSubmissionController::class, 'membershipOrdinary'])->name('membership.ordinary');
    Route::post('/membership-ordinary/submit', [FormSubmissionController::class, 'submitOrdinary'])->name('membership.ordinary.submit');

    Route::get('/membership-friends', [FormSubmissionController::class, 'membershipFriends'])->name('membership.friends');
    Route::post('/membership-friends/submit', [FormSubmissionController::class, 'submitFriends'])->name('membership.friends.submit');

    Route::get('/mentor-registration', [FormSubmissionController::class, 'mentor'])->name('mentor');
    Route::post('/mentor-registration/submit', [FormSubmissionController::class, 'submitMentor'])->name('mentor.submit');

    Route::get('/partnership-collaboration', [FormSubmissionController::class, 'partner'])->name('partner');
    Route::post('/partnership-collaboration/submit', [FormSubmissionController::class, 'submitPartner'])->name('partner.submit');

    Route::get('/volunteer-registration', [FormSubmissionController::class, 'volunteer'])->name('volunteer');
    Route::post('/volunteer-registration/submit', [FormSubmissionController::class, 'submitVolunteer'])->name('volunteer.submit');

    Route::get('/community-aid', [FormSubmissionController::class, 'communityAid'])->name('community-aid');
    Route::post('/community-aid/submit', [FormSubmissionController::class, 'submitCommunityAid'])->name('community-aid.submit');

    Route::get('/mfls-scholarship-application', [FormSubmissionController::class, 'mflsScholarship'])->name('mfls-scholarship');
    Route::get('/mfls-scholarship-application/programme-requirements', [FormSubmissionController::class, 'mflsProgrammeRequirements'])->name('mfls-scholarship.programme-requirements');
    Route::post('/mfls-scholarship-application/requirements-inquiry', [FormSubmissionController::class, 'submitMflsRequirementsInquiry'])->name('mfls-scholarship.requirements-inquiry');
    Route::post('/mfls-scholarship-application/submit', [FormSubmissionController::class, 'submitMflsScholarship'])->name('mfls-scholarship.submit');

    // Admin Section
    Route::get('/admin', function () {
        return redirect()->route('welfare.admin.login');
    });
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login/submit', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/donation-payments', [AdminDashboardController::class, 'donationPayments'])
            ->middleware('admin.permission:donations.view')
            ->name('admin.donation-payments');
        Route::get('/admin/submissions/{type}/{id}', [AdminDashboardController::class, 'showSubmission'])->name('admin.submission.detail');
        Route::post('/admin/submissions/{type}/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.submission.status');
        Route::post('/admin/submissions/{type}/{id}/status/notify', [AdminDashboardController::class, 'notifyStatusUpdate'])->name('admin.submission.status.notify');
        Route::get('/admin/export/{type}', [AdminDashboardController::class, 'exportCsv'])->name('admin.export');
        Route::get('/admin/import/{type}/template', [AdminDashboardController::class, 'downloadImportTemplate'])->name('admin.import.template');
        Route::post('/admin/import/{type}', [AdminDashboardController::class, 'importSubmissions'])->name('admin.import');
        
        // Option management routes
        Route::post('/admin/options/add', [AdminDashboardController::class, 'addOption'])->name('admin.options.add');
        Route::post('/admin/options/edit/{id}', [AdminDashboardController::class, 'editOption'])->name('admin.options.edit');
        Route::post('/admin/options/delete/{id}', [AdminDashboardController::class, 'deleteOption'])->name('admin.options.delete');
        Route::post('/admin/mfls/partner-documents/{partnerId}', [MflsPartnerDocumentController::class, 'upload'])
            ->middleware('admin.permission:mfls.documents.upload')
            ->name('admin.mfls.partner-documents.upload');
        Route::get('/admin/mfls/partner-documents/{partnerId}/view', [MflsPartnerDocumentController::class, 'viewHtml'])
            ->middleware('admin.permission:mfls.documents.view')
            ->name('admin.mfls.partner-documents.view');
        Route::get('/admin/mfls/partner-documents/{partnerId}/download', [MflsPartnerDocumentController::class, 'download'])
            ->middleware('admin.permission:mfls.documents.view')
            ->name('admin.mfls.partner-documents.download');

        // User & role management (super admin configurable)
        Route::middleware('admin.permission:admin.users.manage')->group(function () {
            Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
            Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
            Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
            Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
            Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        });

        Route::middleware('admin.permission:admin.roles.manage')->group(function () {
            Route::get('/admin/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/admin/roles/create', [AdminRoleController::class, 'create'])->name('admin.roles.create');
            Route::post('/admin/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store');
            Route::get('/admin/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
            Route::put('/admin/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
            Route::delete('/admin/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');
        });
    });
});
