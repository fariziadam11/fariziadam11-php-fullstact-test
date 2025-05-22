<?php

use App\Http\Controllers\WebClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Client CRUD routes
Route::resource('clients', WebClientController::class);

// Redirect root to clients index
Route::get('/dashboard', function () {
    return redirect()->route('clients.index');
});
