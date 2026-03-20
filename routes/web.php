<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    PaymentController as ArtistPaymentController
};
use App\Http\Controllers\ArtistProfileController;

// ============================================
// USER/ARTIST ROUTES ONLY - No Admin Routes
// ============================================

// ✅ Root Route - Uses 'web' guard explicitly
Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('Artists.Dashboard.index');
    }
    return redirect()->route('login');
});

// Guest Routes (Users only)
Route::middleware('guest:web')->group(function () {
    // Artist/User Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration (only for artists/users)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes (users only)
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout (Users only)
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth:web')
    ->name('logout');

// Protected Artist Routes - 'auth' defaults to 'web' guard
Route::middleware(['auth:web'])->group(function () {
    
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
            ->where('format', 'pdf|excel');
    });

    // Artist Payment Routes (for artists to request payments)
    Route::prefix('payments')->name('Artists.Payments.')->group(function () {
        Route::get('/', [ArtistPaymentController::class, 'index'])->name('index');
        Route::get('/history', [ArtistPaymentController::class, 'history'])->name('history');
        Route::post('/request', [ArtistPaymentController::class, 'request'])->name('request');
    });
});