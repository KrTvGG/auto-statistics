<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MainController;

Route::get('/', function () {
    return Inertia::render('index');
})->name('home');

Route::get('/refill', [MainController::class, 'indexRefill'])->name('refill.index');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
