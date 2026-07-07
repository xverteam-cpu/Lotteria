@extends('layouts.app')

@section('content')
<style>
  body { background: #f4f6fb !important; color: #12233c; }
  .history-shell { max-width: 940px; margin: 0 auto; padding: 24px 16px 40px; }
  .history-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
  .history-header-left { display:flex; align-items:center; gap:12px; }
  .history-title { margin: 0; font-size: 28px; font-weight: 900; letter-spacing: -0.03em; }
  .history-copy { margin: 8px 0 0; color: #5b6b84; font-size: 14px; line-height: 1.6; }
  .history-back-btn { background:#fff; border-radius:12px; width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(22,45,86,0.06); border:1px solid rgba(48,73,130,0.06); cursor:pointer; }
  .history-back-btn svg { width:16px; height:16px; color:#d71920; }
  .accordion { display:flex; flex-direction:column; gap:18px; }
  .accordion-item { background: transparent; }
  .accordion-head { width:100%; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 20px; border-radius:14px; background: #fff; border:1px solid rgba(48,73,130,0.04); cursor:pointer; box-shadow:0 10px 30px rgba(22,45,86,0.04); }
  .accordion-head .section-title { font-size:18px; margin:0; }
  .accordion-head .section-subtitle { margin:0; font-size:13px; color:#6d7a95; }
  .accordion-toggle { font-size:20px; font-weight:800; color:#25304a; }
  .accordion-body { display:none; padding:0; }
  .accordion-body.open { display:block; }
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
    <div class="history-header-left">
      <a href="{{ route('dashboard') }}" class="history-back-btn" aria-label="Go to dashboard">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M15 6L9 12L15 18" stroke="#d71920" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <div>
        <h1 class="history-title">Account History</h1>
        <p class="history-copy">Review every investment and withdrawal transaction, plus the daily estimated interest earned on approved investments.</p>
      </div>
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

  <div class="accordion">
    <div class="accordion-item">
      <button type="button" class="accordion-head" data-target="investments">
        <div>
          <h2 class="section-title">Investment History</h2>
          <p class="section-subtitle">All account investments, their amounts, and approval status.</p>
        </div>
        <span class="accordion-toggle">+</span>
      </button>
      <div id="investments" class="accordion-body">
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
                  <td>
                    @if ($investment->status === 'approved')
                      ${{ number_format($investment->dailyInterestAmount(), 2) }}
                    @else
                      —
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>

    <div class="accordion-item">
      <button type="button" class="accordion-head" data-target="withdrawals">
        <div>
          <h2 class="section-title">Withdrawal History</h2>
          <p class="section-subtitle">A record of all withdrawal activity from your account.</p>
        </div>
        <span class="accordion-toggle">+</span>
      </button>
      <div id="withdrawals" class="accordion-body">
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
                <th>Description</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($withdrawals as $withdrawal)
                <tr>
                  <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                  <td>${{ number_format($withdrawal->amount, 2) }}</td>
                  <td>
                    @if ($withdrawal->bank_name === 'Welcome Bonus')
                      <strong>$5 Sign Up Bonus</strong><br>
                      <span style="color:#d71920;font-size:12px;">Welcome reward</span>
                    @else
                      {{ ucwords(str_replace('_', ' ', $withdrawal->payment_method)) }}
                    @endif
                  </td>
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
      </div>
    </div>

    <div class="accordion-item">
      <button type="button" class="accordion-head" data-target="daily-interest">
        <div>
          <h2 class="section-title">Daily Interest History</h2>
          <p class="section-subtitle">Daily interest amounts from approved investments.</p>
        </div>
        <span class="accordion-toggle">+</span>
      </button>
      <div id="daily-interest" class="accordion-body">
        @php
          $dailyList = $investments->where('status', 'approved');
        @endphp

        @if ($dailyList->isEmpty())
          <div class="empty-state">
            <strong>No daily interest records yet.</strong>
            <p>Approved investments will show daily interest here.</p>
          </div>
        @else
          <table class="history-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Package</th>
                <th>Daily Interest</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dailyList as $inv)
                <tr>
                  <td>{{ $inv->created_at->format('M d, Y') }}</td>
                  <td>{{ $inv->package_name }}</td>
                  <td>${{ number_format($inv->dailyInterestAmount(), 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>

    <div class="accordion-item">
      <button type="button" class="accordion-head" data-target="rewards">
        <div>
          <h2 class="section-title">Rewards History</h2>
          <p class="section-subtitle">Signup bonuses and other rewards applied to your account.</p>
        </div>
        <span class="accordion-toggle">+</span>
      </button>
      <div id="rewards" class="accordion-body">
        @php
          $rewards = $withdrawals->filter(fn($w) => ($w->bank_name ?? '') === 'Welcome Bonus' || ($w->account_number ?? '') === 'signup-bonus');
          $user = Auth::user();
          $claimed = ! empty($user->signup_bonus_claimed_at);
        @endphp

        @if (! $claimed)
          <div style="padding:16px 20px 0 20px;">
            <button id="claim-signup-bonus" class="history-back-btn" style="width:auto;padding:8px 12px;">Claim $5 Sign Up Bonus</button>
          </div>
        @endif

        @if ($rewards->isEmpty())
          <div class="empty-state" id="rewards-empty">
            <strong>No rewards yet.</strong>
            <p>Any signup bonus or rewards will appear here.</p>
          </div>
        @endif

        <table class="history-table" id="rewards-table" style="margin-top:12px; @if($rewards->isEmpty()) display:none; @endif">
          <thead>
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody id="rewards-table-body">
            @foreach ($rewards as $r)
              <tr>
                <td>{{ $r->created_at->format('M d, Y') }}</td>
                <td>${{ number_format($r->amount, 2) }}</td>
                <td>{{ $r->bank_name === 'Welcome Bonus' ? 'Signup Bonus' : ($r->account_holder ?? '') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <template id="reward-row-template">
          <tr>
            <td class="r-date"></td>
            <td class="r-amount"></td>
            <td class="r-details"></td>
          </tr>
        </template>
      </div>
    </div>
  </div>
</main>

<script>
  (function(){
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.accordion-head').forEach(function(btn){
          btn.addEventListener('click', function(){
            var targetId = btn.getAttribute('data-target');
            var body = document.getElementById(targetId);
            if (!body) return;
            // Close all others (only one open at a time)
            document.querySelectorAll('.accordion-body').forEach(function(b){
              if (b.id !== targetId) {
                b.classList.remove('open');
                var head = document.querySelector('.accordion-head[data-target="' + b.id + '"]');
                if (head) head.querySelector('.accordion-toggle').textContent = '+';
              }
            });

            var isOpen = body.classList.toggle('open');
            var toggle = btn.querySelector('.accordion-toggle');
            if (toggle) toggle.textContent = isOpen ? '−' : '+';
          });
        });
    });
  })();

  // Signup bonus claim handling
  (function(){
    document.addEventListener('DOMContentLoaded', function(){
      var claimBtn = document.getElementById('claim-signup-bonus');
      if (!claimBtn) return;
      claimBtn.addEventListener('click', function(){
        if (!confirm('Claim the $5 signup bonus?')) return;
        claimBtn.disabled = true;
        claimBtn.textContent = 'Claiming...';

        fetch("{{ route('rewards.claim-signup-bonus') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({})
        }).then(function(res){
          if (!res.ok) throw new Error('Request failed');
          return res.text();
        }).then(function(){
          // Insert a new reward row locally
          var tpl = document.getElementById('reward-row-template');
          var clone = tpl.content.cloneNode(true);
          var now = new Date();
          var dateStr = now.toLocaleString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
          clone.querySelector('.r-date').textContent = dateStr;
          clone.querySelector('.r-amount').textContent = '$5.00';
          clone.querySelector('.r-details').textContent = 'Signup Bonus';

          var tbody = document.getElementById('rewards-table-body');
          if (tbody) {
            tbody.insertBefore(clone, tbody.firstChild);
          }

          var table = document.getElementById('rewards-table');
          var empty = document.getElementById('rewards-empty');
          if (table) table.style.display = '';
          if (empty) empty.style.display = 'none';

          claimBtn.textContent = 'Claimed';
          claimBtn.disabled = true;
        }).catch(function(err){
          console.error(err);
          claimBtn.disabled = false;
          claimBtn.textContent = 'Claim $5 Sign Up Bonus';
          alert('Unable to claim signup bonus right now.');
        });
      });
    });
  })();
</script>

@endsection
