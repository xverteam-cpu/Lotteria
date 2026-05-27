@extends('layouts.app')

@section('content')
<style>
  body { background:#ffffff !important; }
  .dashboard-shell { max-width:1120px; margin:18px auto 36px; padding:0 12px; }
  .account-hero { position:relative; overflow:hidden; border-radius:16px; background:#b00000; color:#fff; box-shadow:0 10px 24px rgba(176,0,0,.2); }
  .account-hero img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center; }
  .account-hero::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg, rgba(176,0,0,.92), rgba(227,27,35,.68), rgba(245,164,0,.32)); }
  .hero-inner { position:relative; z-index:1; padding:28px 16px 20px; }
  .hero-kicker { margin:0 0 8px; color:#ffe0a3; font-size:12px; line-height:16px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; }
  .balance-label { color:#ffe0a3; font-size:14px; line-height:19px; font-weight:700; }
  .balance-value { margin-top:4px; color:#fff; font-size:32px; line-height:38px; font-weight:800; }
  .hero-actions { display:flex; flex-wrap:wrap; gap:6px; margin-top:12px; }
  .hero-action { display:inline-flex; align-items:center; justify-content:center; min-height:32px; padding:0 11px; border-radius:7px; border:1px solid rgba(255,255,255,.35); background:transparent; color:#fff; font-size:10px; line-height:13px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; text-decoration:none; cursor:pointer; }
  .hero-action.primary { border-color:#fff; background:#fff; color:#c40000; }
  .notice { margin-top:14px; max-width:560px; border-radius:10px; background:rgba(255,255,255,.12); padding:12px 14px; color:#fff; font-size:13px; line-height:19px; }
  .summary-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:12px; margin:14px 0; }
  .swipe-invest {
    position:relative;
    overflow:hidden;
    display:flex;
    align-items:center;
    gap:12px;
    width:100%;
    min-height:50px;
    margin:14px auto 0;
    padding:7px;
    border:0;
    border-radius:999px;
    background:linear-gradient(90deg, #c40000, #e31b23);
    color:#fff;
    text-decoration:none;
    box-shadow:0 10px 24px rgba(196,0,0,.22);
  }
  .swipe-invest::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(90deg, rgba(196,0,0,0) 0%, rgba(255,255,255,.18) 42%, rgba(255,255,255,.72) 74%, #ffffff 100%);
    pointer-events:none;
  }
  .swipe-knob {
    position:relative;
    z-index:1;
    width:38px;
    height:38px;
    flex:0 0 auto;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
    color:#c40000;
    font-size:20px;
    line-height:1;
    font-weight:900;
    box-shadow:0 4px 12px rgba(0,0,0,.16);
  }
  .swipe-copy {
    position:relative;
    z-index:1;
    flex:1;
    min-width:0;
    text-align:left;
  }
  .swipe-title {
    display:block;
    font-size:13px;
    line-height:17px;
    font-weight:900;
    letter-spacing:.08em;
    text-transform:uppercase;
  }
  .swipe-hint {
    display:block;
    margin-top:2px;
    color:#ffe0a3;
    font-size:11px;
    line-height:15px;
    font-weight:700;
  }
  .swipe-runner {
    position:absolute;
    top:50%;
    right:42px;
    z-index:1;
    width:76px;
    height:32px;
    animation:runner-slide 1.45s linear infinite;
    transform:translateY(-50%);
    pointer-events:none;
  }
  .swipe-runner span {
    position:absolute;
    top:50%;
    width:24px;
    height:24px;
    border-top:9px solid #f5c400;
    border-right:9px solid #f5c400;
    transform:translateY(-50%) rotate(45deg);
  }
  .swipe-runner span:nth-child(1) { left:0; }
  .swipe-runner span:nth-child(2) { left:24px; }
  .swipe-runner span:nth-child(3) { left:48px; }
  @keyframes runner-slide {
    0% { transform:translate(-60px, -50%); opacity:0; }
    20% { opacity:.9; }
    78% { opacity:.9; }
    100% { transform:translate(34px, -50%); opacity:0; }
  }
  .summary-card { padding:16px; border-radius:16px; background:#fff; box-shadow:0 10px 24px rgba(15,23,42,.08); border:1px solid #ffc5cd; }
  .summary-card.featured { background:linear-gradient(135deg, #c40000, #e31b23 62%, #f5a400); border-color:#e31b23; color:#fff; }
  .summary-label { color:#c40000; font-size:11px; line-height:15px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
  .summary-card.featured .summary-label { color:#ffe0a3; }
  .summary-value { margin-top:10px; color:#001a33; font-size:25px; line-height:31px; font-weight:900; }
  .summary-card.featured .summary-value { color:#fff; }
  .summary-help { margin-top:10px; color:#64748b; font-size:12px; line-height:17px; }
  .summary-card.featured .summary-help { color:#fff8e8; }
  .joy-marquee {
    overflow:hidden;
    margin:14px -12px 14px;
    border-block:1px solid #f5a400;
    background:#fff8e8;
  }
  .joy-track {
    display:flex;
    width:max-content;
    animation:joy-scroll 14s linear infinite;
  }
  .joy-text {
    flex:0 0 auto;
    margin:0;
    padding:14px 18px;
    color:#cf332b;
    font-size:34px;
    line-height:40px;
    font-weight:900;
    letter-spacing:.02em;
    white-space:nowrap;
    text-transform:uppercase;
  }
  @keyframes joy-scroll {
    from { transform:translateX(0); }
    to { transform:translateX(-50%); }
  }
  .activity-card { border-radius:18px; background:#fff; padding:16px; box-shadow:0 10px 24px rgba(15,23,42,.08); border:1px solid #ffc5cd; }
  .activity-head { display:block; gap:12px; }
  .activity-title { color:#c40000; font-size:15px; line-height:20px; font-weight:800; }
  .activity-item { display:block; gap:14px; margin-top:10px; border-radius:14px; background:#fff5f5; padding:14px 16px; color:#475569; font-size:14px; line-height:20px; }
  .activity-item strong { display:block; color:#c40000; }
  .activity-time { color:#64748b; font-size:12px; }
  .logout-form { display:inline; }
  @media (min-width:761px) {
    .dashboard-shell { margin:28px auto 48px; padding:0 18px; }
    .account-hero { border-radius:18px; }
    .hero-inner { padding:42px 22px 22px; }
    .balance-label { font-size:16px; line-height:22px; }
    .balance-value { font-size:40px; line-height:46px; }
    .hero-action { min-height:34px; padding:0 15px; }
    .swipe-invest { margin-top:18px; }
    .summary-grid { gap:16px; margin:18px 0 22px; }
    .summary-card { padding:20px; }
    .joy-marquee { margin:18px 0; border-radius:16px; }
    .joy-text { font-size:38px; line-height:44px; padding:16px 24px; }
    .activity-card { padding:24px; }
    .activity-head, .activity-item { display:flex; align-items:center; justify-content:space-between; }
  }
</style>

@php($user = auth()->user())

<main class="dashboard-shell">
  <section class="account-hero">
    <img src="/MGames%20Festival.png" alt="">
    <div class="hero-inner">
      <div class="balance-label">Available balance</div>
      <div class="balance-value">$0.00</div>
      <div class="hero-actions">
        <a class="hero-action" href="{{ route('unavailable') }}">Withdraw</a>
        <a class="hero-action" href="{{ route('unavailable') }}">Transfer</a>
        <a class="hero-action" href="{{ route('unavailable') }}">Statements</a>
        @if ($user->is_admin)
          <a class="hero-action" href="{{ route('admin.dashboard') }}">Admin</a>
        @endif
        <form class="logout-form" action="{{ route('logout') }}" method="post">
          @csrf
          <button class="hero-action" type="submit">Logout</button>
        </form>
      </div>
    </div>
  </section>

  <a class="swipe-invest" href="{{ route('unavailable') }}" aria-label="Swipe to invest">
    <span class="swipe-knob">›</span>
    <span class="swipe-copy">
      <span class="swipe-title">Swipe to Invest</span>
      <span class="swipe-hint">Start your partner investment</span>
    </span>
    <span class="swipe-runner" aria-hidden="true">
      <span></span>
      <span></span>
      <span></span>
    </span>
  </a>

  <section class="summary-grid" aria-label="Account summary">
    <div class="summary-card featured">
      <div>
        <div class="summary-label">Investment</div>
        <div class="summary-value">$0.00</div>
      </div>
      <div class="summary-help">Active partner capital.</div>
    </div>
    <div class="summary-card">
      <div>
        <div class="summary-label">Income</div>
        <div class="summary-value">$0.00</div>
      </div>
      <div class="summary-help">Total earned income.</div>
    </div>
    <div class="summary-card">
      <div>
        <div class="summary-label">Referral</div>
        <div class="summary-value">$0.00</div>
      </div>
      <div class="summary-help">Referral rewards.</div>
    </div>
    <div class="summary-card">
      <div>
        <div class="summary-label">Daily Interest</div>
        <div class="summary-value">$0.00</div>
      </div>
      <div class="summary-help">Estimated daily earnings.</div>
    </div>
  </section>

  <section class="joy-marquee" aria-label="Lotteria favorites message">
    <div class="joy-track">
      <p class="joy-text">ALL YOUR FAVORITES IN HERE FEEL THE TASTE OF JOY!</p>
      <p class="joy-text" aria-hidden="true">ALL YOUR FAVORITES IN HERE FEEL THE TASTE OF JOY!</p>
    </div>
  </section>

  <section class="activity-card">
    <div class="activity-head">
      <div class="activity-title">Recent activity</div>
      <a href="{{ route('unavailable') }}" style="color:#c40000;text-decoration:none;font-size:13px;font-weight:800;">View all</a>
    </div>
    <div class="activity-item"><div><strong>Partner account created</strong><span class="activity-time">{{ $user->created_at?->format('h:i A') ?: 'Now' }} - submitted</span></div><div style="font-weight:800;color:#c40000;">{{ $user->region ?: 'N/A' }}</div></div>
    <div class="activity-item"><div><strong>Application profile saved</strong><span class="activity-time">{{ $user->last_seen_at?->format('h:i A') ?: 'Now' }} - active</span></div><div style="font-weight:800;color:#c40000;">{{ $user->phone ?: 'N/A' }}</div></div>
  </section>
</main>
@endsection
