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
}
