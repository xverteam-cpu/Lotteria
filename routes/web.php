<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InvestmentApprovalController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PinController;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use App\Support\CurrencyRateService;
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
    if (request()->cookie('lotteria_pin_user')) {
        return redirect()->route('pin.login');
    }

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
    return view('signup', ['referral' => request('ref')]);
})->name('signup');

Route::get('/franchising', function () {
    return view('franchising', ['referral' => request('ref')]);
})->name('franchising');

Route::get('/unavailable', function () {
    return view('unavailable');
})->name('unavailable');

Route::get('/dashboard', function () {
    if (Auth::user()?->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard_user');
})->middleware(['auth', 'pin'])->name('dashboard');

// Deposit page for buying shares
Route::get('/deposit', function () {
    return view('deposit');
})->middleware(['auth', 'pin'])->name('deposit');

Route::get('/invest', function () {
    $meta = CurrencyRateService::latestUsdToPhpWithMeta();
    return view('invest', [
        'phpRate' => $meta['rate'],
        'phpRateUpdatedAt' => $meta['updated_at'],
    ]);
})->name('invest');

// User actions (authenticated)
Route::get('/send', function () {
    return view('send');
})->middleware(['auth', 'pin'])->name('send');

Route::get('/withdraw', function () {
    return view('withdraw');
})->middleware(['auth', 'pin'])->name('withdraw');

Route::get('/history', function () {
    $user = Auth::user();

    $investments = Investment::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->get();

    $withdrawals = Withdrawal::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->get();

    $dailyInterest = $investments
        ->where('status', 'approved')
        ->sum(fn ($investment) => $investment->dailyInterestAmount());

    return view('history', [
        'investments' => $investments,
        'withdrawals' => $withdrawals,
        'dailyInterest' => $dailyInterest,
    ]);
})->middleware(['auth', 'pin'])->name('history');

Route::get('/referrals', function () {
    return view('referrals');
})->middleware(['auth', 'pin'])->name('referrals');

Route::get('/cards', function () {
    return view('cards');
})->middleware(['auth', 'pin'])->name('cards');

Route::get('/loan', function () {
    return view('loan');
})->middleware(['auth', 'pin'])->name('loan');

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'pin'])->name('profile');

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

Route::get('/admin/investments', [InvestmentApprovalController::class, 'index'])
    ->middleware(['auth', 'pin'])
    ->name('admin.investments');

Route::get('/admin/investments/{investment}', [InvestmentApprovalController::class, 'show'])
    ->middleware(['auth', 'pin'])
    ->name('admin.investments.show');

Route::post('/admin/investments/{investment}/approve', [InvestmentApprovalController::class, 'approve'])
    ->middleware(['auth', 'pin'])
    ->name('admin.investments.approve');

Route::get('/admin/withdrawals', [WithdrawalController::class, 'index'])
    ->middleware(['auth', 'pin'])
    ->name('admin.withdrawals');

Route::get('/admin/withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])
    ->middleware(['auth', 'pin'])
    ->name('admin.withdrawals.show');

Route::post('/admin/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])
    ->middleware(['auth', 'pin'])
    ->name('admin.withdrawals.approve');

Route::post('/admin/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])
    ->middleware(['auth', 'pin'])
    ->name('admin.withdrawals.reject');

Route::post('/admin/investments/{investment}/reject', [InvestmentApprovalController::class, 'reject'])
    ->middleware(['auth', 'pin'])
    ->name('admin.investments.reject');

Route::post('/admin/send-package', [UserManagementController::class, 'sendPackage'])
    ->middleware(['auth', 'pin'])
    ->name('admin.send-package');

Route::post('/admin/send-funds', [UserManagementController::class, 'sendFunds'])
    ->middleware(['auth', 'pin'])
    ->name('admin.send-funds');
