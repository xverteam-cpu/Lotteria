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

  .fab-scrim { position:fixed; inset:0; z-index:120; background:rgba(0,0,0,0.52); opacity:0; visibility:hidden; transition:opacity .28s ease; }
  .fab-scrim.is-open { opacity:1; visibility:visible; }
  .fab-panel { position:fixed; left:0; right:0; bottom:0; z-index:130; transform:translateY(110%); transition:transform .34s cubic-bezier(.22,1,.36,1); }
  .fab-panel.is-open { transform:translateY(0); }
  .fab-sheet { border-radius:28px 28px 0 0; padding:18px 18px 28px; background:#fff; box-shadow:0 -18px 60px rgba(3,7,18,0.14); }
  .fab-sheet-handle { width:68px; height:6px; margin:0 auto 14px; border-radius:999px; background:#e9e9e9; }
  .fab-sheet-title { font-size:16px; font-weight:900; color:#121212; text-align:center; margin-bottom:18px; }
  .fab-actions { display:grid; gap:12px; }
  .fab-action { display:flex; align-items:center; gap:14px; padding:14px 16px; border-radius:18px; background:#f9fafb; color:#121212; text-decoration:none; font-weight:800; transition:transform .2s ease, background .2s ease; transform:translateY(24px); opacity:0; }
  .fab-panel.is-open .fab-action { transform:translateY(0); opacity:1; }
  .fab-action:hover { background:#fff; transform:translateY(-2px); }
  .fab-action-icon { width:38px; height:38px; border-radius:16px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#e31b23,#ff6b4a); color:#fff; font-size:18px; }
  .fab-action:nth-child(1) { transition-delay:.05s; }
  .fab-action:nth-child(2) { transition-delay:.10s; }
  .fab-action:nth-child(3) { transition-delay:.15s; }
  .fab-action:nth-child(4) { transition-delay:.20s; }
  .fab-action:nth-child(5) { transition-delay:.25s; }
  .fab-action:nth-child(6) { transition-delay:.30s; }
  .fab-close { margin-top:16px; width:100%; border:none; border-radius:16px; padding:14px 16px; background:#f5f5f5; color:#4b5563; font-weight:900; cursor:pointer; }

  @media (min-width:760px) { .wallet-shell { max-width:760px; } .actions-grid { grid-template-columns:repeat(8,1fr); } }
</style>

@php
  $user = auth()->user();
  $investments = $user->investments()->latest()->get();
  $activeCapital = $investments->sum(fn($i) => (float) $i->amount);
  $dailyInterest = $investments->sum(fn($i) => $i->dailyInterestAmount());
  $earnedIncome = $investments->sum(fn($i) => $i->earnedInterest());
  $availableBalance = (float) $user->balance + $earnedIncome;
@endphp

<main class="wallet-shell">
  <section class="hero">
    <div class="hero-top">
      <div class="hero-kicker">Available balance</div>
      <a class="hero-cta" href="{{ route('unavailable') }}">View Details</a>
    </div>
    <div class="hero-balance">
      <div>
        <div class="balance-value">${{ number_format($availableBalance, 2) }}</div>
        <div style="font-weight:800;opacity:.95;">Withdrawable funds and accumulated interest</div>
      </div>
      <a class="hero-cta" href="{{ route('invest') }}">Buy Shares</a>
    </div>
  </section>

  <div class="card" style="margin-top:12px; padding:18px; display:flex; align-items:center; justify-content:space-between; gap:16px;">
    <div>
      <div class="balance-label" style="color:#64748b;">Total investment</div>
      <div class="balance-value" style="margin-top:8px;">${{ number_format($activeCapital, 2) }}</div>
    </div>
    <div style="text-align:right;">
      <div style="font-size:12px; color:#ffffff; background:rgba(255,255,255,0.18); display:inline-flex; align-items:center; justify-content:center; border-radius:999px; padding:8px 12px;">Active capital</div>
      <div style="font-size:13px; color:#ffffff; opacity:.88; margin-top:10px;">Daily interest adds to available balance</div>
    </div>
  </div>

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
        <div class="icon"><img src="{{ asset('referrals.png') }}" alt="Referrals" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Referrals</div>
      </a>

      <a class="action" href="{{ route('franchising') }}">
        <div class="icon"><img src="{{ asset('franchisebuttong.png') }}" alt="Franchise" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Franchise</div>
      </a>

      <a class="action" href="{{ route('cards') }}">
        <div class="icon"><img src="{{ asset('cards.png') }}" alt="Cards" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Cards</div>
      </a>

      <a class="action" href="{{ route('loan') }}">
        <div class="icon"><img src="{{ asset('loan.png') }}" alt="Loan" style="width:28px;height:28px;object-fit:contain;border-radius:8px;background:#fff;padding:4px;"></div>
        <div>Loan</div>
      </a>
    </div>
  </div>

  <div style="margin-top:12px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;"><strong style="font-size:14px;color:#071846;">Discover</strong><a href="#" style="color:#e31b23;font-weight:800;text-decoration:none;">See All →</a></div>
    <div class="discover">
      <a class="discover-item" href="{{ route('unavailable') }}" style="text-decoration:none;color:inherit;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#e31b23" stroke-width="1.6" stroke-linecap="round"/></svg><div>Promos</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}" style="text-decoration:none;color:inherit;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M12 2v6" stroke="#e31b23" stroke-width="1.6" stroke-linecap="round"/></svg><div>Insurance</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}" style="text-decoration:none;color:inherit;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="10" r="3" stroke="#e31b23" stroke-width="1.6"/></svg><div>Nearby</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}" style="text-decoration:none;color:inherit;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#e31b23" stroke-width="1.6"/></svg><div>Food</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}" style="text-decoration:none;color:inherit;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M2 12h20" stroke="#e31b23" stroke-width="1.6"/></svg><div>Flights</div></a>
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

<div class="fab-scrim" id="fabScrim" aria-hidden="true"></div>
<div class="fab-panel" id="fabPanel" aria-hidden="true">
  <div class="fab-sheet" role="dialog" aria-modal="true" aria-label="Quick actions menu">
    <div class="fab-sheet-handle"></div>
    <div class="fab-sheet-title">Quick actions</div>
    <div class="fab-actions">
      <a class="fab-action" href="{{ route('invest') }}">
        <span class="fab-action-icon">💰</span>
        <span>Buy shares</span>
      </a>
      <a class="fab-action" href="{{ route('send') }}">
        <span class="fab-action-icon">📤</span>
        <span>Send</span>
      </a>
      <a class="fab-action" href="{{ route('withdraw') }}">
        <span class="fab-action-icon">🏧</span>
        <span>Withdraw</span>
      </a>
      <a class="fab-action" href="{{ route('referrals') }}">
        <span class="fab-action-icon">🤝</span>
        <span>Referrals</span>
      </a>
      <a class="fab-action" href="{{ route('franchising') }}">
        <span class="fab-action-icon">🏬</span>
        <span>Franchise</span>
      </a>
      <a class="fab-action" href="{{ route('cards') }}">
        <span class="fab-action-icon">💳</span>
        <span>Cards</span>
      </a>
      <a class="fab-action" href="{{ route('loan') }}">
        <span class="fab-action-icon">🪙</span>
        <span>Loans</span>
      </a>
    </div>
    <button class="fab-close" type="button" id="fabClose">Close menu</button>
  </div>
</div>

<nav class="bottom-nav" aria-hidden="false">
  <a class="nav-item" href="{{ route('dashboard') }}" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none"><path d="M3 11l9-7 9 7v8a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/></svg>
    <div>Home</div>
  </a>
  <a class="nav-item" href="{{ route('withdraw') }}" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
    <div>History</div>
  </a>
  <a class="nav-item" href="#" id="fabToggle" style="text-decoration:none;">
    <div class="nav-scan"> <svg viewBox="0 0 24 24" fill="none" width="26" height="26"><rect x="4" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="14" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="4" y="14" width="6" height="6" stroke="currentColor" stroke-width="1.6"/><rect x="14" y="14" width="6" height="6" stroke="currentColor" stroke-width="1.6"/></svg></div>
    <div>Menu</div>
  </a>
  <a class="nav-item" href="{{ route('unavailable') }}" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none"><path d="M2 12h20" stroke="currentColor" stroke-width="1.6"/></svg>
    <div>Rewards</div>
  </a>
  <a class="nav-item" href="{{ route('profile') }}" style="text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3" stroke="currentColor" stroke-width="1.6"/><path d="M4 20c1.5-4 6-6 8-6s6.5 2 8 6" stroke="currentColor" stroke-width="1.6"/></svg>
    <div>Profile</div>
  </a>
</nav>

<script>
  (function () {
    var fabToggle = document.getElementById('fabToggle');
    var fabScrim = document.getElementById('fabScrim');
    var fabPanel = document.getElementById('fabPanel');
    var fabClose = document.getElementById('fabClose');

    function openFabMenu(event) {
      if (event) event.preventDefault();
      if (!fabScrim || !fabPanel) return;
      fabScrim.classList.add('is-open');
      fabPanel.classList.add('is-open');
      fabScrim.setAttribute('aria-hidden', 'false');
      fabPanel.setAttribute('aria-hidden', 'false');
    }

    function closeFabMenu() {
      if (!fabScrim || !fabPanel) return;
      fabScrim.classList.remove('is-open');
      fabPanel.classList.remove('is-open');
      fabScrim.setAttribute('aria-hidden', 'true');
      fabPanel.setAttribute('aria-hidden', 'true');
    }

    if (fabToggle) {
      fabToggle.addEventListener('click', openFabMenu);
    }

    if (fabScrim) {
      fabScrim.addEventListener('click', closeFabMenu);
    }

    if (fabClose) {
      fabClose.addEventListener('click', closeFabMenu);
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeFabMenu();
      }
    });
  })();
</script>

@endsection
