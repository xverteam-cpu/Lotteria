<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\User;
use App\Support\InvestmentPackages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(Auth::user()?->is_admin, 403);

            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('region', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('CASE WHEN last_seen_at IS NOT NULL AND last_seen_at >= ? THEN 1 ELSE 0 END DESC', [now()->subMinutes(5)])
            ->orderByDesc('last_seen_at')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $allUsers = User::query()->get();

        return view('admin.dashboard', [
            'users' => $users,
            'totalUsers' => $allUsers->count(),
            'newUsersCount' => $allUsers->where('created_at', '>=', now()->subDay())->count(),
            'adminUsersCount' => $allUsers->where('is_admin', true)->count(),
            'onlineUsersCount' => $allUsers->filter->isOnline()->count(),
            'search' => $search,
            'packages' => InvestmentPackages::all(),
            'packageSlots' => InvestmentPackages::currentSlots(),
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.user-show', [
            'managedUser' => $user,
        ]);
    }

    public function sendPackage(Request $request)
    {
        $packages = InvestmentPackages::all();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package' => 'required|in:'.implode(',', array_keys($packages)),
        ]);

        $user = User::findOrFail($request->user_id);
        $package = $packages[$request->package];

        $created = DB::transaction(function () use ($user, $package, $request) {
            if (! InvestmentPackages::reserveSlot($request->package)) {
                return false;
            }

            $investment = Investment::create([
                'user_id' => $user->id,
                'package_key' => $request->package,
                'package_name' => $package['name'],
                'package_price' => $package['price'],
                'amount' => $package['price'],
                'daily_interest_rate' => $package['daily_interest_rate'],
                'duration_days' => $package['duration_days'],
                'payment_method' => 'admin_transfer',
                'status' => 'approved',
                'starts_at' => now(),
            ]);

            $investment->processReferralCommission();

            return true;
        });

        if (! $created) {
            return redirect()->route('admin.dashboard')
                ->withErrors(['package' => "No remaining slots available for {$package['name']}."]);
        }

        return redirect()->route('admin.dashboard')
            ->with('status', "Package '{$package['name']}' sent to {$user->name} successfully!");
    }

    public function updatePackageSlots(Request $request)
    {
        $defaultPackageKeys = array_keys(InvestmentPackages::defaults());
        $rules = ['slots' => 'required|array'];

        foreach ($defaultPackageKeys as $key) {
            $rules["slots.{$key}"] = 'required|integer|min:0';
        }

        $validated = $request->validate($rules);

        foreach ($validated['slots'] as $packageKey => $remainingSlots) {
            InvestmentPackages::setRemainingSlots($packageKey, $remainingSlots);
        }

        return redirect()->route('admin.dashboard')
            ->with('status', 'Package slot counts updated successfully.');
    }

    public function sendFunds(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($request->user_id);
        $amount = (float) $request->amount;

        // Add funds to user balance
        $user->balance += $amount;
        $user->save();

        return redirect()->route('admin.dashboard')
            ->with('status', "Sent \${$amount} to {$user->name} successfully! New balance: \${$user->balance}");
    }
}
