<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return redirect()->intended(
            $request->user()->is_admin ? route('admin.dashboard') : route('dashboard')
        );
    }

    public function login(Request $request): View|RedirectResponse
    {
        if (! $request->user()->pin_hash) {
            return redirect()->route('pin.setup');
        }

        if ($request->session()->boolean('pin_verified')) {
            return redirect()->route($request->user()->is_admin ? 'admin.dashboard' : 'dashboard');
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

        if (! Hash::check($data['pin'], $request->user()->pin_hash)) {
            return back()
                ->withErrors(['pin' => 'The PIN you entered is incorrect.'])
                ->onlyInput('pin');
        }

        $request->session()->put('pin_verified', true);

        return redirect()->intended(
            $request->user()->is_admin ? route('admin.dashboard') : route('dashboard')
        );
    }
}
