@extends('layouts.app')

@section('content')
<style>
  :root{
    --color-primary: #e31b23;
    --color-accent: #ffb300;
    --bg: #f4f6f8;
    --card: #ffffff;
    --muted: #64748b;
    --text: #041438;
    --shadow-1: 0 8px 24px rgba(3,7,18,0.06);
    --shadow-2: 0 16px 48px rgba(3,7,18,0.08);
    --radius: 14px;
  }

  body { background:var(--bg) !important; color:var(--text); -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; }
  .wallet-shell { max-width:430px; margin:8px auto 92px; padding:0 16px; }
  .topbar { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; }
  .brand { display:flex; align-items:center; gap:10px; }
  .brand-mark { width:36px; height:36px; border-radius:10px; background:var(--color-primary); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900; font-size:16px; box-shadow:var(--shadow-1); }
  .brand-title { font-weight:900; color:var(--text); font-size:18px; }

  .hero { border-radius:18px; padding:18px; color:#fff; background:var(--color-primary); box-shadow:var(--shadow-2); }
  .hero-top { display:flex; align-items:center; justify-content:space-between; }
  .hero-kicker { font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; opacity:.95; }
  .hero-balance { margin-top:12px; display:flex; align-items:baseline; justify-content:space-between; }
  .balance-value { font-size:34px; font-weight:900; letter-spacing:-0.02em; }
  .hero-cta { display:inline-flex; align-items:center; gap:8px; background:#fff; color:var(--color-primary); border-radius:999px; padding:8px 12px; font-weight:800; text-decoration:none; box-shadow:var(--shadow-1); transition:transform .18s ease, box-shadow .18s ease; }
  .hero-cta:hover { transform:translateY(-3px); box-shadow:0 20px 40px rgba(3,7,18,0.12); }

  .card { margin-top:14px; background:var(--card); border-radius:var(--radius); padding:14px; box-shadow:var(--shadow-1); border:1px solid rgba(3,7,18,0.04); transition:transform .18s ease, box-shadow .18s ease; }
  .card:hover { transform:translateY(-6px); box-shadow:0 28px 60px rgba(3,7,18,0.12); }

  .actions-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
  .action { display:flex; flex-direction:column; align-items:center; gap:8px; text-align:center; color:var(--text); font-weight:700; font-size:12px; text-decoration:none; }
  .action .icon { width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:var(--card); box-shadow:0 8px 18px rgba(3,7,18,0.06); border:1px solid rgba(3,7,18,0.04); transition:transform .18s ease, box-shadow .18s ease; }
  .action:hover .icon { transform:translateY(-6px); box-shadow:0 22px 48px rgba(3,7,18,0.12); }
  .action svg { width:28px; height:28px; color:var(--color-primary); fill:currentColor; }

  .discover { display:flex; gap:12px; overflow:auto; padding:8px 6px; }
  .discover-item { min-width:72px; display:flex; flex-direction:column; align-items:center; gap:6px; padding:10px; border-radius:12px; background:var(--card); box-shadow:0 8px 20px rgba(3,7,18,0.04); text-align:center; color:var(--text); font-weight:800; font-size:12px; }

  .promo { margin-top:14px; border-radius:12px; padding:12px; background:var(--card); display:flex; align-items:center; gap:12px; box-shadow:0 10px 30px rgba(3,7,18,0.06); }
  .promo .cta { background:var(--color-primary); color:#fff; padding:8px 12px; border-radius:10px; font-weight:800; text-decoration:none; }

  .bottom-nav { position:fixed; left:12px; right:12px; bottom:12px; display:flex; align-items:center; justify-content:space-around; gap:18px; max-width:640px; margin:0 auto; padding:10px 16px; background:#fff; border-radius:999px; box-shadow:0 24px 60px rgba(3,7,18,0.08); border:1px solid rgba(3,7,18,0.04); }
  .nav-item { display:flex; flex-direction:column; align-items:center; gap:6px; color:var(--muted); font-weight:800; font-size:11px; text-decoration:none; }
  .nav-item svg { width:20px; height:20px; color:var(--muted); }
  .nav-scan { position:relative; top:-24px; width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:var(--color-primary); box-shadow:0 24px 40px rgba(227,27,35,0.12); color:#fff; transition:transform .18s ease; }
  .nav-scan:hover { transform:translateY(-6px); }

  @media (min-width:760px) { .wallet-shell { max-width:760px; } .actions-grid { grid-template-columns:repeat(8,1fr); } }
</style>

@php
  $user = auth()->user();
  $investments = $user->investments()->latest()->get();
  $activeCapital = $investments->sum(fn($i) => (float) $i->amount);
  $dailyInterest = $investments->sum(fn($i) => $i->dailyInterestAmount());
  $earnedIncome = $investments->sum(fn($i) => $i->earnedInterest());
  $availableBalance = $activeCapital + $earnedIncome;
@endphp

<main class="wallet-shell">
  <header class="topbar">
    <div class="brand">
      <div class="brand-mark">L</div>
      <div>
        <div class="brand-title">Lotteria Wallet</div>
        <div style="font-size:11px;color:#8b91a3;font-weight:700;">Demo • For demonstration only</div>
      </div>
    </div>
    <div style="display:flex;gap:12px;align-items:center;">
      <a href="#" style="color:#e31b23;font-weight:900;text-decoration:none;">🔔</a>
      <a href="#" style="width:36px;height:36px;border-radius:50%;background:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:#c40000;">👤</a>
    </div>
  </header>

  <section class="hero">
    <div class="hero-top">
      <div class="hero-kicker">Available balance</div>
      <a class="hero-cta" href="{{ route('unavailable') }}">View Details</a>
    </div>
    <div class="hero-balance">
      <div>
        <div class="balance-value">${{ number_format($availableBalance, 2) }}</div>
        <div style="font-weight:800;opacity:.95;">&nbsp;</div>
      </div>
      <a class="hero-cta" href="{{ route('invest') }}">Buy Shares</a>
    </div>
  </section>

  <div class="card" style="margin-top:12px;">
    <div class="actions-grid" role="list">
      <a class="action" href="{{ route('send') }}">
        <div class="icon"><img src="{{ asset('Send%20(1).png') }}" alt="Send" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Send</div>
      </a>

      <a class="action" href="{{ route('withdraw') }}">
        <div class="icon"><img src="{{ asset('Withdraw.png') }}" alt="Withdraw" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Withdraw</div>
      </a>

      <a class="action" href="{{ route('referrals') }}">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg></div>
        <div>Referrals</div>
      </a>

      <a class="action" href="{{ route('franchising') }}">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.6" fill="none"/><path d="M7 7V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg></div>
        <div>Franchise</div>
      </a>

      <a class="action" href="{{ route('cards') }}">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none"><rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="1.6" fill="none"/><path d="M6 10h12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" fill="none"/></svg></div>
        <div>Cards</div>
      </a>

      <a class="action" href="{{ route('loan') }}">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3v4M8 11v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg></div>
        <div>Loan</div>
      </a>
    </div>
  </div>

  <div style="margin-top:12px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;"><strong style="font-size:14px;color:#071846;">Discover</strong><a href="#" style="color:#e31b23;font-weight:800;text-decoration:none;">See All →</a></div>
    <div class="discover">
      <div class="discover-item"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#e31b23" stroke-width="1.6" stroke-linecap="round"/></svg><div>Promos</div></div>
      <div class="discover-item"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M12 2v6" stroke="#e31b23" stroke-width="1.6" stroke-linecap="round"/></svg><div>Insurance</div></div>
      <div class="discover-item"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="10" r="3" stroke="#e31b23" stroke-width="1.6"/></svg><div>Nearby</div></div>
      <div class="discover-item"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#e31b23" stroke-width="1.6"/></svg><div>Food</div></div>
      <div class="discover-item"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M2 12h20" stroke="#e31b23" stroke-width="1.6"/></svg><div>Flights</div></div>
    </div>
  </div>

  <div class="promo">
    <div style="flex:1;">
      <div style="font-weight:900;color:#071846;">More rewards, more ways!</div>
      <div style="font-size:13px;color:#64748b;margin-top:6px;">Earn points and get exclusive deals just for you.</div>
    </div>
    <div><a class="cta" href="#">Explore Now</a></div>
  </div>

</main>

<nav class="bottom-nav" aria-hidden="false">
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none"><path d="M3 11l9-7 9 7v8a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/></svg>
    <div>Home</div>
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
    <div>History</div>
  </a>
  <a class="nav-item" href="{{ route('unavailable') }}" style="text-decoration:none;">
    <div class="nav-scan"> <svg viewBox="0 0 24 24" fill="none" width="26" height="26"><rect x="4" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="14" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="4" y="14" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="14" y="14" width="6" height="6" stroke="currentColor" stroke-width="1.6"/></svg></div>
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none"><path d="M2 12h20" stroke="currentColor" stroke-width="1.6"/></svg>
    <div>Rewards</div>
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3" stroke="currentColor" stroke-width="1.6"/><path d="M4 20c1.5-4 6-6 8-6s6.5 2 8 6" stroke="currentColor" stroke-width="1.6"/></svg>
    <div>Profile</div>
  </a>
</nav>

@endsection
