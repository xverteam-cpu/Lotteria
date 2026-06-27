<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package' => 'required|in:starter,professional,premium,enterprise',
        ]);

        $user = User::findOrFail($request->user_id);

        // Define package details
        $packages = [
            'starter' => ['name' => 'Starter', 'amount' => 100],
            'professional' => ['name' => 'Professional', 'amount' => 500],
            'premium' => ['name' => 'Premium', 'amount' => 1000],
            'enterprise' => ['name' => 'Enterprise', 'amount' => 5000],
        ];

        $package = $packages[$request->package];

        // Create an investment record (activated package)
        Investment::create([
            'user_id' => $user->id,
            'package_name' => $package['name'],
            'amount' => $package['amount'],
            'payment_method' => 'admin_transfer',
            'status' => 'approved',
        ]);

        return redirect()->route('admin.dashboard')
            ->with('status', "Package '{$package['name']}' sent to {$user->name} successfully!");
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
