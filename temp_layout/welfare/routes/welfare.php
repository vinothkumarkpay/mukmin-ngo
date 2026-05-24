<?php

use App\Http\Controllers\Welfare\BlogController;
use App\Http\Controllers\Welfare\CampaignController;
use App\Http\Controllers\Welfare\HomeController;
use App\Http\Controllers\Welfare\PageController;
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
*/

Route::name('welfare.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/donate', [PageController::class, 'donate'])->name('donate');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/causes', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/causes/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');
});
