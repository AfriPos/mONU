<?php

use App\Http\Controllers\macPrefixesController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\routerController;
use App\Http\Controllers\transactionsController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard')->middleware('permission:view finance');

Route::middleware('auth')->group(function () {
    // profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // user routes
    Route::get('/users', [userController::class, 'index'])->name('users.index')->middleware('permission:view users');
    Route::get('/users/create', [userController::class, 'create'])->name('users.create')->middleware('permission:create users');
    Route::post('/users', [userController::class, 'store'])->name('users.store')->middleware('permission:create users');
    Route::get('/users/{user}/edit', [userController::class, 'edit'])->name('users.edit')->middleware('permission:edit users');
    Route::put('/users/{user}', [userController::class, 'update'])->name('users.update')->middleware('permission:edit users');
    Route::delete('/users/{user}', [userController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete users');

    // mac management router
    Route::get('/mac-prefixes', [macPrefixesController::class, 'index'])->name('mac.index')->middleware('permission:view mac prefixes');
    Route::get('/mac-prefixes/create', [macPrefixesController::class, 'create'])->name('mac.create')->middleware('permission:create mac prefixes');
    Route::post('/mac-prefixes', [macPrefixesController::class, 'store'])->name('mac.store')->middleware('permission:create mac prefixes');
    Route::get('/mac-prefixes/{macPrefixesModel}', [macPrefixesController::class, 'show'])->name('mac.show')->middleware('permission:view mac prefixes');
    Route::post('/mac-prefixes/{macPrefixesModel}/deactivate', [macPrefixesController::class, 'deactivate'])->name('mac.deactivate')->middleware('permission:deactivate mac prefixes');
    Route::post('/mac-prefixes/{macPrefixesModel}/activate', [macPrefixesController::class, 'activate'])->name('mac.activate')->middleware('permission:activate mac prefixes');

    // roles routes
    Route::get('/roles', [rolesController::class, 'index'])->name('roles.index')->middleware('permission:view roles');
    Route::get('/roles/create', [rolesController::class, 'create'])->name('roles.create')->middleware('permission:create roles');
    Route::post('/roles', [rolesController::class, 'store'])->name('roles.store')->middleware('permission:create roles');
    Route::get('/roles/{role}/edit', [rolesController::class, 'edit'])->name('roles.edit')->middleware('permission:edit roles');
    Route::put('/roles/{role}', [rolesController::class, 'update'])->name('roles.update')->middleware('permission:edit roles');
    Route::delete('/roles/{role}', [rolesController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete roles');

    Route::get('/transactions', [transactionsController::class, 'index'])->name('transactions.index')->middleware('permission:view finance');

    Route::get('/routers', [routerController::class, 'index'])->name('routers.index')->middleware('permission:view routers');
});

require __DIR__ . '/auth.php';
