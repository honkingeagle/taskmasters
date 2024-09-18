<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('welcome');

Route::get('profile/{user}', [ProfileController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('profile.show');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('assignments', AssignmentController::class)
    ->only(['create', 'edit', 'show'])
    ->middleware(['auth', 'verified', 'client']);

Route::resource('bids', BidController::class)
    ->only(['show'])
    ->middleware(['auth', 'verified', 'freelancer']);

Route::get('bids/{assignment}/create', [BidController::class, 'create'])
    ->name('bids.create')
    ->middleware(['auth', 'verified', 'freelancer']);

Route::view('/update-profile', 'profile')
    ->middleware(['auth'])
    ->name('update-profile');

require __DIR__.'/auth.php';
