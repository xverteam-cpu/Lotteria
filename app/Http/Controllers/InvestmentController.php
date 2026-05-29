<?php

namespace App\Http\Controllers;

use App\Support\InvestmentPackages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvestmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'package' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $package = InvestmentPackages::find($data['package']);

        if (! $package) {
            throw ValidationException::withMessages([
                'package' => 'Please select a valid package.',
            ]);
        }

        if ((float) $data['amount'] < $package['price']) {
            throw ValidationException::withMessages([
                'amount' => 'Minimum investment for '.$package['name'].' is $'.number_format($package['price'], 2).'.',
            ]);
        }

        $request->user()->investments()->create([
            'package_key' => $data['package'],
            'package_name' => $package['name'],
            'package_price' => $package['price'],
            'amount' => $data['amount'],
            'daily_interest_rate' => $package['daily_interest_rate'],
            'duration_days' => $package['duration_days'],
            'starts_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', $package['name'].' investment has been submitted.');
    }
}
