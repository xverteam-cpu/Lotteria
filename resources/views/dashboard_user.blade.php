@extends('layouts.app')

@section('content')
<style>
  :root {
    --color-primary: #c8102e;
    --color-primary-soft: #e21d2a;
    --color-navy: #0f172a;
    --color-title: #1e293b;
    --color-body: #475569;
    --color-muted: #94a3b8;
    --bg: #f8fafc;
    --card: #ffffff;
    --border: #eef2f7;
    --shadow-soft: 0 1px 2px rgba(15,23,42,.04);
    --shadow-card: 0 10px 35px rgba(15,23,42,.05);
    --radius: 22px;
    --radius-sm: 18px;
    --radius-lg: 30px;
  }

  body {
    background: var(--bg) !important;
    color: var(--color-body);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  .wallet-shell {
    max-width: 430px;
    margin: 8px auto 92px;
    padding: 0 16px;
  }

  .topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 20px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .brand-mark {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 900;
    font-size: 16px;
    box-shadow: 0 6px 18px rgba(15,23,42,.06);
  }

  .brand-title {
    font-weight: 700;
    color: var(--color-title);
    font-size: 18px;
  }

  .hero {
    position: relative;
    border-radius: var(--radius);
    padding: 26px 22px;
    color: #fff;
    background: linear-gradient(180deg, var(--color-primary-soft), var(--color-primary));
    box-shadow: var(--shadow-soft), var(--shadow-card);
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    top: -18px;
    right: -24px;
    width: 170px;
    height: 170px;
    background: radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 58%);
    pointer-events: none;
  }

  .hero::after {
    content: '';
    position: absolute;
    top: 12px;
    right: 20px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255,235,59,.95), rgba(255,235,59,.35) 35%, transparent 65%);
    filter: blur(4px);
    pointer-events: none;
  }

  .hero .hero-cta.mail,
  .hero .hero-cta.buy {
    position: relative;
    padding: 14px 16px;
    justify-content: center;
    display: inline-flex;
    align-items: center;
    z-index: 1;
  }

  .hero .hero-cta.mail::before,
  .hero .hero-cta.buy::before {
    content: '';
    position: absolute;
    inset: auto auto auto auto;
    top: 50%;
    left: 50%;
    width: 62px;
    height: 62px;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255,235,59,.95), rgba(255,235,59,.35) 35%, rgba(255,235,59,0) 65%);
    filter: blur(7px);
    z-index: -1;
  }

  .hero .hero-cta.mail {
    width: 52px;
    min-width: 52px;
  }

  .notification-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    min-width: 20px;
    height: 20px;
    line-height: 20px;
    border-radius: 999px;
    background: #d71920;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,.16);
  }

  .notification-panel {
    position: fixed;
    left: 12px;
    right: 12px;
    top: 84px;
    max-width: 640px;
    margin: 0 auto;
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 32px 80px rgba(0,0,0,.12);
    overflow: hidden;
    z-index: 40;
    transform: translateY(-20px);
    opacity: 0;
    visibility: hidden;
    transition: opacity .22s ease, transform .22s ease, visibility .22s ease;
  }

  .notification-panel.is-open {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
  }

  .notification-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 22px;
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
  }

  .notification-panel-header strong {
    font-size: 16px;
    font-weight: 900;
    color: #111827;
  }

  .notification-panel-header button {
    background: transparent;
    border: none;
    color: #6b7280;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
  }

  .notification-list {
    display: grid;
    gap: 0;
  }

  .notification-item {
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
  }

  .notification-item:last-child {
    border-bottom: none;
  }

  .notification-title {
    font-weight: 800;
    color: #111827;
    margin-bottom: 6px;
  }

  .notification-text {
    color: #4b5563;
    font-size: 14px;
    margin-bottom: 8px;
  }

  .notification-time {
    color: #9ca3af;
    font-size: 12px;
  }

  .notification-empty {
    padding: 20px 22px;
    color: #6b7280;
    font-size: 14px;
    text-align: center;
  }

  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
  }

  .hero-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .hero-kicker {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .85;
  }

  .hero-balance {
    margin-top: 18px;
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 16px;
  }

  .hero .balance-value {
    font-size: 44px;
    font-weight: 700;
    letter-spacing: -1px;
    line-height: 1.05;
  }

  .hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    color: var(--color-primary);
    border-radius: 999px;
    padding: 14px 20px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 8px 18px rgba(215,25,32,.15);
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .hero-cta:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 22px rgba(215,25,32,.18);
  }

  .card {
    margin-top: 24px;
    background: var(--card);
    border-radius: var(--radius);
    padding: 18px;
    box-shadow: var(--shadow-soft), var(--shadow-card);
    border: 1px solid var(--border);
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 12px 40px rgba(15,23,42,.05);
  }

  .balance-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .balance-meta {
    text-align: right;
  }

  .balance-label {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .85;
    color: var(--color-muted);
  }

  .balance-card .balance-value {
    margin-top: 8px;
    font-size: 28px;
    font-weight: 700;
    color: var(--color-title);
  }

  .small-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,.18);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .06em;
  }

  .status-copy {
    margin-top: 10px;
    color: rgba(255,255,255,.88);
    font-size: 13px;
  }

  .actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  .action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-align: center;
    color: var(--color-title);
    font-weight: 500;
    font-size: 15px;
    text-decoration: none;
  }

  .action .icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--card);
    box-shadow: 0 6px 18px rgba(15,23,42,.06);
    border: 1px solid var(--border);
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .action:hover .icon {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(15,23,42,.08);
  }

  .action img,
  .action svg {
    width: 22px;
    height: 22px;
  }

  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--color-title);
  }

  .section-link {
    color: var(--color-primary);
    font-weight: 700;
    text-decoration: none;
  }

  .discover {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    padding-bottom: 6px;
  }

  .discover-wrapper {
    margin-top: 24px;
  }

  .discover-item {
    min-width: 98px;
    height: 82px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 10px;
    padding: 20px;
    border-radius: var(--radius-sm);
    background: var(--card);
    box-shadow: 0 2px 6px rgba(15,23,42,.04), 0 10px 35px rgba(15,23,42,.05);
    text-align: left;
    color: var(--color-title);
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
  }

  .discover-item svg {
    width: 22px;
    height: 22px;
  }

  .promo {
    margin-top: 24px;
    border-radius: var(--radius);
    padding: 20px;
    background: linear-gradient(180deg, #ffffff, #fcfcfd);
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 6px rgba(15,23,42,.04), 0 10px 35px rgba(15,23,42,.05);
  }

  .promo-copy {
    flex: 1;
  }

  .promo-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--color-title);
  }

  .promo-subtitle {
    margin-top: 6px;
    font-size: 13px;
    color: #64748b;
  }

  .promo .cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    padding: 0 24px;
    border-radius: 999px;
    background: var(--color-primary);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 8px 18px rgba(215,25,32,.15);
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .promo .cta:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 22px rgba(215,25,32,.18);
  }

  .bottom-nav {
    position: fixed;
    left: 12px;
    right: 12px;
    bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 18px;
    max-width: 640px;
    margin: 0 auto;
    padding: 0 22px;
    height: 86px;
    background: rgba(255,255,255,.95);
    border-radius: 30px;
    border: 1px solid rgba(239,239,247,.90);
    backdrop-filter: blur(18px);
    box-shadow: 0 8px 24px rgba(15,23,42,.06);
  }

  .nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: var(--color-muted);
    font-weight: 500;
    font-size: 13px;
    text-decoration: none;
    transition: transform .2s ease, color .2s ease;
  }

  .nav-item:hover {
    transform: translateY(-2px);
    color: var(--color-title);
  }

  .nav-item img {
    width: 22px;
    height: 22px;
  }

  .bottom-nav a {
    text-decoration: none;
  }

  .discover-item,
  .section-link {
    text-decoration: none;
  }

  .nav-scan {
    position: relative;
    top: -24px;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    box-shadow: none;
    transition: transform .18s ease;
  }

  .nav-scan:hover {
    transform: translateY(-6px);
  }

  .nav-scan img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .fab-scrim {
    position: fixed;
    inset: 0;
    z-index: 120;
    background: rgba(0,0,0,0.52);
    opacity: 0;
    visibility: hidden;
    transition: opacity .28s ease;
  }

  .fab-scrim.is-open {
    opacity: 1;
    visibility: visible;
  }

  .fab-panel {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 130;
    transform: translateY(110%);
    transition: transform .34s cubic-bezier(.22,1,.36,1);
  }

  .fab-panel.is-open {
    transform: translateY(0);
  }

  .fab-sheet {
    border-radius: 28px 28px 0 0;
    padding: 18px 18px 28px;
    background: #fff;
    box-shadow: 0 -18px 60px rgba(3,7,18,0.14);
  }

  .fab-sheet-handle {
    width: 68px;
    height: 6px;
    margin: 0 auto 14px;
    border-radius: 999px;
    background: #e9e9e9;
  }

  .fab-sheet-title {
    font-size: 16px;
    font-weight: 900;
    color: #121212;
    text-align: center;
    margin-bottom: 18px;
  }

  .fab-actions {
    display: grid;
    gap: 12px;
  }

  .fab-action {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 18px;
    background: #f9fafb;
    color: #121212;
    text-decoration: none;
    font-weight: 800;
    transition: transform .2s ease, background .2s ease;
    transform: translateY(24px);
    opacity: 0;
  }

  .fab-panel.is-open .fab-action {
    transform: translateY(0);
    opacity: 1;
  }

  .fab-action:hover {
    background: #fff;
    transform: translateY(-2px);
  }

  .fab-action-icon {
    width: 38px;
    height: 38px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e31b23, #ff6b4a);
    color: #fff;
    font-size: 18px;
  }

  .fab-action:nth-child(1) { transition-delay:.05s; }
  .fab-action:nth-child(2) { transition-delay:.10s; }
  .fab-action:nth-child(3) { transition-delay:.15s; }
  .fab-action:nth-child(4) { transition-delay:.20s; }
  .fab-action:nth-child(5) { transition-delay:.25s; }
  .fab-action:nth-child(6) { transition-delay:.30s; }

  .fab-close {
    margin-top: 16px;
    width: 100%;
    border: none;
    border-radius: 16px;
    padding: 14px 16px;
    background: #f5f5f5;
    color: #4b5563;
    font-weight: 900;
    cursor: pointer;
  }

  .raffle-overlay {
    position: fixed;
    inset: 0;
    z-index: 200;
    background: rgba(0,0,0,.72);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .raffle-card {
    position: relative;
    width: min(100%, 520px);
    border-radius: 24px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 28px 80px rgba(0,0,0,.35);
  }

  .raffle-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,.9);
    color: #111;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    z-index: 2;
  }

  .raffle-image {
    display: block;
    width: 100%;
    height: auto;
    object-fit: cover;
  }

  @media (min-width:760px) {
    .wallet-shell {
      max-width: 760px;
    }
    .actions-grid {
      grid-template-columns: repeat(8, 1fr);
    }
  }
</style>

@php
  $user = $user ?? auth()->user();
  $investments = $user->investments()->latest()->get();
  $activeCapital = $investments->sum(fn($i) => (float) $i->amount);
  $dailyInterest = $investments->sum(fn($i) => $i->dailyInterestAmount());
  $earnedIncome = $investments->sum(fn($i) => $i->earnedInterest());
  $availableBalance = (float) $user->balance + $earnedIncome;
@endphp

<main class="wallet-shell">
  @if (! empty($showRafflePopup))
    <div class="raffle-overlay" id="raffleOverlay" aria-modal="true" role="dialog">
      <div class="raffle-card">
        <button class="raffle-close" type="button" id="raffleClose" aria-label="Close raffle popup">×</button>
        <img src="{{ asset('raffle.png') }}" alt="Raffle promotion" class="raffle-image">
      </div>
    </div>
  @endif
  <section class="hero">
    <div class="hero-top">
      <div class="hero-kicker">Available balance</div>
      <a class="hero-cta mail" href="#" id="notificationToggle" aria-label="View notifications" aria-expanded="false">
        <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M1 2.5C1 1.673 1.673 1 2.5 1h15c.827 0 1.5.673 1.5 1.5v11c0 .827-.673 1.5-1.5 1.5h-15A1.5 1.5 0 0 1 1 13.5v-11Z" stroke="currentColor" stroke-width="1.5"/>
          <path d="M1.5 3.5 10 8.75l8.5-5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span class="notification-badge" id="notificationBadge">{{ $notifications->count() }}</span>
        <span class="sr-only">View notifications</span>
      </a>
    </div>
    <div class="hero-balance">
      <div>
        <div class="balance-value">${{ number_format($availableBalance, 2) }}</div>
      </div>
      <a class="hero-cta buy" href="{{ route('invest') }}">Buy Shares</a>
    </div>
  </section>

  <div class="notification-panel" id="notificationPanel" aria-hidden="true">
    <div class="notification-panel-header">
      <strong>Recent activity</strong>
      <button type="button" id="notificationClose">Close</button>
    </div>
    <div class="notification-list" id="notificationList">
      @forelse ($notifications as $notification)
        <div class="notification-item" data-notification-id="{{ $notification['id'] }}">
          <div class="notification-title">{{ $notification['title'] }}</div>
          <div class="notification-text">{{ $notification['description'] }}</div>
          <div class="notification-time">{{ $notification['time']->diffForHumans() }}</div>
        </div>
      @empty
        <div class="notification-empty">No notifications yet.</div>
      @endforelse
    </div>
  </div>

  <div class="card balance-card">
    <div>
      <div class="balance-label">Total investment</div>
      <div class="balance-value">${{ number_format($activeCapital, 2) }}</div>
    </div>
    <div class="balance-meta">
      <div class="small-pill">Active capital</div>
      <div class="status-copy">Daily interest adds to available balance</div>
    </div>
  </div>

  <div class="card">
    <div class="actions-grid" role="list">
      <a class="action" href="{{ route('send') }}">
        <div class="icon"><img src="{{ asset('Send%20(1).png') }}" alt="Send"></div>
        <div>Send</div>
      </a>

      <a class="action" href="{{ route('withdraw') }}">
        <div class="icon"><img src="{{ asset('Withdraw.png') }}" alt="Withdraw"></div>
        <div>Withdraw</div>
      </a>

      <a class="action" href="{{ route('referrals') }}">
        <div class="icon"><img src="{{ asset('referrals.png') }}" alt="Referrals"></div>
        <div>Referrals</div>
      </a>

      <a class="action" href="{{ route('franchising') }}">
        <div class="icon"><img src="{{ asset('franchisebuttong.png') }}" alt="Franchise"></div>
        <div>Franchise</div>
      </a>

      <a class="action" href="{{ route('cards') }}">
        <div class="icon"><img src="{{ asset('cards.png') }}" alt="Cards"></div>
        <div>Cards</div>
      </a>

      <a class="action" href="{{ route('loan') }}">
        <div class="icon"><img src="{{ asset('loan.png') }}" alt="Loan"></div>
        <div>Loan</div>
      </a>
    </div>
  </div>

  <div class="discover-wrapper">
    <div class="section-header">
      <strong class="section-title">Discover</strong>
      <a class="section-link" href="#">See All →</a>
    </div>
    <div class="discover">
      <a class="discover-item" href="{{ route('unavailable') }}"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#c8102e" stroke-width="2" stroke-linecap="round"/></svg><div>Promos</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M12 2v6" stroke="#c8102e" stroke-width="2" stroke-linecap="round"/></svg><div>Insurance</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="10" r="3" stroke="#c8102e" stroke-width="2"/></svg><div>Nearby</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M3 12h18" stroke="#c8102e" stroke-width="2"/></svg><div>Food</div></a>
      <a class="discover-item" href="{{ route('unavailable') }}"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M2 12h20" stroke="#c8102e" stroke-width="2"/></svg><div>Flights</div></a>
    </div>
  </div>

  <div class="promo">
    <div class="promo-copy">
      <div class="promo-title">More rewards, more ways!</div>
      <div class="promo-subtitle">Earn points and get exclusive deals just for you.</div>
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
  <a class="nav-item active" href="{{ route('dashboard') }}">
    <img src="{{ asset('home.png') }}" alt="Home">
    <div>Home</div>
  </a>
  <a class="nav-item" href="{{ route('history') }}">
    <img src="{{ asset('history.png') }}" alt="History">
    <div>History</div>
  </a>
  <a class="nav-item" href="#" id="fabToggle">
    <div class="nav-scan">
      <img src="{{ asset('menu.png') }}" alt="Menu">
    </div>
  </a>
  <a class="nav-item" href="{{ route('unavailable') }}">
    <img src="{{ asset('reward.png') }}" alt="Rewards">
    <div>Rewards</div>
  </a>
  <a class="nav-item" href="{{ route('profile') }}">
    <img src="{{ asset('profile.png') }}" alt="Profile">
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

    function closeRafflePopup() {
      var overlay = document.getElementById('raffleOverlay');
      if (!overlay) return;
      overlay.style.display = 'none';
    }

    var raffleClose = document.getElementById('raffleClose');
    if (raffleClose) {
      raffleClose.addEventListener('click', closeRafflePopup);
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeFabMenu();
        closeRafflePopup();
      }
    });
  })();

    (function () {
      var notificationToggle = document.getElementById('notificationToggle');
      var notificationPanel = document.getElementById('notificationPanel');
      var notificationClose = document.getElementById('notificationClose');
      var notificationBadge = document.getElementById('notificationBadge');
      var notificationItems = Array.from(document.querySelectorAll('.notification-item'));
      var storageKey = 'dashboard-notifications-read-{{ $user->id }}';

      function getReadNotifications() {
        try {
          return new Set(JSON.parse(localStorage.getItem(storageKey) || '[]'));
        } catch (e) {
          return new Set();
        }
      }

      function updateBadge() {
        var read = getReadNotifications();
        var unreadCount = notificationItems.filter(function (item) {
          return !read.has(item.dataset.notificationId);
        }).length;

        if (!notificationBadge) return;
        notificationBadge.textContent = unreadCount;
        notificationBadge.style.display = unreadCount > 0 ? 'inline-flex' : 'none';
      }

      function openNotificationPanel(event) {
        if (event) event.preventDefault();
        if (!notificationPanel) return;
        notificationPanel.classList.add('is-open');
        notificationPanel.setAttribute('aria-hidden', 'false');
        notificationToggle.setAttribute('aria-expanded', 'true');

        var read = getReadNotifications();
        notificationItems.forEach(function (item) {
          read.add(item.dataset.notificationId);
        });
        localStorage.setItem(storageKey, JSON.stringify(Array.from(read)));
        updateBadge();
      }

      function closeNotificationPanel() {
        if (!notificationPanel) return;
        notificationPanel.classList.remove('is-open');
        notificationPanel.setAttribute('aria-hidden', 'true');
        notificationToggle.setAttribute('aria-expanded', 'false');
      }

      if (notificationToggle) {
        notificationToggle.addEventListener('click', openNotificationPanel);
      }
      if (notificationClose) {
        notificationClose.addEventListener('click', closeNotificationPanel);
      }
      document.addEventListener('click', function (event) {
        if (!notificationPanel || !notificationToggle) return;
        if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
          closeNotificationPanel();
        }
      });

      updateBadge();
    })();
