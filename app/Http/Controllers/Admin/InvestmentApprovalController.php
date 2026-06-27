<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvestmentApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()?->is_admin, 403);
            return $next($request);
        });
    }
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', 'pending');

        $investments = Investment::query()
            ->with(['user', 'approver'])
            ->where('status', $status)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('package_name', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $pendingCount = Investment::where('status', 'pending')->count();
        $approvedCount = Investment::where('status', 'approved')->count();
        $rejectedCount = Investment::where('status', 'rejected')->count();

        return view('admin.investments', [
            'investments' => $investments,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(Investment $investment): View
    {
        return view('admin.investment-show', [
            'investment' => $investment->load(['user', 'approver']),
        ]);
    }

    public function approve(Investment $investment, Request $request): RedirectResponse
    {
        if ($investment->status !== 'pending') {
            return back()
                ->withErrors(['error' => 'Only pending investments can be approved.']);
        }

        $investment->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'starts_at' => now(),
        ]);

        // After approving, credit referral commission where applicable
        $investment->refresh()->processReferralCommission();

        return redirect()
            ->back()
            ->with('status', 'Investment approved successfully.');
    }

    public function reject(Investment $investment, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        if ($investment->status !== 'pending') {
            return back()
                ->withErrors(['error' => 'Only pending investments can be rejected.']);
        }

        $investment->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return redirect()
            ->back()
            ->with('status', 'Investment rejected successfully.');
    }
}
