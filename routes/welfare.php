<?php

use App\Http\Controllers\Welfare\BlogController;
use App\Http\Controllers\Welfare\CampaignController;
use App\Http\Controllers\Welfare\HomeController;
use App\Http\Controllers\Welfare\PageController;
use App\Http\Controllers\Welfare\FormSubmissionController;
use App\Http\Controllers\Welfare\AdminAuthController;
use App\Http\Controllers\Welfare\AdminDashboardController;
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
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/about/who-we-are', [PageController::class, 'whoWeAre'])->name('about.who-we-are');
    Route::get('/about/president-note', [PageController::class, 'presidentNote'])->name('about.president-note');
    Route::get('/about/leadership', [PageController::class, 'leadership'])->name('about.leadership');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/donate', [PageController::class, 'donate'])->name('donate');
    
    // New placeholders
    Route::get('/ecosystem', [PageController::class, 'ecosystem'])->name('ecosystem');
    Route::get('/serve-together', [PageController::class, 'serve'])->name('serve');
    Route::get('/impact-areas', [PageController::class, 'impact'])->name('impact');
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

    // Admin Section
    Route::get('/admin', function () {
        return redirect()->route('welfare.admin.login');
    });
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login/submit', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/submissions/{type}/{id}', [AdminDashboardController::class, 'showSubmission'])->name('admin.submission.detail');
        Route::post('/admin/submissions/{type}/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.submission.status');
        Route::get('/admin/export/{type}', [AdminDashboardController::class, 'exportCsv'])->name('admin.export');
        
        // Option management routes
        Route::post('/admin/options/add', [AdminDashboardController::class, 'addOption'])->name('admin.options.add');
        Route::post('/admin/options/edit/{id}', [AdminDashboardController::class, 'editOption'])->name('admin.options.edit');
        Route::post('/admin/options/delete/{id}', [AdminDashboardController::class, 'deleteOption'])->name('admin.options.delete');
    });
});
