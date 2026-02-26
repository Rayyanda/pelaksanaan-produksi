<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PoProductionController;
use App\Http\Controllers\PartInternalController;
use App\Http\Controllers\PartOperationController;
use App\Http\Controllers\DivisionProductionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WipTrackingController;
use App\Http\Controllers\ProductionScheduleController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('home.main');
});

require __DIR__ . '/auth.php';

// ============================================
// GUEST ROUTES (belum login)
// ============================================
Route::middleware('guest')->group(function () {

    // GET - Show Forms (Custom Views)
    Route::get('login', [AuthViewController::class, 'showLogin'])
        ->name('login');

    Route::get('register', [AuthViewController::class, 'showRegister'])
        ->name('register');

    Route::get('forgot-password', [AuthViewController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::get('reset-password/{token}', [AuthViewController::class, 'showResetPassword'])
        ->name('password.reset');

    // POST - Handle Logic (Breeze Controllers)
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::prefix('home')->group(function () {

        Route::get('/', function () {
            return view('home.main');
        })->name('home.main');

        Route::get('/products', function () {
            return view('home.products');
        })->name('home.products');

        Route::get('/divisions', function () {
            return view('home.divisions');
        })->name('home.divisions');

        Route::get('/facilities', function () {
            return view('home.facilities');
        })->name('home.facilities');

        Route::get('/gallery', function () {
            return view('home.galleries');
        })->name('home.gallery');
    });
});

// ============================================
// Auth ROUTES (sudah login)
// ============================================


Route::middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    //part internal
    Route::resource('part-internals', PartInternalController::class);

    // Custom route for storing part with operations
    Route::post('part-internals/store-with-operations', [PartInternalController::class, 'storeWithOperations'])
        ->name('part-internals.store-with-operations');

    // Custom route for updating part with operations
    Route::put('part-internals/{partInternal}/update-with-operations', [PartInternalController::class, 'updateWithOperations'])
        ->name('part-internals.update-with-operations');

    /*

    

|--------------------------------------------------------------------------
| WIP Trackings Routes
|--------------------------------------------------------------------------
*/
    Route::resource('wip-trackings', WipTrackingController::class)->only([
        'index',
        'show'
    ]);

    Route::prefix('wip-trackings')->name('wip-trackings.')->group(function () {
        // Start operation
        Route::post('{wipTracking}/start', [WipTrackingController::class, 'start'])
            ->name('start');

        // Complete operation
        Route::post('{wipTracking}/complete', [WipTrackingController::class, 'complete'])
            ->name('complete');

        // Reset to waiting
        Route::post('{wipTracking}/reset', [WipTrackingController::class, 'reset'])
            ->name('reset');

        // Update notes
        Route::post('{wipTracking}/update-notes', [WipTrackingController::class, 'updateNotes'])
            ->name('update-notes');

        // Change step
        Route::post('{wipTracking}/change-step', [WipTrackingController::class, 'changeStep'])
            ->name('change-step');

        // Bulk update status
        Route::post('bulk-update-status', [WipTrackingController::class, 'bulkUpdateStatus'])
            ->name('bulk-update-status');

        // Export
        Route::get('export', [WipTrackingController::class, 'export'])
            ->name('export');

        // Summary by division
        Route::get('summary-by-division', [WipTrackingController::class, 'summaryByDivision'])
            ->name('summary-by-division');
    });

    Route::prefix('divisions')->name('divisions-production.')->group(function () {
        Route::get('/{divisionId}/dashboard', [DivisionProductionController::class, 'dashboard'])
            ->name('dashboard');

        // Get WIP summary for division
        Route::get('{divisionId}/wip-summary', [DivisionProductionController::class, 'wipSummary'])
            ->name('wip-summary');

        // Get active batches for division
        Route::get('{divisionId}/active-batches', [DivisionProductionController::class, 'activeBatches'])
            ->name('active-batches');

        // Get performance metrics
        Route::get('{divisionId}/performance-metrics', [DivisionProductionController::class, 'performanceMetrics'])
            ->name('performance-metrics');
    });


    Route::resource('po-productions', PoProductionController::class);
    Route::post('po-productions/bulk-delete', [PoProductionController::class, 'bulkDelete'])->name('po-productions.bulk-delete');
    Route::post('po-productions/{poProduction}/refresh-snapshot', [PoProductionController::class, 'refreshSnapshot'])->name('po-productions.refresh-snapshot');
    Route::get('po-productions/{poProduction}/snapshot', [PoProductionController::class, 'getSnapshot'])->name('po-productions.snapshot');
    Route::post('po-productions/{id}/restore', [PoProductionController::class, 'restore'])->name('po-productions.restore');
    Route::delete('po-productions/{id}/force-delete', [PoProductionController::class, 'forceDelete'])->name('po-productions.force-delete');
    Route::get('po-productions-export', [PoProductionController::class, 'export'])->name('po-productions.export');

    Route::get('/batches/timeline', [App\Http\Controllers\BatchController::class, 'batchesTimeline'])->name('batches.timeline');

    //batch routes
    Route::resource('batches', App\Http\Controllers\BatchController::class);
    Route::patch('batches/{batch}/approve', [App\Http\Controllers\BatchController::class, 'approve'])->name('batches.approve');
    //export & export single
    Route::get('batches-export-pdf/{batch}', [App\Http\Controllers\BatchController::class, 'exportPDF'])->name('batches.export-pdf');
    Route::get('batches/{batch}/export-single', [App\Http\Controllers\BatchController::class, 'exportSingle'])->name('batches.export-single');

    // Show Schedule Detail (dari database)
    Route::get('/production-schedules/{batchId}/detail', [ProductionScheduleController::class, 'showDetail'])
        ->name('production-schedules.detail');

    // Index (opsional - untuk list semua schedules)
    Route::get('/production-schedules', [ProductionScheduleController::class, 'index'])
        ->name('production-schedules.index');

    // Update urgent status
    Route::patch('production-schedules/{id}/update-urgent', [ProductionScheduleController::class, 'updateUrgent'])
        ->name('production-schedules.update-urgent');
});

Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {
    //admin routes here





    //division
    Route::resource('divisions', App\Http\Controllers\DivisionController::class);

    //production calendars
    Route::resource('production_calendars', App\Http\Controllers\ProductionCalendarController::class);
    // Division API

    // Resource routes for Part Operations
    Route::resource('part-operations', PartOperationController::class);

    // Additional routes for Part Operations
    Route::prefix('part-operations')->name('part-operations.')->group(function () {

        // Bulk store (must be before resource routes)
        Route::post('store-bulk', [PartOperationController::class, 'storeBulk'])
            ->name('store-bulk');

        // Bulk edit & update
        Route::get('edit-bulk/{partId}', [PartOperationController::class, 'editBulk'])
            ->name('edit-bulk');
        Route::put('update-bulk/{partId}', [PartOperationController::class, 'updateBulk'])
            ->name('update-bulk');

        // Duplicate operation
        Route::post('{partOperation}/duplicate', [PartOperationController::class, 'duplicate'])
            ->name('duplicate');

        // Bulk delete
        Route::post('bulk-delete', [PartOperationController::class, 'bulkDelete'])
            ->name('bulk-delete');

        // Restore soft deleted
        Route::post('{id}/restore', [PartOperationController::class, 'restore'])
            ->name('restore');

        // Force delete
        Route::delete('{id}/force-delete', [PartOperationController::class, 'forceDelete'])
            ->name('force-delete');

        // Export to CSV
        Route::get('export', [PartOperationController::class, 'export'])
            ->name('export');

        // Reorder operations for a part
        Route::post('reorder/{partId}', [PartOperationController::class, 'reorder'])
            ->name('reorder');
    });

    //users managemtent
    Route::resource('users', App\Http\Controllers\UserController::class);






    //export
    Route::get('wip-trackings-export', [App\Http\Controllers\WipTrackingController::class, 'export'])->name('wip-trackings.export');

    /*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
    Route::resource('users', UserController::class);

    Route::prefix('users')->name('users.')->group(function () {
        // Toggle user status
        Route::post('{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('toggle-status');
    });
});
