<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PinController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (! Auth::user()->pin_hash) {
            return redirect()->route('pin.setup');
        }

        if (! (bool) session()->get('pin_verified', false)) {
            return redirect()->route('pin.login');
        }

        return redirect()->route(Auth::user()->is_admin ? 'admin.dashboard' : 'dashboard');
    }

    if (request()->cookie('lotteria_pin_user')) {
        return redirect()->route('pin.login');
    }

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

Route::get('/pin/setup', [PinController::class, 'setup'])->middleware('auth')->name('pin.setup');
Route::post('/pin/setup', [PinController::class, 'store'])->middleware('auth')->name('pin.store');
Route::get('/pin/login', [PinController::class, 'login'])->name('pin.login');
Route::post('/pin/login', [PinController::class, 'verify'])->name('pin.verify');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::get('/franchising', function () {
    return redirect()->route('signup');
})->name('franchising');

Route::get('/unavailable', function () {
    return view('unavailable');
})->name('unavailable');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'pin'])->name('dashboard');

Route::get('/invest', function () {
    return view('invest');
})->middleware(['auth', 'pin'])->name('invest');

Route::post('/investments', [InvestmentController::class, 'store'])
    ->middleware(['auth', 'pin'])
    ->name('investments.store');

Route::get('/admin/dashboard', function () {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->index(request());
})->middleware(['auth', 'pin'])->name('admin.dashboard');

Route::get('/admin/users/{user}', function (User $user) {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->show($user);
})->middleware(['auth', 'pin'])->name('admin.users.show');
