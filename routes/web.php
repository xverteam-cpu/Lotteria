<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('splash');
});

Route::get('/home', function () {
    return view('lotteria');
})->name('home');

Route::get('/order', function () {
    return view('order');
})->name('order');

Route::get('/login', function () {
    return redirect()->route('order');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register-partner', [AuthController::class, 'register'])->name('register.partner');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/franchising', function () {
    return view('franchising');
})->name('franchising');

Route::get('/unavailable', function () {
    return view('unavailable');
})->name('unavailable');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/admin/dashboard', function () {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->index(request());
})->middleware('auth')->name('admin.dashboard');

Route::get('/admin/users/{user}', function (User $user) {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->show($user);
})->middleware('auth')->name('admin.users.show');
