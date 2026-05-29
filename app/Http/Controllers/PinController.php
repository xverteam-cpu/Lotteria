<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PinController extends Controller
{
    public function setup(Request $request): View|RedirectResponse
    {
        if ($request->user()->pin_hash) {
            return redirect()->route('pin.login');
        }

        return view('pin', [
            'mode' => 'setup',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pin' => ['required', 'digits:4'],
        ]);

        $request->user()->forceFill([
            'pin_hash' => Hash::make($data['pin']),
            'pin_set_at' => now(),
        ])->save();

        $request->session()->put('pin_verified', true);
        Cookie::queue('lotteria_pin_user', (string) $request->user()->id, 60 * 24 * 30);

        return redirect()->intended(
            $request->user()->is_admin ? route('admin.dashboard') : route('dashboard')
        );
    }

    public function login(Request $request): View|RedirectResponse
    {
        $user = $this->pinUser($request);

        if (! $user) {
            return redirect()->route('order');
        }

        if (! $user->pin_hash) {
            if (! Auth::check()) {
                Auth::login($user);
                $request->session()->regenerate();
            }

            return redirect()->route('pin.setup');
        }

        if (Auth::check() && (bool) $request->session()->get('pin_verified', false)) {
            return redirect()->route($user->is_admin ? 'admin.dashboard' : 'dashboard');
        }

        return view('pin', [
            'mode' => 'login',
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pin' => ['required', 'digits:4'],
        ]);

        $user = $this->pinUser($request);

        if (! $user) {
            return redirect()->route('order');
        }

        if (! Hash::check($data['pin'], $user->pin_hash)) {
            return back()
                ->withErrors(['pin' => 'The PIN you entered is incorrect.'])
                ->onlyInput('pin');
        }

        if (! Auth::check()) {
            Auth::login($user);
            $request->session()->regenerate();
        }

        $request->session()->put('pin_verified', true);
        Cookie::queue('lotteria_pin_user', (string) $user->id, 60 * 24 * 30);

        return redirect()->intended(
            $user->is_admin ? route('admin.dashboard') : route('dashboard')
        );
    }

    private function pinUser(Request $request): User|Authenticatable|null
    {
        if ($request->user()) {
            return $request->user();
        }

        $rememberedUserId = $request->cookie('lotteria_pin_user');

        if (! $rememberedUserId) {
            return null;
        }

        return User::find($rememberedUserId);
    }
}
