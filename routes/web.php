<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InvestmentApprovalController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PinController;
use App\Http\Middleware\RestrictUserAccess;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use App\Support\CurrencyRateService;
use App\Support\DailyInterestAccrualService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.update');

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

    $user = Auth::user();
    DailyInterestAccrualService::accrueDueInterestForUser($user);

    $showRafflePopup = false;
    if ($user->shouldShowRafflePopup()) {
        $user->markRafflePopupShown();
        $showRafflePopup = true;
    }

    $recentInvestments = Investment::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    $recentWithdrawals = Withdrawal::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    $dailyInterest = $user->investments()
        ->where('status', 'approved')
        ->get()
        ->sum(fn ($investment) => $investment->dailyInterestAmount());

    if (! is_numeric($dailyInterest)) {
        $dailyInterest = 0;
    }

    $notifications = collect();

    if ($dailyInterest > 0) {
        $notifications->push([
            'id' => 'daily-interest',
            'title' => 'Daily interest credited',
            'description' => '$'.number_format($dailyInterest, 2).' added to available balance.',
            'time' => now(),
        ]);
    }

    foreach ($recentInvestments as $investment) {
        $description = match ($investment->payment_method) {
            'admin_transfer' => 'Package sent by admin: '.$investment->package_name.'.',
            'account_balance' => 'Investment purchased from your balance: '.$investment->package_name.'.',
            'bank_transfer' => 'Investment submitted and pending approval: '.$investment->package_name.'.',
            default => 'Investment activity: '.$investment->package_name.'.',
        };

        $notifications->push([
            'id' => 'investment-'.$investment->id,
            'title' => 'Investment update',
            'description' => $description,
            'time' => $investment->created_at,
        ]);
    }

    foreach ($recentWithdrawals as $withdrawal) {
        $notifications->push([
            'id' => 'withdrawal-'.$withdrawal->id,
            'title' => 'Withdrawal request',
            'description' => '₱'.number_format($withdrawal->amount, 2).' '.$withdrawal->status.'.',
            'time' => $withdrawal->created_at,
        ]);
    }

    $notifications = $notifications
        ->sortByDesc('time')
        ->values()
        ->take(5);

    $notificationsRead = $user->getNotificationsReadIds();
    $unreadCount = $notifications->reject(fn ($notification) => in_array($notification['id'], $notificationsRead, true))->count();

    return view('dashboard_user', [
        'user' => $user,
        'showRafflePopup' => $showRafflePopup,
        'notifications' => $notifications,
        'notificationsRead' => $notificationsRead,
        'unreadCount' => $unreadCount,
    ]);
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('dashboard');

Route::post('/notifications/read-all', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'ids' => ['required', 'array'],
        'ids.*' => ['string'],
    ]);

    $request->user()->markNotificationsRead($data['ids']);

    return response()->json(['status' => 'success']);
})->middleware(['auth', 'pin'])->name('notifications.read_all');

Route::get('/rewards', function () {
    $user = Auth::user();

    return view('rewards', [
        'user' => $user,
        'signupBonusClaimed' => ! empty($user->signup_bonus_claimed_at),
        'signupBonusAmount' => 5,
    ]);
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('rewards');

Route::post('/rewards/claim-signup-bonus', function (Illuminate\Http\Request $request) {
    $user = $request->user();

    if (! empty($user->signup_bonus_claimed_at)) {
        return redirect()->route('rewards')->with('status', 'You already claimed your $5 sign up bonus.');
    }

    DB::transaction(function () use ($user): void {
        $user->forceFill([
            'balance' => (float) ($user->balance ?? 0) + 5,
            'signup_bonus_claimed_at' => now(),
        ])->save();

        App\Models\Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 5,
            'payment_method' => 'account_balance',
            'bank_name' => 'Welcome Bonus',
            'account_number' => 'signup-bonus',
            'account_holder' => 'Sign Up Bonus',
            'status' => 'approved',
        ]);
    });

    return redirect()->route('rewards')->with('status', 'Your $5 sign up bonus has been added to your available balance.');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('rewards.claim-signup-bonus');

// Deposit page for buying shares
Route::get('/deposit', function () {
    return view('deposit');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('deposit');

Route::get('/invest', function () {
    $meta = CurrencyRateService::latestUsdToPhpWithMeta();
    $user = Auth::user();
    $totalInvestment = $user ? (float) $user->investments()->sum('amount') : 0;

    return view('invest', [
        'phpRate' => $meta['rate'],
        'phpRateUpdatedAt' => $meta['updated_at'],
        'packageSlots' => App\Support\InvestmentPackages::currentSlots(),
        'totalInvestment' => $totalInvestment,
    ]);
})->name('invest');

// User actions (authenticated)
Route::get('/send', function () {
    return view('send');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('send');

Route::get('/withdraw', function () {
    $user = Auth::user();
    DailyInterestAccrualService::accrueDueInterestForUser($user);
    $user->refresh();

    $investments = $user->investments()->latest()->get();
    $earnedIncome = $investments->sum(fn ($investment) => $investment->earnedInterest());
    $availableBalance = (float) $user->balance + $earnedIncome;

    $recentWithdrawals = Withdrawal::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    return view('withdraw', [
        'availableBalance' => $availableBalance,
        'recentWithdrawals' => $recentWithdrawals,
    ]);
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('withdraw');

Route::post('/withdrawals', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'amount' => ['required', 'numeric', 'min:20', 'max:500'],
        'bank_name' => ['required', 'string', 'max:255'],
        'account_number' => ['required', 'string', 'max:255'],
        'account_holder' => ['required', 'string', 'max:255'],
    ]);

    $user = $request->user();

    DailyInterestAccrualService::accrueDueInterestForUser($user);
    $user->refresh();

    $investments = $user->investments()->latest()->get();
    $availableBalance = (float) $user->balance + $investments->sum(fn ($investment) => $investment->earnedInterest());

    if ($availableBalance < (float) $data['amount']) {
        throw Illuminate\Validation\ValidationException::withMessages([
            'amount' => 'Insufficient balance for this withdrawal request.',
        ]);
    }

    DB::transaction(function () use ($user, $data): void {
        $user->update([
            'bank_name' => $data['bank_name'],
            'bank_account_number' => $data['account_number'],
            'bank_account_holder' => $data['account_holder'],
        ]);

        App\Models\Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'payment_method' => 'bank_transfer',
            'bank_name' => $data['bank_name'],
            'account_number' => $data['account_number'],
            'account_holder' => $data['account_holder'],
            'status' => 'pending',
        ]);

        $user->balance = max(0, ($user->balance ?? 0) - (float) $data['amount']);
        $user->save();
    });

    return redirect()->route('withdraw')->with('status', 'Withdrawal request submitted successfully.');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('withdrawals.store');

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
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('history');

Route::get('/referrals', function () {
    return view('referrals');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('referrals');

Route::get('/cards', function () {
    return view('cards');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('cards');

Route::get('/loan', function () {
    return view('loan');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('loan');

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('profile');

Route::get('/profile/edit', function () {
    return view('profile-edit');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('profile.edit');

Route::get('/profile/password', function () {
    return view('change-password');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('profile.password');

Route::get('/profile/notifications', function () {
    return view('notification-settings');
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('profile.notifications');

Route::post('/investments', [InvestmentController::class, 'store'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('investments.store');

Route::get('/admin/dashboard', function () {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->index(request());
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('admin.dashboard');

Route::get('/admin/users/{user}', function (User $user) {
    abort_unless(Auth::user()?->is_admin, 403);

    return app(UserManagementController::class)->show($user);
})->middleware(['auth', 'pin', RestrictUserAccess::class])->name('admin.users.show');

Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.users.destroy');

Route::post('/admin/users/{user}/restrict', [UserManagementController::class, 'restrict'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.users.restrict');

Route::get('/admin/investments', [InvestmentApprovalController::class, 'index'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.investments');

Route::get('/admin/investments/{investment}', [InvestmentApprovalController::class, 'show'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.investments.show');

Route::post('/admin/investments/{investment}/approve', [InvestmentApprovalController::class, 'approve'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.investments.approve');

Route::get('/admin/withdrawals', [WithdrawalController::class, 'index'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.withdrawals');

Route::get('/admin/withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.withdrawals.show');

Route::post('/admin/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.withdrawals.approve');

Route::post('/admin/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.withdrawals.reject');

Route::post('/admin/investments/{investment}/reject', [InvestmentApprovalController::class, 'reject'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.investments.reject');

Route::post('/admin/send-package', [UserManagementController::class, 'sendPackage'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.send-package');

Route::post('/admin/package-slots', [UserManagementController::class, 'updatePackageSlots'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.package-slots.update');

Route::post('/admin/send-funds', [UserManagementController::class, 'sendFunds'])
    ->middleware(['auth', 'pin', RestrictUserAccess::class])
    ->name('admin.send-funds');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
