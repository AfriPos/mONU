<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\macPrefixesController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\routerController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {

    Route::get('/test', function () {
        return response()->json(['message' => 'API is working']);
    });

    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('/orders', [PayPalController::class, 'createOrder']);
    Route::post('/orders/{orderId}/capture', [PayPalController::class, 'captureOrder']);
    
    // Protected routes (for authenticated users)
    Route::middleware('auth:sanctum')->group(function () {
        // fetch router models
        Route::post('/routermodels', [routerController::class, 'routermodels']);
        // fetch specific router model
        Route::post('/routermodel/{routerModel}', [routerController::class, 'show']);
        // router configurations
        Route::post('/router-configurations', [routerController::class, 'routerconfigurations']);
        // get unique issues
        Route::post('/issues-types', [routerController::class, 'getIssues']);

        // fetch mac prefixes
        Route::post('/mac-prefixes', [macPrefixesController::class, 'getMacPrefixes']);
        // fetch base mac
        Route::post('/base-mac', [macPrefixesController::class, 'getBaseMac']);
        Route::post('/base-mac/cancel', [macPrefixesController::class, 'cancelBaseMac']);
        // assign mac addresses from batch
        Route::post('/config-success', [macPrefixesController::class, 'configSuccess']);



        // Logout
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });
});
