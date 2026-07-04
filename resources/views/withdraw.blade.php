@extends('layouts.app')

@section('content')
<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Inter, Arial, Helvetica, sans-serif;
  }

  body { background: #f3f5f8; color: #071a44; }

  .phone { max-width: 430px; min-height: 100vh; margin: 0 auto; background: #f3f5f8; padding: 24px 16px 110px; position: relative; }

  .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; }
  .brand { display:flex; align-items:center; gap:12px; }
  .logo { width:38px; height:38px; border-radius:12px; background:#ed1c24; color:#fff; font-weight:900; display:flex; align-items:center; justify-content:center; font-size:20px; }
  .brand h1 { font-size:19px; font-weight:900; line-height:1; }
  .brand p { font-size:12px; color:#8b96a8; font-weight:700; margin-top:4px; }

  .icons { display:flex; align-items:center; gap:16px; font-size:18px; }
  .profile { width:36px; height:36px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; color:#3f247a; box-shadow:0 10px 25px rgba(0,0,0,0.08); }

  .back-row { display:flex; align-items:center; gap:12px; margin-bottom:18px; }
  .back-btn { width:42px; height:42px; border-radius:15px; background:#fff; border:none; color:#ed1c24; font-size:24px; font-weight:800; box-shadow:0 10px 25px rgba(0,0,0,0.08); display:inline-flex; align-items:center; justify-content:center; text-decoration:none; }

  .page-title h2 { font-size:25px; font-weight:900; }
  .page-title p { color:#71809a; font-size:13px; font-weight:600; margin-top:4px; }

  .balance-card { background:#fff; border-radius:22px; padding:18px; color:#041438; margin-bottom:16px; box-shadow:0 12px 32px rgba(0,0,0,0.06); }
  .balance-label { font-size:12px; font-weight:900; color:#64748b; }
  .balance-amount { font-size:28px; font-weight:900; margin-top:8px; }

  .form-card { background:#fff; border-radius:22px; padding:18px; box-shadow:0 12px 32px rgba(0,0,0,0.06); }
  .label { display:block; font-size:13px; font-weight:900; margin-bottom:8px; color:#071a44; }
  .input-box { width:100%; border:1px solid #edf0f4; background:#f8fafc; border-radius:16px; padding:15px 16px; font-size:15px; font-weight:700; color:#071a44; margin-bottom:16px; outline:none; }
  .input-row { position:relative; }
  .currency { position:absolute; top:15px; left:16px; font-size:16px; font-weight:900; color:#ed1c24; }
  .amount-input { padding-left:42px; font-size:22px; font-weight:900; }

  .quick-row { display:flex; gap:10px; margin-bottom:18px; }
  .quick-row button { flex:1; border:none; border-radius:14px; padding:12px 0; background:#fff5f5; color:#ed1c24; font-weight:900; font-size:13px; }

  .note { background:#f8fafc; border-radius:16px; padding:14px; font-size:12px; line-height:1.5; color:#6b7890; margin-bottom:18px; }

  .send-btn { width:100%; border:none; border-radius:18px; background:#ed1c24; color:#fff; font-size:17px; font-weight:900; padding:17px; box-shadow:0 16px 28px rgba(237,28,36,0.28); }

  .recent { margin-top:20px; }
  .section-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
  .section-head h3 { font-size:16px; font-weight:900; }
  .section-head a { font-size:14px; color:#ed1c24; text-decoration:none; font-weight:900; }

  .history-list { display:flex; flex-direction:column; gap:10px; }
  .history-item { background:#fff; border-radius:14px; padding:12px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 8px 22px rgba(0,0,0,0.04); }
  .history-left { font-weight:800; }
  .history-right { color:#6b7890; font-weight:900; }

  @media (max-width:380px) { .brand h1 { font-size:17px; } .balance-amount { font-size:24px; } .quick-row button { min-width: calc(50% - 5px); } }
</style>

<main class="phone">

  <div class="back-row">
    <a href="{{ route('dashboard') }}" class="back-btn">‹</a>
    <div class="page-title">
      <h2>Withdraw Money</h2>
      <p>Request funds to your bank or linked card</p>
    </div>
  </div>

  <section class="balance-card">
    <div class="balance-label">AVAILABLE BALANCE</div>
    <div class="balance-amount">${{ number_format($availableBalance ?? 0, 2) }}</div>
  </section>

  <section class="form-card">
    @if (session('status'))
      <div style="margin-bottom:12px; padding:12px; border-radius:12px; background:#e8f8ee; color:#137547; font-weight:700;">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div style="margin-bottom:12px; padding:12px; border-radius:12px; background:#fff1f2; color:#b42318; font-weight:700;">
        <ul style="margin:0; padding-left:18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('withdrawals.store') }}">
      @csrf

      <label class="label">Amount to Withdraw</label>
      <div class="input-row">
        <span class="currency">$</span>
        <input class="input-box amount-input" type="number" name="amount" min="20" max="500" step="0.01" placeholder="0.00" required />
      </div>

      <div class="quick-row">
        <button type="button" onclick="document.querySelector('input[name=amount]').value='10'">$10</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='25'">$25</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='50'">$50</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='100'">$100</button>
      </div>

      <label class="label">Bank Name</label>
      <input class="input-box" type="text" name="bank_name" value="{{ old('bank_name', auth()->user()->bank_name) }}" placeholder="e.g. BDO, BPI, Metrobank" required />

      <label class="label">Bank Account Number</label>
      <input class="input-box" type="text" name="account_number" value="{{ old('account_number', auth()->user()->bank_account_number) }}" placeholder="Enter account number" required />

      <label class="label">Account Holder Name</label>
      <input class="input-box" type="text" name="account_holder" value="{{ old('account_holder', auth()->user()->bank_account_holder) }}" placeholder="Enter account holder name" required />

      <div class="note">Minimum withdrawal is $20 and maximum withdrawal is $500. Your available balance will be reduced immediately after the request is submitted.</div>

      <button class="send-btn" type="submit">Request Withdrawal</button>
    </form>
  </section>

  <section class="recent">
    <div class="section-head">
      <h3>Recent Withdrawals</h3>
      <a href="#">See All →</a>
    </div>

    <div class="history-list">
      @forelse ($recentWithdrawals as $withdrawal)
        <div class="history-item">
          <div class="history-left">
            ${{ number_format($withdrawal->amount, 2) }} • {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}
          </div>
          <div class="history-right">{{ ucfirst($withdrawal->status) }}</div>
        </div>
      @empty
        <div class="history-item">
          <div class="history-left">No recent withdrawals yet.</div>
          <div class="history-right">—</div>
        </div>
      @endforelse
    </div>
  </section>

</main>

@endsection
