<?php

namespace App\Http\Controllers;

use App\Support\InvestmentPackages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvestmentController extends Controller
{
    public function store(Request $request): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'package' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:bank_transfer,account_balance,crypto'],
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

        if ($data['payment_method'] === 'account_balance' && $request->user()->balance < (float) $data['amount']) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient account balance for this purchase.',
            ]);
        }

        $isPending = $data['payment_method'] === 'bank_transfer';

        $investment = $request->user()->investments()->create([
            'package_key' => $data['package'],
            'package_name' => $package['name'],
            'package_price' => $package['price'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'daily_interest_rate' => $package['daily_interest_rate'],
            'duration_days' => $package['duration_days'],
            'starts_at' => $isPending ? null : now(),
            'status' => $isPending ? 'pending' : 'approved',
        ]);

        if ($data['payment_method'] === 'account_balance') {
            $user = $request->user();
            $user->balance = max(0, ($user->balance ?? 0) - (float) $data['amount']);
            $user->save();
        }

        // If investment is immediately approved, process referral commission
        if ($investment->status === 'approved') {
            $investment->processReferralCommission();
        }

        $message = $isPending
            ? $package['name'].' investment has been submitted and is pending admin approval.'
            : $package['name'].' investment has been activated successfully.';

        if ($request->expectsJson() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message,
                'investment' => [
                    'id' => $investment->id,
                    'package_key' => $investment->package_key,
                    'package_name' => $investment->package_name,
                    'amount' => (float) $investment->amount,
                    'daily_interest_rate' => (float) $investment->daily_interest_rate,
                    'duration_days' => (int) $investment->duration_days,
                    'payment_method' => $investment->payment_method,
                    'status' => $investment->status,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('status', $message);
    }
}
