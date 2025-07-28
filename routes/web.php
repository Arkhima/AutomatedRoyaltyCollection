<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Artists\DashboardController;
use App\Http\Controllers\Artists\TrackController;
use App\Http\Controllers\Artists\ArtistController;
use App\Http\Controllers\Artists\StreamController;
use App\Http\Controllers\Artists\RoyaltyController;
use App\Http\Controllers\Artists\ReportController;
use App\Http\Controllers\Artists\PaymentController;

Route::get('/', function () {

    return view('Artists.Dashboard.index');
});

    //--!ARTIST-DashboardROUTES--//

    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('Artists.Dashboard.index');

    //--!Track Routes--//
    Route::resource('/Tracks', TrackController::class)->names([
        'index' => 'Artists.Tracks.index',
        'create' => 'Artists.Tracks.create',
        'store' => 'Artists.Tracks.store',
        'edit' => 'Artists.Tracks.edit',
        'update' => 'Artists.Tracks.update',
        'destroy' => 'Artists.Tracks.destroy'

    
]);
    //--!Artist Routes--//
    Route::resource('/Artists', ArtistController::class)->names([
    'index' => 'Artists.ArtistSection.index',
    'create' => 'Artists.ArtistSection.create',
    'store' => 'Artists.ArtistSection.store',
    'edit' => 'Artists.ArtistSection.edit',
    'update' => 'Artists.ArtistSection.update',
    'destroy' => 'Artists.ArtistSection.destroy'
]);

    Route::post('/streams/import', [StreamController::class, 'upload'])
            ->name('Artists.streams.import');

    Route::get('/Streams', [StreamController::class, 'index'])
            ->name('Artists.Streams.index');


    Route::get('/Royalties', [RoyaltyController::class, 'index'])
             ->name('Artists.Royalties.index');

    Route::get('/Reports', [ReportController::class, 'index'])
            ->name('Artists.Reports.index');

    Route::get('/Reports', [ReportController::class, 'index'])->name('Artists.Reports.index');
    Route::get('/Reports/pdf', [ReportController::class, 'exportPdf'])->name('Artists.Reports.exportPdf');
    Route::get('/Reports/excel', [ReportController::class, 'exportExcel'])->name('Artists.Reports.exportExcel');


    Route::get('/Payments', [PaymentController::class, 'index'])->name('Artists.Payments.index');
    Route::post('/Payments/request', [PaymentController::class, 'request'])->name('Artists.Payments.request');