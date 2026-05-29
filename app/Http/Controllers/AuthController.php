<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'The provided login details are incorrect.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->forget('pin_verified');
        Cookie::queue('lotteria_pin_user', (string) Auth::id(), 60 * 24 * 30);

        return redirect()->route(Auth::user()->pin_hash ? 'pin.login' : 'pin.setup');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:80'],
            'message' => ['nullable', 'string', 'max:2000'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['fullname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'region' => $data['region'],
            'message' => $data['message'] ?? null,
            'password' => $data['password'],
            'is_admin' => strcasecmp($data['email'], env('ADMIN_EMAIL', 'xver.team@gmail.com')) === 0,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('pin_verified');
        Cookie::queue('lotteria_pin_user', (string) $user->id, 60 * 24 * 30);

        return redirect()->route('pin.setup');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pin.login');
    }
}
