<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartOperationController;
use App\Http\Controllers\PoProductionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('po/{poId}/detail',[PoProductionController::class,'getDetail'])->name('po-productions.getDetail');

Route::prefix('part-operations')->name('api.part-operations.')->group(function () {

        // Get existing routes for a part
        Route::get('existing-routes/{partId}', [PartOperationController::class, 'getExistingRoutes'])
            ->name('existing-routes');

        // Get operations summary
        Route::get('summary', [PartOperationController::class, 'getSummary'])
            ->name('summary');

        // Get routing flow for a part
        Route::get('routing-flow/{partId}', [PartOperationController::class, 'getRoutingFlow'])
            ->name('routing-flow');
    });
