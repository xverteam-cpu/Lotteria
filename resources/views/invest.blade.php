@extends('layouts.app')

@section('content')
<style>
  body { background:#fffaf3 !important; font-family: Inter, 'Plus Jakarta Sans', 'SF Pro Display', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
  .packages-page { position:relative; min-height:100vh; overflow-x:hidden; overflow-y:visible; padding:20px 16px 28px; color:#252525; }
  .packages-page::before { content:''; position:absolute; top:-38px; right:-74px; width:180px; height:320px; border-radius:54px 0 0 54px; background:linear-gradient(180deg, #f03512, #d91505); transform:skewX(-8deg); z-index:0; }
  .packages-page::after { content:''; position:absolute; top:162px; right:76px; width:56px; height:130px; background:radial-gradient(circle, rgba(245,164,0,.9) 1.4px, transparent 1.5px); background-size:9px 9px; opacity:.75; z-index:0; }
  .packages-shell { position:relative; z-index:1; max-width:940px; margin:0 auto; padding:0 20px; }
  .top-row { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:28px; }
  .back-link { display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:50%; background:#fff; color:#d91b0b; font-size:25px; line-height:1; font-weight:900; text-decoration:none; box-shadow:0 8px 22px rgba(30,20,10,.12); }
  .brand-text { color:#d91b0b; font-size:12px; line-height:16px; font-weight:900; letter-spacing:.14em; text-transform:uppercase; }
  .hero-title { margin:0; max-width:620px; }
  .hero-title .black { display:block; color:#101010; font-size:48px; line-height:46px; font-weight:900; letter-spacing:.02em; text-transform:uppercase; }
  .hero-title .red { display:block; margin-top:4px; color:#e12610; font-size:56px; line-height:54px; font-weight:900; letter-spacing:.02em; text-transform:uppercase; }
  .hero-copy { margin:18px 0 0; color:#2b2b2b; font-size:23px; line-height:29px; font-weight:800; }
  .investment-summary-card { position:relative; overflow:hidden; border-radius:32px; margin:26px 0 28px; background:#fff; box-shadow:0 26px 80px rgba(0,0,0,.08); }
  .investment-summary-card::before { content:''; position:absolute; inset:0; background:radial-gradient(circle at top right, rgba(225,35,16,.14), transparent 30%), linear-gradient(180deg, rgba(255,255,255,.9), rgba(255,255,255,.72)); pointer-events:none; }
  .investment-summary-card-inner { position:relative; z-index:2; display:grid; gap:24px; padding:30px 28px 26px; }
  .summary-top { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; }
  .summary-label { display:block; color:#566370; font-size:12px; letter-spacing:.24em; text-transform:uppercase; font-weight:900; }
  .summary-value { margin-top:8px; color:#101010; font-size:46px; line-height:1; font-weight:900; letter-spacing:-.02em; }
  .summary-copy { margin:10px 0 0; color:#566370; font-size:14px; line-height:22px; max-width:460px; }
  .summary-actions { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:14px; }
  .summary-action { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; min-height:112px; border-radius:24px; border:1px solid rgba(217,27,11,.12); background:#fff; color:#101010; font-size:14px; font-weight:900; transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease; cursor:pointer; }
  .summary-action:hover, .summary-action:focus { transform:translateY(-2px); border-color:#d91b0b; box-shadow:0 18px 32px rgba(217,27,11,.12); outline:none; }
  .summary-action-icon { display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:16px; background:rgba(225,35,16,.08); color:#d91b0b; font-size:20px; }
  .summary-action span { display:block; }
  .package-track { display:flex; flex-wrap:nowrap; align-items:flex-start; justify-content:flex-start; gap:20px; overflow-x:auto; overflow-y:visible; overscroll-behavior-x:contain; scroll-snap-type:x mandatory; padding:8px 20px 20px 20px; margin-right:0; -webkit-overflow-scrolling:touch; scroll-padding:0 20px; }
  .package-track::-webkit-scrollbar { display:none; }
  .dot-row { display:none; }
  .package-status { margin:20px 0 28px; }
  .status-card { padding:24px; border-radius:28px; background:#fff; box-shadow:0 18px 48px rgba(0,0,0,.06); border:1px solid rgba(217,27,11,.08); }
  .status-top { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:18px; }
  .status-title { margin:0; color:#111827; font-size:18px; line-height:1.2; font-weight:900; }
  .status-copy { margin:6px 0 0; color:#6b7280; font-size:14px; line-height:20px; }
  .status-badge { display:inline-flex; align-items:center; justify-content:center; min-width:82px; padding:10px 14px; border-radius:999px; background:#f8f2ef; color:#d91b0b; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:.08em; }
  .status-meter { height:16px; border-radius:999px; background:#f1f5f9; overflow:hidden; }
  .status-progress { width:0%; height:100%; background:linear-gradient(90deg, #f59e0b, #f97316); border-radius:999px; transition:width .35s ease; }
  .status-meta { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:14px; color:#334155; font-size:14px; font-weight:700; }
  .status-copy-label { display:block; margin:20px 0 14px; color:#111827; font-size:18px; line-height:1.2; font-weight:900; letter-spacing:.02em; text-transform:none; }
  .status-steps { display:grid; gap:16px; }
  .status-step { display:grid; grid-template-columns:auto 1fr; gap:14px; align-items:flex-start; padding:16px 16px 16px 18px; border-radius:22px; background:#f9f7f4; border:1px solid rgba(217,27,11,.1); }
  .status-step-number { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; background:#fff0ee; color:#d91b0b; font-size:14px; font-weight:900; box-shadow:0 8px 16px rgba(217,27,11,.08); }
  .status-step-title { margin:0; color:#111827; font-size:15px; font-weight:900; line-height:1.2; }
  .status-step-copy { margin:6px 0 0; color:#6b7280; font-size:13px; line-height:19px; }
  .package-card { position:relative; z-index:0; flex:0 0 min(88vw, 460px); max-width:460px; min-height:auto; scroll-snap-align:center; border-radius:32px; background:#fff; border:1px solid rgba(217,27,11,.12); box-shadow:0 24px 70px rgba(14,25,30,.08); overflow:hidden; cursor:pointer; touch-action:manipulation; -webkit-tap-highlight-color: rgba(0,0,0,0.08); user-select:none; transform-origin:top center; transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease, opacity .25s ease; transform:translateY(0) scale(0.95); opacity:.72; }
  .package-card:hover { transform:translateY(-2px) scale(0.96); box-shadow:0 32px 70px rgba(14,25,30,.12); border-color:rgba(217,27,11,.18); }
  .package-card:active { transform:translateY(-1px) scale(0.96); box-shadow:0 26px 60px rgba(14,25,30,.1); }
  .package-card.is-active { z-index:2; transform:translateY(-4px) scale(1.05); opacity:1; box-shadow:0 36px 88px rgba(14,25,30,.16); border-color:rgba(217,27,11,.16); }
  .package-card:focus-visible { outline:3px solid #f5a400; outline-offset:4px; }
  .package-card::after { display:none; }
  .package-card * { pointer-events:auto !important; }
  .package-content { position:relative; z-index:2; padding:28px 24px 24px; max-width:100%; display:grid; gap:22px; }
  .package-card-top { display:grid; gap:10px; }
  .package-card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; }
  .package-card-title { margin:0; color:#101010; font-size:22px; line-height:1.15; font-weight:900; }
  .package-card-pill { display:inline-flex; align-items:center; justify-content:center; padding:8px 14px; border-radius:999px; background:#fff3ef; color:#d91b0b; font-size:12px; font-weight:900; letter-spacing:.06em; text-transform:uppercase; border:1px solid rgba(217,27,11,.12); }
  .package-card-copy { margin:0; color:#6d6d6d; font-size:14px; line-height:20px; }
  .package-card-features { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; }
  .package-feature { padding:16px 14px; border-radius:18px; background:#f9f8f7; border:1px solid rgba(227,34,19,.08); }
  .package-feature-label { display:block; color:#778291; font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; margin-bottom:8px; }
  .package-feature-value { color:#101010; font-size:16px; font-weight:900; line-height:1.2; }
  .package-card-action { width:100%; min-height:52px; border:none; border-radius:20px; background:#d91b0b; color:#fff; font-size:15px; font-weight:900; letter-spacing:.02em; cursor:pointer; transition:background .2s ease, transform .2s ease; }
  .package-card-action:hover, .package-card-action:focus { background:#b71813; transform:translateY(-1px); outline:none; }
  .package-card-action:active { transform:translateY(0); }
  .package-card.supreme { background: linear-gradient(180deg, #141b2f 0%, #24325c 100%); border-color: rgba(255,255,255,.14); }
  .package-card.premium-plus { background: #0a0a0a; border-color: rgba(255,255,255,.14); }
  .package-card.supreme .package-card-title,
  .package-card.supreme .package-card-copy,
  .package-card.supreme .package-feature-value,
  .package-card.supreme .package-card-pill,
  .package-card.premium-plus .package-card-title,
  .package-card.premium-plus .package-card-copy,
  .package-card.premium-plus .package-feature-value,
  .package-card.premium-plus .package-card-pill { color:#fff; }
  .package-card.supreme .package-card-pill,
  .package-card.premium-plus .package-card-pill { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.16); }
  .package-card.supreme .package-feature,
  .package-card.premium-plus .package-feature { background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.12); }
  .package-card.supreme .package-feature-label,
  .package-card.premium-plus .package-feature-label { color: rgba(255,255,255,.7); }
  .package-card.supreme .package-card-action { background: linear-gradient(135deg, #f2b23a, #e28f18); color:#111; }
  .package-card.premium-plus .package-card-action { background: linear-gradient(135deg, #ffd25b, #f18b2d); color:#111; }
  .package-card.supreme .package-card-action:hover,
  .package-card.premium-plus .package-card-action:hover { background: linear-gradient(135deg, #d49b19, #c67c0f); }
  .package-card.premium-plus .package-card-action { background: linear-gradient(135deg, #ffd25b, #f18b2d); color:#111; }
  .package-visual { display:none; }
  .package-visual::before { content:''; position:absolute; inset:0; background-image:var(--package-image); background-size:cover; background-position:center; background-repeat:no-repeat; filter:blur(14px); transform:scale(1.1); opacity:.88; }
  .package-visual::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(255,255,255,.22), rgba(255,255,255,.06)); pointer-events:none; }
  .package-visual svg { position:relative; z-index:1; width:58px; height:58px; color:#fff; opacity:.88; }
  .package-visual path, .package-visual circle { stroke:currentColor; fill:none; }
  .package-visual circle { fill:currentColor; opacity:.7; }
  .price-row { display:flex; align-items:center; gap:10px; margin-top:18px; }
  .price { display:inline-flex; align-items:center; min-height:48px; padding:0 16px; border-radius:12px; background:linear-gradient(180deg, #ef3518, #d91705); color:#fff; font-size:31px; line-height:34px; font-weight:900; }
  .package-terms { display:inline-flex; align-items:center; min-height:38px; margin-top:10px; padding:0 13px; border-radius:12px; background:#f8f2ef; color:#d91b0b; font-size:14px; line-height:18px; font-weight:900; white-space:nowrap; }
  .package-breakdown { margin-top:22px; display:grid; gap:12px; }
  .package-breakdown-item { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:14px 16px; border-radius:18px; background:rgba(241,228,222,.9); border:1px solid rgba(217,27,11,.14); }
  .package-breakdown-label { color:#6b1913; font-size:13px; font-weight:700; letter-spacing:.03em; text-transform:uppercase; }
  .package-breakdown-value { color:#d91b0b; font-size:16px; font-weight:900; }
  .payment-card { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin:8px auto 0; padding:14px 16px; max-width:790px; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(30,20,10,.08); }
  .payment-copy { color:#252525; font-size:13px; line-height:17px; font-weight:600; flex:1 1 180px; min-width:160px; }
  .payment-logos { display:flex; align-items:center; gap:12px; color:#64748B; white-space:nowrap; flex:1 1 280px; justify-content:flex-end; }
  .payment-logos svg { display:block; width:auto; height:20px; }
  .payment-logo { display:inline-flex; align-items:center; justify-content:center; min-width:24px; }
  .dot-row { display:flex; justify-content:center; gap:8px; margin:2px 0 16px; }
  .dot { width:7px; height:7px; border-radius:50%; background:#ffd2c9; }
  .dot.is-active { width:22px; border-radius:999px; background:#e12610; }
  .package-modal { position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:20px; background:rgba(10,10,10,.62); }
  .package-modal.is-open { display:flex; }
  .modal-card { width:min(100%, 480px); max-height:92vh; overflow:auto; border-radius:44px; background:#fff; padding:32px 28px; box-shadow:0 32px 80px rgba(0,0,0,.15); }
  .modal-image { display:block; width:100%; height:auto; border-radius:20px; background:#fff8e8; }

  /* Circular badge showing remaining slots (top-right) */
  .slots-circle {
    position: absolute;
    z-index: 3;
    top: 12px;
    right: 12px;
    width: 56px;
    height: 56px;
    background: linear-gradient(90deg,#d61505,#e12a10);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 11px;
    line-height: 1.05;
    text-align: center;
    padding: 6px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    pointer-events: none;
    text-transform: none;
    letter-spacing: 0;
    white-space: normal;
  }

  .modal-image-wrap { position: relative; }
  .modal-actions { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:28px; }
  .modal-button { display:inline-flex; align-items:center; justify-content:center; min-height:52px; border-radius:12px; border:0; font-size:14px; line-height:18px; font-weight:900; letter-spacing:.04em; text-transform:uppercase; text-decoration:none; cursor:pointer; transition:all 0.2s ease; }
  .modal-button.confirm { background:#d91b0b; color:#fff; box-shadow:0 8px 24px rgba(217,27,11,.25); }
  .modal-button.confirm:hover { background:#b01609; box-shadow:0 12px 32px rgba(217,27,11,.35); }
  .modal-button.cancel { background:#f5f5f5; color:#666; border:1.5px solid #e0e0e0; }
  .modal-button.cancel:hover { background:#efefef; border-color:#d0d0d0; }
  .amount-title { margin:0 0 12px; color:#1a1a1a; font-size:26px; line-height:32px; font-weight:900; letter-spacing:-.4px; }
  .amount-copy { margin:0 0 24px; color:#666; font-size:15px; line-height:22px; font-weight:500; }
  .amount-field { display:block; margin-bottom:24px; }
  .amount-field span { display:block; margin-bottom:10px; color:#1a1a1a; font-size:13px; line-height:17px; font-weight:700; letter-spacing:.3px; text-transform:uppercase; }
  .amount-field input { width:100%; min-height:56px; border-radius:14px; border:1.5px solid #e5e5e5; padding:16px 18px; color:#1a1a1a; font-size:18px; font-weight:700; outline:none; background:#fafafa; transition:all 0.2s ease; }
  .amount-field input::placeholder { color:#999; font-weight:500; }
  .amount-field input:focus { border-color:#d91b0b; background:#fff; box-shadow:0 0 0 4px rgba(217,27,11,.08); }
  .estimate-grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; margin-top:24px; margin-bottom:20px; }
  .currency-toggle { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:20px; margin-bottom:24px; }
  .currency-button { min-height:44px; border-radius:10px; border:1.5px solid #e0e0e0; background:#f9f9f9; color:#666; font-size:14px; line-height:18px; font-weight:800; cursor:pointer; transition:all 0.2s ease; }
  .currency-button:hover { border-color:#d0d0d0; background:#f5f5f5; }
  .currency-button.is-active { background:#d91b0b; color:#fff; border-color:#d91b0b; box-shadow:0 6px 20px rgba(217,27,11,.2); }
  .estimate-card { display:flex; flex-direction:column; justify-content:center; min-height:90px; border-radius:14px; background:linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); border:1.5px solid #e8e8e8; padding:12px 10px; overflow:hidden; }
  .estimate-label { color:#888; font-size:11px; line-height:14px; font-weight:800; letter-spacing:.5px; text-transform:uppercase; }
  .estimate-value { margin-top:6px; color:#d91b0b; font-size:clamp(10px, 1.8vw, 18px); line-height:1.05; font-weight:900; overflow:hidden; word-break:break-word; display:block; }
  .estimate-note { margin:18px 0 0; color:#999; font-size:13px; line-height:19px; font-weight:500; }
  .form-error { margin:0 0 20px; border-radius:12px; background:#ffebeb; border:1.5px solid #f5c2c2; padding:14px 16px; color:#c41e1e; font-size:13px; line-height:18px; font-weight:700; }
  .payment-options { display:grid; gap:12px; margin-top:20px; }
  .payment-choice { display:flex; align-items:center; justify-content:space-between; gap:14px; width:100%; min-height:64px; border-radius:14px; border:1.5px solid #e8e8e8; background:#f9f9f9; color:#1a1a1a; padding:0 18px; font-size:14px; line-height:18px; font-weight:800; cursor:pointer; text-align:left; transition:all 0.2s ease; }
  .payment-choice:hover, .payment-choice:focus { border-color:#d91b0b; background:#fff; box-shadow:0 6px 20px rgba(217,27,11,.12); outline:none; }
  .payment-choice .payment-meta { display:flex; flex-direction:column; align-items:flex-start; gap:4px; }
  .payment-choice .payment-meta span { color:#888; font-size:13px; line-height:17px; font-weight:600; }
  .payment-choice .payment-icons { display:flex; align-items:center; gap:10px; }
  .payment-choice .payment-icons img { height:28px; width:auto; border-radius:8px; background:#ffffff; padding:4px; box-shadow:0 4px 12px rgba(0,0,0,.08); }
  .bank-logos { display:flex; flex-wrap:wrap; gap:12px; margin:24px 0; }
  .bank-logo-item { flex:1 1 45%; display:flex; align-items:center; justify-content:center; gap:10px; padding:18px 14px; border-radius:14px; border:1.5px solid #e8e8e8; background:#f9f9f9; cursor:pointer; text-align:center; transition:all 0.2s ease; }
  .bank-logo-item img { height:36px; width:auto; }
  .bank-logo-item span { color:#1a1a1a; font-size:14px; line-height:18px; font-weight:800; }
  .bank-logo-item:hover, .bank-logo-item:focus { border-color:#d91b0b; background:#fff; box-shadow:0 6px 20px rgba(217,27,11,.12); outline:none; }
  @media (max-width:430px) {
    /* Mobile-first fixes */
    html, body { overflow-x: hidden; }
    * { box-sizing: border-box; }
    /* Use small safe paddings so content sits close to screen edges */
    .packages-page { padding-inline:12px; padding-top:12px; padding-bottom:18px; }
    .packages-shell { padding:0; width:100%; max-width:100%; }
    .hero-title .black { font-size:40px; line-height:39px; }
    .hero-title .red { font-size:47px; line-height:46px; }
    .hero-copy { font-size:20px; line-height:26px; }

    /* Make track use full width and avoid extra inner padding */
    .package-track { padding:8px 0 16px 0; gap:10px; scroll-padding:10px; padding-inline:10px; overflow-x:auto; }

    /* Ensure package cards fully fit inside the viewport without clipping */
    .package-card { flex:0 0 calc(100% - 20px); width:calc(100% - 20px); max-width:calc(100% - 20px); margin:0; box-sizing:border-box; min-height:auto; padding:12px; border-radius:18px; transform:translateY(0) scale(1); scroll-snap-align:start; }
    .package-card:hover, .package-card:active, .package-card.is-active { transform:translateY(0) scale(1); }
    .package-content { padding:14px 12px; }

    /* Hide decorative pseudo-elements that can cause overflow */
    .packages-page::before, .packages-page::after, .package-visual { display:none !important; }

    /* Compact payment area */
    .payment-card { justify-content:space-between; padding:12px 12px; gap:10px; }
    .payment-copy { flex:1 1 100%; min-width:0; margin-bottom:8px; }
    .payment-logos { flex:1 1 100%; justify-content:flex-start; gap:10px; }
    .payment-logos svg { height:18px; }

    .package-name { font-size:28px; line-height:32px; }
    .price { font-size:27px; padding-inline:8px; }
    .package-terms { font-size:13px; padding-inline:8px; }
  }
  @media (min-width:760px) {
    .packages-page { padding:34px 22px 42px; }
    .packages-page::before { width:270px; height:460px; right:-90px; }
    .hero-title .black { font-size:62px; line-height:60px; }
    .hero-title .red { font-size:76px; line-height:72px; }
    .hero-copy { font-size:28px; line-height:34px; }
    .package-track { gap:22px; margin-right:0; }
    .package-card { flex-basis:520px; }
    .package-card { flex:1 1 100%; max-width:100%; }
  }

  /* Additional mobile adjustments for small phones */
  @media (max-width:480px) {
    .packages-page { padding:12px 12px 16px; }
    .packages-shell { padding:0; }
    .hero-title .black { font-size:34px; line-height:36px; }
    .hero-title .red { font-size:40px; line-height:42px; }
    .hero-copy { font-size:18px; line-height:24px; }
    .investment-summary-card { border-radius:14px; margin:12px 0 12px; }
    .investment-summary-card-inner { padding:12px 12px 12px; gap:8px; }
    .summary-top { align-items:center; }
    .summary-value { font-size:26px; margin-top:4px; }
    .summary-copy { margin-top:6px; font-size:13px; }
    .summary-actions { grid-template-columns:repeat(3, 1fr); gap:8px; }
    .summary-action { min-height:84px; border-radius:12px; }
    .package-track { padding:8px 10px 16px 10px; gap:10px; }
    .package-card { flex:0 0 88vw; max-width:88vw; border-radius:18px; min-height:260px; padding:10px; }
    .package-content { padding:12px 10px 14px; gap:10px; }
    .package-card-title { font-size:18px; }
    .package-card-copy { font-size:13px; }
    .package-card-features { grid-template-columns:repeat(3, 1fr); gap:8px; }
    .package-feature { padding:10px 8px; border-radius:12px; }
    .package-feature-value { font-size:14px; }
    .package-card-action { min-height:44px; font-size:14px; border-radius:14px; }
    .price { font-size:22px; }
    .package-terms { font-size:12px; }
    .dot-row { display:flex; }
    .dot { width:6px; height:6px; }
    .dot.is-active { width:16px; }
    .slots-circle { top:10px; right:10px; width:44px; height:44px; font-size:10px; padding:4px; }
    .modal-card { width:min(100%, 92vw); border-radius:18px; padding:16px; }
    .modal-image { border-radius:12px; }
    .estimate-grid { gap:8px; }
    .estimate-card { min-height:76px; padding:10px 8px; }
    .estimate-label { font-size:10px; line-height:12px; }
    .estimate-value { font-size:clamp(9px, 3.2vw, 13px); margin-top:4px; line-height:1.08; }
    .payment-card { padding:10px 12px; gap:8px; }
    .bank-logo-item { flex:1 1 48%; padding:10px; }
  }
</style>

<main class="packages-page">
  <div class="packages-shell">
    <div class="top-row">
      <a class="back-link" href="{{ route('dashboard') }}" aria-label="Back to dashboard">&lsaquo;</a>
      <div class="brand-text">Lotteria Partner</div>
    </div>

    <section class="investment-summary-card" aria-label="Total investment summary">
      <div class="investment-summary-card-inner">
        <div class="summary-top">
          <div>
            <span class="summary-label">Total investment</span>
            <div class="summary-value">₱{{ number_format($totalInvestment ?? 0, 2) }}</div>
            <p class="summary-copy">Your total invested capital is shown here so you can track your position instantly.</p>
          </div>
          <div class="summary-deco" aria-hidden="true"></div>
        </div>
      </div>
    </section>

    <section id="packageTrack" class="package-track" aria-label="Swipeable package list">
      <article class="package-card crunch" role="button" tabindex="0" data-package-key="crunch" data-package-title="Basic" data-package-price="120" data-package-rate="0.6" data-package-days="180" data-package-min="120" data-package-max="799.99" data-package-remaining="{{ $packageSlots['crunch'] ?? 250 }}" data-package-image="{{ asset('basic.png') }}">
        <div class="package-content">
          <div class="package-card-top">
            <div class="package-card-head">
              <h3 class="package-card-title">Basic Package</h3>
              <span class="package-card-pill">Starter</span>
            </div>
            <p class="package-card-copy">Ideal for first-time members</p>
          </div>
          <div class="package-card-features">
            <div class="package-feature">
              <span class="package-feature-label">Capital</span>
              <span class="package-feature-value">$120</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Rate</span>
              <span class="package-feature-value">0.60%</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Term</span>
              <span class="package-feature-value">180D</span>
            </div>
          </div>
          <button type="button" class="package-card-action">Choose Basic</button>
        </div>
      </article>

      <article class="package-card loaded" role="button" tabindex="0" data-package-key="loaded" data-package-title="Standard" data-package-price="800" data-package-rate="0.7" data-package-days="150" data-package-min="800" data-package-max="3999.99" data-package-remaining="{{ $packageSlots['loaded'] ?? 250 }}" data-package-image="{{ asset('standard.png') }}">
        <div class="package-content">
          <div class="package-card-top">
            <div class="package-card-head">
              <h3 class="package-card-title">Standard Package</h3>
              <span class="package-card-pill">Growth</span>
            </div>
            <p class="package-card-copy">Standard share package for strong market growth.</p>
          </div>
          <div class="package-card-features">
            <div class="package-feature">
              <span class="package-feature-label">Capital</span>
              <span class="package-feature-value">$800</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Rate</span>
              <span class="package-feature-value">0.70%</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Term</span>
              <span class="package-feature-value">150D</span>
            </div>
          </div>
          <button type="button" class="package-card-action">Choose Standard</button>
        </div>
      </article>

      <article class="package-card supreme" role="button" tabindex="0" data-package-key="supreme" data-package-title="Premium" data-package-price="4000" data-package-rate="0.75" data-package-days="120" data-package-min="4000" data-package-max="7999.99" data-package-remaining="{{ $packageSlots['supreme'] ?? 250 }}" data-package-image="{{ asset('premium.png') }}">
        <div class="package-content">
          <div class="package-card-top">
            <div class="package-card-head">
              <h3 class="package-card-title">Premium Package</h3>
              <span class="package-card-pill">Premium</span>
            </div>
            <p class="package-card-copy">Premium package for higher return potential.</p>
          </div>
          <div class="package-card-features">
            <div class="package-feature">
              <span class="package-feature-label">Capital</span>
              <span class="package-feature-value">$4,000</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Rate</span>
              <span class="package-feature-value">0.75%</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Term</span>
              <span class="package-feature-value">120D</span>
            </div>
          </div>
          <button type="button" class="package-card-action">Choose Premium</button>
        </div>
      </article>

      <article class="package-card premium-plus" role="button" tabindex="0" data-package-key="premium_plus" data-package-title="Premium+" data-package-price="8000" data-package-rate="0.9" data-package-days="80" data-package-min="8000" data-package-max="50000" data-package-remaining="{{ $packageSlots['premium_plus'] ?? 250 }}" data-package-image="{{ asset('premium+.png') }}">
        <div class="package-content">
          <div class="package-card-top">
            <div class="package-card-head">
              <h3 class="package-card-title">Premium+ Package</h3>
              <span class="package-card-pill">Elite</span>
            </div>
            <p class="package-card-copy">Elite package for maximum returns.</p>
          </div>
          <div class="package-card-features">
            <div class="package-feature">
              <span class="package-feature-label">Capital</span>
              <span class="package-feature-value">$8,000</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Rate</span>
              <span class="package-feature-value">0.90%</span>
            </div>
            <div class="package-feature">
              <span class="package-feature-label">Term</span>
              <span class="package-feature-value">80D</span>
            </div>
          </div>
          <button type="button" class="package-card-action">Choose Premium+</button>
        </div>
      </article>
    </section>

    <div class="dot-row" aria-hidden="true">
      <span class="dot is-active"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <section class="package-status" aria-label="Membership slots status">
      <div class="status-card">
        <div class="status-top">
          <div>
            <h2 class="status-title">Program Status</h2>
            <p class="status-copy">Membership slots filled across all packages.</p>
          </div>
          <div class="status-badge" id="statusPackageLabel">All packages</div>
        </div>
        <div class="status-meter" aria-hidden="true">
          <div class="status-progress" id="statusProgress"></div>
        </div>
        <div class="status-meta">
          <span id="statusPercent">0%</span>
          <span id="statusSlotsRemaining">1000 slots remaining</span>
        </div>
        <div class="status-copy-label">How to Join</div>
        <div class="status-steps" aria-label="How to join steps">
          <div class="status-step">
            <span class="status-step-number">1</span>
            <div>
              <h3 class="status-step-title">Create Member Account</h3>
              <p class="status-step-copy">Register using the official Lotteria Philippines application link.</p>
            </div>
          </div>
          <div class="status-step">
            <span class="status-step-number">2</span>
            <div>
              <h3 class="status-step-title">Choose Package</h3>
              <p class="status-step-copy">Select your preferred participation package and capital amount.</p>
            </div>
          </div>
          <div class="status-step">
            <span class="status-step-number">3</span>
            <div>
              <h3 class="status-step-title">Submit Payment Proof</h3>
              <p class="status-step-copy">Upload your transaction receipt through official payment channels only.</p>
            </div>
          </div>
          <div class="status-step">
            <span class="status-step-number">4</span>
            <div>
              <h3 class="status-step-title">Account Verification</h3>
              <p class="status-step-copy">Your application will be reviewed before account activation.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="payment-card" aria-label="Payment methods">
      <div class="payment-copy">Pay securely with your preferred payment method</div>
      <div class="payment-logos" aria-label="Supported payment networks">
        <span class="payment-logo" aria-label="Visa logo">
          <svg viewBox="0 0 44 14" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="11" font-family="Inter, system-ui, sans-serif" font-size="12" font-weight="800" fill="currentColor">VISA</text>
          </svg>
        </span>
        <span class="payment-logo" aria-label="Mastercard logo">
          <svg viewBox="0 0 32 14" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="7" r="6" fill="currentColor" opacity="0.7" />
            <circle cx="21" cy="7" r="6" fill="currentColor" />
          </svg>
        </span>
        <span class="payment-logo" aria-label="UPI logo">
          <svg viewBox="0 0 40 14" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="11" font-family="Inter, system-ui, sans-serif" font-size="12" font-weight="700" fill="currentColor">UPI</text>
          </svg>
        </span>
        <span class="payment-logo" aria-label="Paytm logo">
          <svg viewBox="0 0 48 14" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="11" font-family="Inter, system-ui, sans-serif" font-size="12" font-weight="700" fill="currentColor">paytm</text>
          </svg>
        </span>
      </div>
    </section>
  </div>
</main>

<div class="package-modal" id="packageModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Package details">
    <div class="modal-image-wrap">
      <img class="modal-image" id="packageModalImage" src="" alt="" loading="lazy" decoding="async">
      <div class="slots-circle" id="packageSlotBadge" aria-hidden="true">250 slots</div>
    </div>
    <div class="modal-actions">
      <button class="modal-button confirm" type="button" id="packageModalConfirm">Confirm</button>
      <button class="modal-button cancel" type="button" id="packageModalCancel">Cancel</button>
    </div>
  </div>
</div>

<div class="package-modal" id="amountModal" aria-hidden="true">
  <form class="modal-card" id="investmentForm" role="dialog" aria-modal="true" aria-label="Investment amount" method="post" action="{{ route('investments.store') }}">
    @csrf
    <input type="hidden" name="package" id="amountPackageKey" value="{{ old('package') }}">
    <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ old('payment_method') }}">
    @if ($errors->any())
      <div class="form-error">{{ $errors->first() }}</div>
    @endif
    <h2 class="amount-title" id="amountPackageTitle">Investment amount</h2>
    <p class="amount-copy" id="amountPackageCopy">Enter the amount you want to invest.</p>
    <label class="amount-field">
      <span id="amountFieldLabel">Amount in USD</span>
      <input type="hidden" name="currency" id="amountCurrencyInput" value="USD">
      <input type="number" name="amount" id="amountInput" min="1" step="0.01" value="{{ old('amount') }}" placeholder="Enter amount">
      <div style="margin-top: 8px; color: #666; font-size: 12px; display: flex; justify-content: space-between;">
        <span id="amountMinDisplay">Min: $120</span>
        <span id="amountMaxDisplay">Max: $799.99</span>
      </div>
    </label>
    <div class="currency-toggle" aria-label="Sample computation currency">
      <button class="currency-button is-active" type="button" data-currency="USD">USD</button>
      <button class="currency-button" type="button" data-currency="PHP">PHP</button>
    </div>
    <div class="estimate-grid" aria-label="Investment estimate">
      <div class="estimate-card">
        <div class="estimate-label">Daily</div>
        <div class="estimate-value" id="dailyEstimate">$0.00</div>
      </div>
      <div class="estimate-card">
        <div class="estimate-label">Weekly</div>
        <div class="estimate-value" id="weeklyEstimate">$0.00</div>
      </div>
      <div class="estimate-card">
        <div class="estimate-label">Total</div>
        <div class="estimate-value" id="totalEstimate">$0.00</div>
      </div>
    </div>
    <p class="estimate-note" id="estimateNote">Sample computation appears after selecting a package.</p>
    <div class="modal-actions">
      <button class="modal-button confirm" type="button" id="amountConfirmPayment">Confirm</button>
      <button class="modal-button cancel" type="button" id="amountModalCancel">Cancel</button>
    </div>
  </form>
</div>

<div class="package-modal" id="paymentModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Mode of payment">
    <h2 class="amount-title">Mode of payment</h2>
    <p class="amount-copy">Choose how you want to pay for this investment.</p>
    <div class="payment-options">
      <button class="payment-choice" type="button" data-payment-method="bank_transfer">
        <div class="payment-meta">
          Bank transfer
          <span>Pay through bank deposit</span>
        </div>
        <div class="payment-icons">
          <img src="{{ asset('landbank_logo_2021_12_12_18_42_13.jpg') }}" alt="Landbank logo" loading="lazy" decoding="async">
          <img src="{{ asset('bank-of-the-philippine-islands-bpi-logo-vector.png') }}" alt="BPI logo" loading="lazy" decoding="async">
        </div>
      </button>
      <button class="payment-choice" type="button" data-payment-method="account_balance">
        <div class="payment-meta">
          Account balance
          <span>Use available account funds</span>
        </div>
      </button>
      <button class="payment-choice" type="button" data-payment-method="crypto">
        <div class="payment-meta">
          Crypto
          <span>Pay using cryptocurrency</span>
        </div>
      </button>
    </div>
    <div class="modal-actions">
      <button class="modal-button cancel" type="button" id="paymentModalBack">Back</button>
      <button class="modal-button cancel" type="button" id="paymentModalCancel">Cancel</button>
    </div>
  </div>
</div>

<div class="package-modal" id="bankModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Bank transfer details">
    <h2 class="amount-title">Bank transfer details</h2>
    <p class="amount-copy">Use any of the supported banks below to complete your deposit.</p>
    <div class="bank-logos">
      <button class="bank-logo-item" type="button" data-bank-qr="{{ asset('LandbankQR.png') }}" data-bank-name="Landbank">
        <img src="{{ asset('landbank_logo_2021_12_12_18_42_13.jpg') }}" alt="Landbank logo" loading="lazy" decoding="async">
        <span>Landbank</span>
      </button>
      <button class="bank-logo-item" type="button" data-bank-qr="{{ asset('BPIQR.png') }}" data-bank-name="BPI">
        <img src="{{ asset('bank-of-the-philippine-islands-bpi-logo-vector.png') }}" alt="BPI logo" loading="lazy" decoding="async">
        <span>BPI</span>
      </button>
    </div>
    <p class="amount-copy">After payment, tap Confirm to submit your deposit details. An admin will review and activate your investment.</p>
    <div class="modal-actions">
      <button class="modal-button confirm" type="button" id="bankModalConfirm">Confirm Payment</button>
      <button class="modal-button cancel" type="button" id="bankModalCancel">Cancel</button>
    </div>
  </div>
</div>

<div class="package-modal" id="qrModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Bank QR code">
    <h2 class="amount-title" id="qrModalTitle">Bank QR</h2>
    <img class="modal-image" id="qrModalImage" src="" alt="Bank QR code" loading="lazy" decoding="async">
    <div class="modal-actions">
      <button class="modal-button confirm" type="button" id="qrModalConfirm">Confirm</button>
      <button class="modal-button cancel" type="button" id="qrModalClose">Cancel</button>
    </div>
  </div>
</div>

<div class="package-modal" id="receiptModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Investment receipt">
    <h2 class="amount-title">Investment Receipt</h2>
    <div style="background: #f5f5f5; padding: 24px; border-radius: 16px; margin: 20px 0;">
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Package</span>
        <span id="receiptPackage" style="font-weight: 600; color: #333;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Investment Amount</span>
        <span id="receiptAmount" style="font-weight: 600; color: #333;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Daily Interest</span>
        <span id="receiptDaily" style="font-weight: 600; color: #d91b0b;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Duration</span>
        <span id="receiptDuration" style="font-weight: 600; color: #333;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Total Expected Return</span>
        <span id="receiptTotal" style="font-weight: 600; color: #d91b0b;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e0e0e0;">
        <span style="color: #666; font-weight: 500;">Payment Method</span>
        <span id="receiptPayment" style="font-weight: 600; color: #333;"></span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 12px 0;">
        <span style="color: #666; font-weight: 500;">Status</span>
        <span style="font-weight: 600; color: #ff9800; background: #fff3e0; padding: 4px 12px; border-radius: 8px; font-size: 12px;">⏳ Pending Approval</span>
      </div>
    </div>
    <p class="amount-copy" style="color: #666; text-align: center; margin: 20px 0;">Admin will review and activate your investment. You'll receive a notification once approved.</p>
    <div class="modal-actions">
      <button class="modal-button confirm" type="button" id="receiptDone">Done</button>
    </div>
  </div>
</div>

<script>
  (function () {
    var track = document.querySelector('.package-track');
    var dots = Array.prototype.slice.call(document.querySelectorAll('.dot'));
    var cards = Array.prototype.slice.call(document.querySelectorAll('.package-card'));
    var modal = document.getElementById('packageModal');
    var modalImage = document.getElementById('packageModalImage');
    var modalConfirm = document.getElementById('packageModalConfirm');
    var modalCancel = document.getElementById('packageModalCancel');
    var investmentForm = document.getElementById('investmentForm');
    var amountModal = document.getElementById('amountModal');
    var amountPackageKey = document.getElementById('amountPackageKey');
    var paymentMethodInput = document.getElementById('paymentMethodInput');
    var amountPackageTitle = document.getElementById('amountPackageTitle');
    var amountPackageCopy = document.getElementById('amountPackageCopy');
    var amountFieldLabel = document.getElementById('amountFieldLabel');
    var amountCurrencyInput = document.getElementById('amountCurrencyInput');
    var amountInput = document.getElementById('amountInput');
    var amountConfirmPayment = document.getElementById('amountConfirmPayment');
    var amountModalCancel = document.getElementById('amountModalCancel');
    var paymentModal = document.getElementById('paymentModal');
    var bankModal = document.getElementById('bankModal');
    var bankModalConfirm = document.getElementById('bankModalConfirm');
    var bankModalCancel = document.getElementById('bankModalCancel');
    var qrModal = document.getElementById('qrModal');
    var qrModalTitle = document.getElementById('qrModalTitle');
    var qrModalImage = document.getElementById('qrModalImage');
    var qrModalClose = document.getElementById('qrModalClose');
    var qrModalConfirm = document.getElementById('qrModalConfirm');
    var receiptModal = document.getElementById('receiptModal');
    var receiptPackage = document.getElementById('receiptPackage');
    var receiptAmount = document.getElementById('receiptAmount');
    var receiptDaily = document.getElementById('receiptDaily');
    var receiptDuration = document.getElementById('receiptDuration');
    var receiptTotal = document.getElementById('receiptTotal');
    var receiptPayment = document.getElementById('receiptPayment');
    var receiptDone = document.getElementById('receiptDone');
    var bankChoiceButtons = Array.prototype.slice.call(document.querySelectorAll('.bank-logo-item'));
    var selectedBank = null;
    var paymentChoices = Array.prototype.slice.call(document.querySelectorAll('.payment-choice'));
    var paymentModalBack = document.getElementById('paymentModalBack');
    var paymentModalCancel = document.getElementById('paymentModalCancel');
    var currencyButtons = Array.prototype.slice.call(document.querySelectorAll('.currency-button'));
    var dailyEstimate = document.getElementById('dailyEstimate');
    var weeklyEstimate = document.getElementById('weeklyEstimate');
    var totalEstimate = document.getElementById('totalEstimate');
    var estimateNote = document.getElementById('estimateNote');
    var pointerStartX = 0;
    var pointerStartY = 0;
    var selectedPackage = null;
    var lastPackageCard = null;
    var selectedCurrency = 'USD';
    var phpRate = @json($phpRate ?? config('currency.usd_to_php', 61.31));
    var phpRateUpdatedAt = @json($phpRateUpdatedAt ?? null);
    if (!track) return;

    function formatDate(date) {
      return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(date);
    }

    function formatMoney(value) {
      return '$' + Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    function updatePackageBreakdownCards() {
      cards.forEach(function (card) {
        var price = Number(card.dataset.packagePrice || 0);
        var rate = Number(card.dataset.packageRate || 0) / 100;
        var days = Number(card.dataset.packageDays || 0);
        var total = price * rate * days;
        var date = new Date();
        date.setDate(date.getDate() + days);
        var totalElem = card.querySelector('[data-breakdown-total]');
        var dateElem = card.querySelector('[data-breakdown-date]');
        if (totalElem) totalElem.textContent = formatMoney(total);
        if (dateElem) dateElem.textContent = formatDate(date);
      });
    }

    function updateDots() {
      var cards = Array.prototype.slice.call(track.querySelectorAll('.package-card'));
      var center = track.scrollLeft + track.clientWidth / 2;
      var active = 0;
      cards.forEach(function (card, index) {
        var cardCenter = card.offsetLeft + card.offsetWidth / 2;
        if (Math.abs(cardCenter - center) < Math.abs(cards[active].offsetLeft + cards[active].offsetWidth / 2 - center)) {
          active = index;
        }
      });
      cards.forEach(function (card, index) {
        card.classList.toggle('is-active', index === active);
      });
      dots.forEach(function (dot, index) {
        dot.classList.toggle('is-active', index === active);
      });
      updateStatus(cards[active]);
    }

    track.addEventListener('scroll', function () {
      window.requestAnimationFrame(updateDots);
    }, { passive:true });
    updatePackageBreakdownCards();
    populatePackageVisuals();
    updateDots();

    function populatePackageVisuals() {
      cards.forEach(function (card) {
        var visual = card.querySelector('.package-visual');
        var image = card.dataset.packageImage;
        if (visual && image) {
          visual.style.setProperty('--package-image', 'url(' + image + ')');
        }
      });
    }

    function updateStatus(card) {
      var defaults = {
        crunch: 250,
        loaded: 250,
        supreme: 250,
        premium_plus: 250,
      };
      var statusLabel = document.getElementById('statusPackageLabel');
      var statusPercent = document.getElementById('statusPercent');
      var statusSlots = document.getElementById('statusSlotsRemaining');
      var statusProgress = document.getElementById('statusProgress');
      if (!statusLabel || !statusPercent || !statusSlots || !statusProgress) return;
      var totals = {
        crunch: 250,
        loaded: 250,
        supreme: 250,
        premium_plus: 250,
      };
      var remainingTotal = 0;
      var capacityTotal = 0;
      cards.forEach(function (cardItem) {
        var key = cardItem.dataset.packageKey;
        var remaining = Number(cardItem.dataset.packageRemaining || totals[key] || 250);
        remainingTotal += remaining;
        capacityTotal += totals[key] || 250;
      });
      var filledTotal = capacityTotal - remainingTotal;
      var percent = capacityTotal > 0 ? Math.round((filledTotal / capacityTotal) * 100) : 0;
      percent = Math.max(0, Math.min(percent, 100));
      statusLabel.textContent = 'All packages';
      statusPercent.textContent = percent + '%';
      statusSlots.textContent = remainingTotal + ' slots remaining';
      statusProgress.style.width = percent + '%';
    }

    function openModal(card) {
      if (!modal || !modalImage) return;
      lastPackageCard = card;
      modalImage.src = card.dataset.packageImage;
      modalImage.alt = card.dataset.packageTitle + ' package';
      selectedPackage = {
        key: card.dataset.packageKey,
        title: card.dataset.packageTitle,
        price: card.dataset.packagePrice,
        rate: card.dataset.packageRate,
        days: card.dataset.packageDays,
        min: card.dataset.packageMin,
        max: card.dataset.packageMax
      };
      updateSlotBadge();
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      if (modalCancel) modalCancel.focus();
    }

    function updateSlotBadge() {
      var badge = document.getElementById('packageSlotBadge');
      if (!badge || !selectedPackage) return;
      var slots = @json($packageSlots ?? []);
      var count = slots[selectedPackage.key] ?? 250;
      badge.textContent = count + ' slots';
    }

    function moveFocusOut(modalElement, shouldReturnFocus) {
      if (modalElement && modalElement.contains(document.activeElement)) {
        document.activeElement.blur();
      }
      if (shouldReturnFocus && lastPackageCard) {
        lastPackageCard.focus();
      }
    }

    function closeModal(shouldReturnFocus) {
      if (!modal || !modalImage) return;
      moveFocusOut(modal, shouldReturnFocus !== false);
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      modalImage.removeAttribute('src');
      modalImage.alt = '';
    }

    function formatCurrencyValue(value) {
      return Number(value || 0).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    function getCurrencySymbol() {
      return selectedCurrency === 'PHP' ? '₱' : '$';
    }

    function getSelectedAmountInUsd(value) {
      var numericValue = Number(value || 0);
      return selectedCurrency === 'PHP' ? numericValue / phpRate : numericValue;
    }

    function updateAmountRange() {
      if (!selectedPackage) return;
      var minAmount = Number(selectedPackage.min || selectedPackage.price);
      var maxAmount = Number(selectedPackage.max || selectedPackage.price);
      var convertedMin = selectedCurrency === 'PHP' ? minAmount * phpRate : minAmount;
      var convertedMax = selectedCurrency === 'PHP' ? maxAmount * phpRate : maxAmount;
      var currencySymbol = getCurrencySymbol();

      amountPackageCopy.textContent = 'Investment range: ' + currencySymbol + formatCurrencyValue(convertedMin) + ' to ' + currencySymbol + formatCurrencyValue(convertedMax) + ' with ' + selectedPackage.rate + '% daily interest for ' + selectedPackage.days + ' days.';
      amountInput.min = convertedMin;
      amountInput.max = convertedMax;
      amountInput.placeholder = 'Minimum ' + currencySymbol + formatCurrencyValue(convertedMin) + ', Maximum ' + currencySymbol + formatCurrencyValue(convertedMax);
      if (amountFieldLabel) amountFieldLabel.textContent = 'Amount in ' + (selectedCurrency === 'PHP' ? 'PHP' : 'USD');
      if (amountCurrencyInput) amountCurrencyInput.value = selectedCurrency;

      var amountMinDisplay = document.getElementById('amountMinDisplay');
      var amountMaxDisplay = document.getElementById('amountMaxDisplay');
      if (amountMinDisplay) amountMinDisplay.textContent = 'Min: ' + currencySymbol + formatCurrencyValue(convertedMin);
      if (amountMaxDisplay) amountMaxDisplay.textContent = 'Max: ' + currencySymbol + formatCurrencyValue(convertedMax);
    }

    function openAmountModal() {
      if (!amountModal || !selectedPackage) return;
      closeModal(false);
      amountPackageKey.value = selectedPackage.key;
      amountPackageTitle.textContent = selectedPackage.title;
      updateAmountRange();
      if (!amountInput.value) {
        var minAmount = Number(selectedPackage.min || selectedPackage.price);
        var convertedDefault = selectedCurrency === 'PHP' ? minAmount * phpRate : minAmount;
        amountInput.value = convertedDefault;
      }
      updateEstimate();
      amountModal.classList.add('is-open');
      amountModal.setAttribute('aria-hidden', 'false');
      amountInput.focus();
    }

    function closeAmountModal() {
      if (!amountModal) return;
      moveFocusOut(amountModal, true);
      amountModal.classList.remove('is-open');
      amountModal.setAttribute('aria-hidden', 'true');
    }

    function openPaymentModal() {
      if (!paymentModal || !investmentForm) return;
      if (typeof investmentForm.reportValidity === 'function' && !investmentForm.reportValidity()) return;
      moveFocusOut(amountModal, false);
      amountModal.classList.remove('is-open');
      amountModal.setAttribute('aria-hidden', 'true');
      paymentModal.classList.add('is-open');
      paymentModal.setAttribute('aria-hidden', 'false');
      if (paymentChoices[0]) paymentChoices[0].focus();
    }

    function closePaymentModal(returnToAmount) {
      if (!paymentModal) return;
      moveFocusOut(paymentModal, !returnToAmount);
      paymentModal.classList.remove('is-open');
      paymentModal.setAttribute('aria-hidden', 'true');
      if (returnToAmount && amountModal) {
        amountModal.classList.add('is-open');
        amountModal.setAttribute('aria-hidden', 'false');
        if (amountConfirmPayment) amountConfirmPayment.focus();
      }
    }

    function money(value) {
      var converted = selectedCurrency === 'PHP' ? Number(value || 0) * phpRate : Number(value || 0);
      var prefix = selectedCurrency === 'PHP' ? '₱' : '$';

      return prefix + converted.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    function updateEstimate() {
      if (!selectedPackage || !amountInput) return;
      var amountInUsd = getSelectedAmountInUsd(amountInput.value);
      var rate = Number(selectedPackage.rate || 0) / 100;
      var days = Number(selectedPackage.days || 0);
      var daily = amountInUsd * rate;
      var weekly = daily * 7;
      var totalInterest = daily * days;
      var total = amountInUsd + totalInterest;

      if (dailyEstimate) dailyEstimate.textContent = money(daily);
      if (weeklyEstimate) weeklyEstimate.textContent = money(weekly);
      if (totalEstimate) totalEstimate.textContent = money(total);
      if (estimateNote) {
        var convertedLabel = '';
        if (selectedCurrency === 'PHP') {
          var formattedRate = Number(phpRate || 0).toFixed(2);
          var updatedSuffix = phpRateUpdatedAt ? ' (updated ' + phpRateUpdatedAt + ')' : ' (updated: —)';
          convertedLabel = 'PHP estimate at ₱' + formattedRate + ' per $1' + updatedSuffix + '. ';
        }

        estimateNote.textContent = convertedLabel + money(amountInUsd) + ' x ' + selectedPackage.rate + '% = ' + money(daily) + ' daily. Estimated total balance after ' + days + ' days is ' + money(total) + '.';
      }
    }

    function activatePackageCard(card) {
      if (!card) return;
      openModal(card);
    }

    if (!cards.length) return;

    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        activatePackageCard(card);
      });
      var actionButton = card.querySelector('.package-card-action');
      if (actionButton) {
        actionButton.addEventListener('click', function (event) {
          event.stopPropagation();
          activatePackageCard(card);
        });
      }
      card.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
          activatePackageCard(card);
          event.preventDefault();
        }
      });
    });

    if (modalCancel) {
      modalCancel.addEventListener('click', closeModal);
    }
    if (modalConfirm) {
      modalConfirm.addEventListener('click', openAmountModal);
    }
    if (amountModalCancel) {
      amountModalCancel.addEventListener('click', closeAmountModal);
    }
    if (amountConfirmPayment) {
      amountConfirmPayment.addEventListener('click', openPaymentModal);
    }
    paymentChoices.forEach(function (choice) {
      choice.addEventListener('click', function () {
        if (!paymentMethodInput || !investmentForm) return;
        if (choice.dataset.paymentMethod === 'bank_transfer') {
          openBankModal();
          return;
        }
        paymentMethodInput.value = choice.dataset.paymentMethod || '';
        investmentForm.submit();
      });
    });

    function openBankModal() {
      if (!bankModal) return;
      closePaymentModal(false);
      bankModal.classList.add('is-open');
      bankModal.setAttribute('aria-hidden', 'false');
      if (bankModalConfirm) bankModalConfirm.focus();
    }

    function closeBankModal(returnToPayment) {
      if (!bankModal) return;
      moveFocusOut(bankModal, !returnToPayment);
      bankModal.classList.remove('is-open');
      bankModal.setAttribute('aria-hidden', 'true');
      if (returnToPayment && paymentModal) {
        paymentModal.classList.add('is-open');
        paymentModal.setAttribute('aria-hidden', 'false');
        if (paymentModalBack) paymentModalBack.focus();
      }
    }

    function openQrModal(bankName, qrUrl) {
      if (!qrModal || !qrModalImage || !qrModalTitle) return;
      selectedBank = { name: bankName, url: qrUrl };
      closeBankModal(false);
      qrModalTitle.textContent = bankName + ' QR Code';
      qrModalImage.src = qrUrl;
      qrModalImage.alt = bankName + ' QR code';
      qrModal.classList.add('is-open');
      qrModal.setAttribute('aria-hidden', 'false');
      if (qrModalClose) qrModalClose.focus();
    }

    function closeQrModal() {
      if (!qrModal || !qrModalImage) return;
      qrModal.classList.remove('is-open');
      qrModal.setAttribute('aria-hidden', 'true');
      qrModalImage.removeAttribute('src');
      qrModalImage.alt = '';
    }

    function populateReceipt(investmentData) {
      if (!receiptModal) return;
      var packageName = investmentData && investmentData.package_name ? investmentData.package_name : (selectedPackage ? selectedPackage.title : 'Selected package');
      var amountValue = investmentData && investmentData.amount != null ? Number(investmentData.amount) : Number(amountInput.value || 0);
      var dailyRate = investmentData && investmentData.daily_interest_rate != null ? Number(investmentData.daily_interest_rate) : Number(selectedPackage ? selectedPackage.rate : 0);
      var durationDays = investmentData && investmentData.duration_days != null ? Number(investmentData.duration_days) : Number(selectedPackage ? selectedPackage.days : 0);
      var dailyIncome = amountValue * (dailyRate / 100);
      var totalInterest = dailyIncome * durationDays;
      var totalReturn = amountValue + totalInterest;

      if (receiptPackage) receiptPackage.textContent = packageName;
      if (receiptAmount) receiptAmount.textContent = money(amountValue);
      if (receiptDaily) receiptDaily.textContent = money(dailyIncome);
      if (receiptDuration) receiptDuration.textContent = durationDays + ' days';
      if (receiptTotal) receiptTotal.textContent = money(totalReturn);
      if (receiptPayment) receiptPayment.textContent = 'Bank Transfer';
      if (receiptModal) {
        receiptModal.classList.add('is-open');
        receiptModal.setAttribute('aria-hidden', 'false');
      }
      if (receiptDone) receiptDone.focus();
    }

    function closeReceiptModal() {
      if (!receiptModal) return;
      receiptModal.classList.remove('is-open');
      receiptModal.setAttribute('aria-hidden', 'true');
    }

    function submitInvestmentRequest(callback) {
      if (!paymentMethodInput || !investmentForm) return;
      paymentMethodInput.value = 'bank_transfer';

      var formData = new FormData(investmentForm);
      var csrfToken = investmentForm.querySelector('input[name="_token"]');
      var headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      };

      if (csrfToken && csrfToken.value) {
        headers['X-CSRF-TOKEN'] = csrfToken.value;
      }

      fetch(investmentForm.action, {
        method: 'POST',
        headers: headers,
        body: formData
      })
        .then(function (response) {
          return response.json().then(function (data) {
            if (!response.ok) {
              throw data;
            }
            return data;
          });
        })
        .then(function (payload) {
          if (payload && payload.success) {
            if (callback) callback(payload);
            return;
          }
          throw payload || { message: 'Unable to submit your investment right now.' };
        })
        .catch(function (error) {
          var message = error && error.message ? error.message : 'Unable to submit your investment right now.';
          window.alert(message);
        });
    }

    bankChoiceButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        openQrModal(button.dataset.bankName || 'Bank', button.dataset.bankQr || '');
      });
    });

    if (qrModalClose) {
      qrModalClose.addEventListener('click', closeQrModal);
    }

    if (qrModal) {
      qrModal.addEventListener('click', function (event) {
        if (event.target === qrModal) closeQrModal();
      });
    }

    if (qrModalConfirm) {
      qrModalConfirm.addEventListener('click', function () {
        submitInvestmentRequest(function (payload) {
          closeQrModal();
          populateReceipt(payload.investment || payload);
        });
      });
    }

    if (receiptDone) {
      receiptDone.addEventListener('click', closeReceiptModal);
    }

    if (bankModalConfirm) {
      bankModalConfirm.addEventListener('click', function () {
        if (selectedBank) {
          openQrModal(selectedBank.name || 'Bank', selectedBank.url || '');
          return;
        }
        closeBankModal(true);
      });
    }

    if (bankModalCancel) {
      bankModalCancel.addEventListener('click', function () {
        closeBankModal(true);
      });
    }

    if (paymentModalBack) {
      paymentModalBack.addEventListener('click', function () {
        closePaymentModal(true);
      });
    }
    if (paymentModalCancel) {
      paymentModalCancel.addEventListener('click', function () {
        closePaymentModal(false);
      });
    }
    if (amountInput) {
      amountInput.addEventListener('input', updateEstimate);
    }
    currencyButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var previousCurrency = selectedCurrency;
        var currentValue = Number(amountInput.value || 0);
        selectedCurrency = button.dataset.currency || 'USD';

        if (Number.isFinite(currentValue) && currentValue > 0) {
          var amountInUsd = previousCurrency === 'PHP' ? currentValue / phpRate : currentValue;
          var convertedValue = selectedCurrency === 'PHP' ? amountInUsd * phpRate : amountInUsd;
          amountInput.value = convertedValue.toFixed(2);
        }

        currencyButtons.forEach(function (item) {
          item.classList.toggle('is-active', item === button);
        });
        updateAmountRange();
        updateEstimate();
      });
    });
    if (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) closeModal();
      });
    }
    if (amountModal) {
      amountModal.addEventListener('click', function (event) {
        if (event.target === amountModal) closeAmountModal();
      });
    }
    if (paymentModal) {
      paymentModal.addEventListener('click', function (event) {
        if (event.target === paymentModal) closePaymentModal(false);
      });
    }
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeModal();
        closeAmountModal();
        closePaymentModal(false);
      }
    });

    @if ($errors->any())
      var oldPackage = '{{ old('package') }}';
      var oldCard = oldPackage ? document.querySelector('[data-package-key="' + oldPackage + '"]') : null;
      if (oldCard) {
        selectedPackage = {
          key: oldCard.dataset.packageKey,
          title: oldCard.dataset.packageTitle,
          price: oldCard.dataset.packagePrice,
          rate: oldCard.dataset.packageRate,
          days: oldCard.dataset.packageDays
        };
        openAmountModal();
      }
    @endif
  })();
</script>
@endsection
