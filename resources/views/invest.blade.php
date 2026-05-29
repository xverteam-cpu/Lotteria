@extends('layouts.app')

@section('content')
<style>
  body { background:#ffffff !important; }
  .invest-shell { min-height:100vh; padding:18px 14px 34px; color:#001a33; }
  .invest-top { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:0 auto 18px; max-width:960px; }
  .back-link { display:inline-flex; align-items:center; justify-content:center; min-height:38px; padding:0 14px; border-radius:999px; background:#fff5f5; color:#c40000; font-size:13px; line-height:17px; font-weight:800; text-decoration:none; border:1px solid #ffc5cd; }
  .brand-mark { color:#c40000; font-size:13px; line-height:17px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
  .invest-card { overflow:hidden; max-width:960px; margin:0 auto; border-radius:22px; background:linear-gradient(180deg, #fff8e8 0%, #ffffff 56%); border:1px solid #ffc5cd; box-shadow:0 14px 34px rgba(15,23,42,.1); }
  .invest-visual { display:flex; align-items:center; justify-content:center; min-height:300px; padding:22px; background:linear-gradient(135deg, rgba(196,0,0,.08), rgba(245,164,0,.14)); }
  .invest-visual img { display:block; width:100%; max-width:560px; height:auto; }
  .invest-content { padding:22px 18px 24px; }
  .invest-kicker { margin:0 0 8px; color:#c40000; font-size:12px; line-height:16px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
  .invest-title { margin:0; color:#c40000; font-size:30px; line-height:36px; font-weight:900; letter-spacing:0; }
  .invest-text { margin:10px 0 0; color:#475569; font-size:14px; line-height:21px; font-weight:600; }
  .invest-actions { display:grid; grid-template-columns:1fr; gap:10px; margin-top:20px; }
  .invest-button { display:inline-flex; align-items:center; justify-content:center; min-height:46px; border-radius:999px; color:#fff; background:#c40000; font-size:14px; line-height:18px; font-weight:900; letter-spacing:.05em; text-transform:uppercase; text-decoration:none; border:0; box-shadow:0 10px 22px rgba(196,0,0,.22); }
  .invest-button.secondary { background:#fff; color:#c40000; border:1px solid #ffc5cd; box-shadow:none; }
  .invest-meta { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px; margin-top:18px; }
  .meta-card { border-radius:16px; background:#fff; border:1px solid #ffe0a3; padding:14px; }
  .meta-label { color:#c40000; font-size:11px; line-height:15px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
  .meta-value { margin-top:6px; color:#001a33; font-size:20px; line-height:25px; font-weight:900; }
  @media (min-width:760px) {
    .invest-shell { padding:28px 18px 48px; }
    .invest-card { display:grid; grid-template-columns:minmax(0, 1.05fr) minmax(0, .95fr); align-items:stretch; }
    .invest-visual { min-height:520px; padding:34px; }
    .invest-content { display:flex; flex-direction:column; justify-content:center; padding:34px; }
    .invest-title { font-size:42px; line-height:48px; }
    .invest-text { font-size:16px; line-height:24px; }
    .invest-actions { grid-template-columns:1fr 1fr; }
  }
</style>

<main class="invest-shell">
  <div class="invest-top">
    <a class="back-link" href="{{ route('dashboard') }}">Back</a>
    <div class="brand-mark">Lotteria Partner</div>
  </div>

  <section class="invest-card">
    <div class="invest-visual">
      <img src="{{ asset('images/Crunch.svg') }}" alt="Lotteria investment">
    </div>
    <div class="invest-content">
      <p class="invest-kicker">Partner investment</p>
      <h1 class="invest-title">Start your Lotteria investment plan</h1>
      <p class="invest-text">Review partner portfolio access, capital activity, referral income, and daily interest once investment packages are connected.</p>

      <div class="invest-actions">
        <a class="invest-button" href="{{ route('unavailable') }}">Continue</a>
        <a class="invest-button secondary" href="{{ route('dashboard') }}">Dashboard</a>
      </div>

      <div class="invest-meta" aria-label="Investment summary">
        <div class="meta-card">
          <div class="meta-label">Status</div>
          <div class="meta-value">Ready</div>
        </div>
        <div class="meta-card">
          <div class="meta-label">Balance</div>
          <div class="meta-value">$0.00</div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
