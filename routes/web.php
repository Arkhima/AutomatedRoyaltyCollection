<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController,
    ResetPasswordController
};
use App\Http\Controllers\Artists\{
    DashboardController,
    TrackController,
    ArtistController,
    StreamController,
    RoyaltyController,
    ReportController,
    PaymentController
};

use App\Http\Controllers\ArtistProfileController;

// Home
Route::get('/', function () {
    return view('auth.login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Artist Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('Artists.Dashboard.index');

    // Artist Profile
    Route::get('/artist/profile', [ArtistProfileController::class, 'show'])->name('Artists.Profile.show');
    Route::put('/artist/profile', [ArtistProfileController::class, 'update'])->name('Artists.Profile.update');

    // Track Routes
    Route::resource('/tracks', TrackController::class)->names([
        'index'   => 'Artists.Tracks.index',
        'create'  => 'Artists.Tracks.create',
        'store'   => 'Artists.Tracks.store',
        'edit'    => 'Artists.Tracks.edit',
        'update'  => 'Artists.Tracks.update',
        'destroy' => 'Artists.Tracks.destroy',
    ]);

    // Artist Profile Routes (resource under prefix)
    Route::prefix('artist-profile')->name('Artists.ArtistSection.')->group(function () {
        Route::resource('', ArtistController::class)->parameters(['' => 'artist'])->names([
            'index'   => 'index',
            'create'  => 'create',
            'store'   => 'store',
            'show'    => 'show',
            'edit'    => 'edit',
            'update'  => 'update',
            'destroy' => 'destroy',
        ]);
    });

    // Stream Routes
    Route::prefix('streams')->name('Artists.Streams.')->group(function () {
        Route::get('/', [StreamController::class, 'index'])->name('index');
        Route::get('/upload', [StreamController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload', [StreamController::class, 'upload'])->name('upload');
        Route::post('/generate-demo', [StreamController::class, 'generateDemo'])->name('generate-demo');
        Route::get('/download-template', [StreamController::class, 'downloadTemplate'])->name('download-template');
    });

    // Royalty Routes
    Route::prefix('royalties')->name('Artists.Royalties.')->group(function () {
        Route::get('/', [RoyaltyController::class, 'index'])->name('index');
        Route::get('/export', [RoyaltyController::class, 'export'])->name('export');
    });

   // Report Routes
Route::prefix('reports')->name('Artists.Reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/generate/{format?}', [ReportController::class, 'generate'])
        ->name('generate')
        ->where('format', 'pdf|excel'); // Optional 'pdf' or 'excel' format parameter
});


    // Payment Routes
    Route::prefix('payments')->name('Artists.Payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
        Route::post('/request', [PaymentController::class, 'request'])->name('request'); // Added route for withdrawal request
    });
});
