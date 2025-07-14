<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Storage;


Route::middleware(RedirectIfAuthenticated::class)->group(function () {
    Route::view('/', 'login')->name('login');
    Route::view('register', 'register')->name('register');

    Route::prefix('account')->name('acc.')->group(function () {
        Route::post('login', [AuthController::class, 'accountLogin'])->name('login');

        Route::post('create', [UserController::class, 'create'])->name('create');
    });
});


Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('acc.logout');
    Route::view('home', 'tasks')->name('tasks');

    Route::resource('tasks', TaskController::class);

    Route::get('tasks/download/photos/{filename}', function ($filename) {
        $path = 'photos/' . $filename; // folder inside storage/app/public

        if (!Storage::disk('public')->exists($path)) {
            return view('404');
        }

        return Storage::disk('public')->download($path);
    })->name('tasks.download');
});
