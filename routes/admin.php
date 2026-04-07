<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaymentsController as AdminPaymentsController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ArtistsController;
use App\Http\Controllers\Admin\TracksController;

// Secret admin path (from .env)
$adminPath = env('ADMIN_PATH', 'system-access');

Route::prefix($adminPath)
    ->name('admin.')
    ->group(function () {

        // ===============================
        // GUEST ADMIN ROUTES (LOGIN, PASSWORD RESET)
        // ===============================
        Route::middleware('guest:admin')->group(function () {
            Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

            Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
            Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

            Route::get('/reset-password/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
            Route::post('/reset-password', [AdminResetPasswordController::class, 'reset'])->name('password.update');
        });

        // ===============================
        // AUTHENTICATED ADMIN ROUTES
        // ===============================
        Route::middleware('auth:admin')->group(function () {

            // Logout
            Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

            // Dashboard
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.alt');

            // USERS (FULL CRUD)
            Route::get('/users', [UsersController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
            Route::post('/users', [UsersController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
            Route::post('/users/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

            // ARTISTS (FULL CRUD)
            Route::get('/artists', [ArtistsController::class, 'index'])->name('artists.index');
            Route::get('/artists/create', [ArtistsController::class, 'create'])->name('artists.create');
            Route::post('/artists', [ArtistsController::class, 'store'])->name('artists.store');
            Route::get('/artists/{artist}', [ArtistsController::class, 'show'])->name('artists.show');
            Route::delete('/artists/{artist}', [ArtistsController::class, 'destroy'])->name('artists.destroy');

            // TRACKS (with exports)
            Route::get('/tracks', [TracksController::class, 'index'])->name('tracks.index');
            Route::get('/tracks/create', [TracksController::class, 'create'])->name('tracks.create');
            Route::post('/tracks', [TracksController::class, 'store'])->name('tracks.store');
            Route::get('/tracks/{track}', [TracksController::class, 'show'])->name('tracks.show');
            Route::delete('/tracks/{track}', [TracksController::class, 'destroy'])->name('tracks.destroy');
            Route::get('/tracks/export/csv', [TracksController::class, 'exportCsv'])->name('tracks.export.csv');
            Route::get('/tracks/export/pdf', [TracksController::class, 'exportPdf'])->name('tracks.export.pdf');

            // SEARCH
            Route::get('/search', [SearchController::class, 'index'])->name('search.index');
            Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');

            // PAYMENTS (FIXED - Changed {id} to {payment} for model binding)
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [AdminPaymentsController::class, 'index'])->name('index');
                Route::get('/{payment}', [AdminPaymentsController::class, 'show'])->name('show');
                Route::post('/{payment}/approve', [AdminPaymentsController::class, 'approve'])->name('approve');
                Route::post('/{payment}/reject', [AdminPaymentsController::class, 'reject'])->name('reject');
                Route::post('/{payment}/complete', [AdminPaymentsController::class, 'complete'])->name('complete');
                Route::post('/bulk-action', [AdminPaymentsController::class, 'bulkAction'])->name('bulk');
                Route::get('/export/csv', [AdminPaymentsController::class, 'export'])->name('export');
            });
        });
    });