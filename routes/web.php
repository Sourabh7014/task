<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [InvitationController::class, 'index'])->name('users.index');
    Route::post('/invite', [InvitationController::class, 'invite'])->name('invitation.invite');

    Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
    Route::post('/urls', [ShortUrlController::class, 'create'])->name('urls.create');

    Route::get('/export/urls', [App\Http\Controllers\ExportController::class, 'exportUrls'])->name('export.urls');
    Route::get('/export/users', [App\Http\Controllers\ExportController::class, 'exportUsers'])->name('export.users');
});

Route::get('/s/{code}', [ShortUrlController::class, 'resolve'])->name('urls.resolve');
