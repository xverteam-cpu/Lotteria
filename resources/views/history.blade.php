@extends('layouts.app')

@section('content')
<style>
  body { background: #f4f6fb !important; color: #12233c; }
  .history-shell { max-width: 940px; margin: 0 auto; padding: 24px 16px 40px; }
  .history-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
  .history-title { margin: 0; font-size: 28px; font-weight: 900; letter-spacing: -0.03em; }
  .history-copy { margin: 8px 0 0; color: #5b6b84; font-size: 14px; line-height: 1.6; }
  .summary-grid { display: grid; gap: 14px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 28px; }
  .summary-card { background: #fff; border-radius: 24px; padding: 24px; box-shadow: 0 18px 42px rgba(22, 45, 86, 0.08); border: 1px solid rgba(48, 73, 130, 0.08); }
  .summary-label { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.18em; color: #7c89a2; margin-bottom: 12px; }
  .summary-value { font-size: 32px; font-weight: 900; color: #131b2e; }
  .summary-note { margin-top: 10px; font-size: 13px; color: #55637c; line-height: 1.6; }
  .section { margin-bottom: 28px; }
  .section-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
  .section-title { margin: 0; font-size: 20px; font-weight: 900; color: #15203a; }
  .section-subtitle { margin: 0; color: #6d7a95; font-size: 13px; }
  .history-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 18px 42px rgba(22, 45, 86, 0.08); }
  .history-table th, .history-table td { padding: 16px 18px; text-align: left; border-bottom: 1px solid #f1f5fa; }
  .history-table th { background: #f7f9fd; color: #56627a; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; }
  .history-table td { color: #2a3860; font-size: 14px; }
  .history-table tbody tr:last-child td { border-bottom: none; }
  .badge { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; }
  .badge-approved { background: #d8f3e8; color: #116147; }
  .badge-pending { background: #fff4e6; color: #9b5900; }
  .badge-rejected { background: #fee2e2; color: #9b1c1c; }
  .empty-state { padding: 28px; border-radius: 20px; background: #fff; text-align: center; color: #5d6b83; box-shadow: 0 15px 38px rgba(22, 45, 86, 0.06); }
  .empty-state strong { color: #25304a; }
</style>

<main class="history-shell">
  <div class="history-header">
    <div>
      <h1 class="history-title">Account History</h1>
      <p class="history-copy">Review every investment and withdrawal transaction, plus the daily estimated interest earned on approved investments.</p>
    </div>
  </div>

  <div class="summary-grid">
    <div class="summary-card">
      <div class="summary-label">Daily Interest Earned</div>
      <div class="summary-value">${{ number_format($dailyInterest, 2) }}</div>
      <p class="summary-note">Sum of daily interest from all approved investments.</p>
    </div>
    <div class="summary-card">
      <div class="summary-label">Investment History</div>
      <div class="summary-value">{{ $investments->count() }}</div>
      <p class="summary-note">Total investment records for your account.</p>
    </div>
    <div class="summary-card">
      <div class="summary-label">Withdrawal Activity</div>
      <div class="summary-value">{{ $withdrawals->count() }}</div>
      <p class="summary-note">Total withdrawal requests made from this account.</p>
    </div>
  </div>

  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">Investment Transactions</h2>
        <p class="section-subtitle">All account investments, their amounts, and approval status.</p>
      </div>
    </div>

    @if ($investments->isEmpty())
      <div class="empty-state">
        <strong>No investment transactions yet.</strong>
        <p>Invest in a package to begin earning daily interest and see it listed here.</p>
      </div>
    @else
      <table class="history-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Package</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Daily Interest</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($investments as $investment)
            <tr>
              <td>{{ $investment->created_at->format('M d, Y') }}</td>
              <td>{{ $investment->package_name }}</td>
              <td>${{ number_format($investment->amount, 2) }}</td>
              <td>
                <span class="badge badge-{{ $investment->status }}">
                  {{ ucfirst($investment->status) }}
                </span>
              </td>
              <td>${{ number_format($investment->dailyInterestAmount(), 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </section>

  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">Withdrawal Transactions</h2>
        <p class="section-subtitle">A record of all withdrawal activity from your account.</p>
      </div>
    </div>

    @if ($withdrawals->isEmpty())
      <div class="empty-state">
        <strong>No withdrawals yet.</strong>
        <p>Request a withdrawal and the transaction will appear here once submitted.</p>
      </div>
    @else
      <table class="history-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($withdrawals as $withdrawal)
            <tr>
              <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
              <td>${{ number_format($withdrawal->amount, 2) }}</td>
              <td>{{ ucwords(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
              <td>
                <span class="badge badge-{{ $withdrawal->status }}">
                  {{ ucfirst($withdrawal->status) }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </section>
</main>

@endsection
