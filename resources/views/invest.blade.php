@extends('layouts.app')

@section('content')
<style>
  body { background:#fffaf3 !important; }
  .packages-page { position:relative; min-height:100vh; overflow:hidden; padding:20px 16px 28px; color:#252525; }
  .packages-page::before { content:''; position:absolute; top:-38px; right:-74px; width:180px; height:320px; border-radius:54px 0 0 54px; background:linear-gradient(180deg, #f03512, #d91505); transform:skewX(-8deg); z-index:0; }
  .packages-page::after { content:''; position:absolute; top:162px; right:76px; width:56px; height:130px; background:radial-gradient(circle, rgba(245,164,0,.9) 1.4px, transparent 1.5px); background-size:9px 9px; opacity:.75; z-index:0; }
  .packages-shell { position:relative; z-index:1; max-width:940px; margin:0 auto; }
  .top-row { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:28px; }
  .back-link { display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:50%; background:#fff; color:#d91b0b; font-size:25px; line-height:1; font-weight:900; text-decoration:none; box-shadow:0 8px 22px rgba(30,20,10,.12); }
  .brand-text { color:#d91b0b; font-size:12px; line-height:16px; font-weight:900; letter-spacing:.14em; text-transform:uppercase; }
  .hero-title { margin:0; max-width:620px; }
  .hero-title .black { display:block; color:#101010; font-size:48px; line-height:46px; font-weight:900; font-style:italic; letter-spacing:.02em; text-transform:uppercase; }
  .hero-title .red { display:block; margin-top:4px; color:#e12610; font-size:56px; line-height:54px; font-weight:900; font-style:italic; letter-spacing:.02em; text-transform:uppercase; }
  .hero-copy { margin:18px 0 0; color:#2b2b2b; font-size:23px; line-height:29px; font-weight:800; }
  .swipe-hint { margin:0 0 10px; color:#d91b0b; font-size:12px; line-height:16px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
  .package-track { display:flex; gap:18px; overflow-x:auto; overscroll-behavior-x:contain; scroll-snap-type:x mandatory; padding:0 8px 20px 0; margin-right:-16px; -webkit-overflow-scrolling:touch; }
  .package-track::-webkit-scrollbar { display:none; }
  .package-card { position:relative; flex:0 0 88%; min-height:286px; scroll-snap-align:center; border-radius:28px; background:#fff; border:1px solid rgba(224,30,10,.08); box-shadow:0 14px 28px rgba(45,24,10,.14); overflow:hidden; cursor:pointer; }
  .package-card:focus-visible { outline:3px solid #f5a400; outline-offset:4px; }
  .package-card::after { content:''; position:absolute; right:-40px; top:108px; width:62%; height:96px; background:linear-gradient(90deg, #e12a10, #d61505); box-shadow:0 8px 20px rgba(165,24,9,.22); z-index:1; }
  .package-content { position:relative; z-index:2; padding:30px 24px 26px; max-width:58%; }
  .package-name { margin:0; color:#e12610; font-size:32px; line-height:36px; font-weight:900; font-style:italic; letter-spacing:.04em; text-transform:uppercase; }
  .package-number { color:#f5a400; margin-right:8px; }
  .package-desc { margin:16px 0 0; color:#232323; font-size:16px; line-height:21px; font-weight:500; }
  .price-row { display:flex; align-items:center; gap:10px; margin-top:18px; }
  .price { display:inline-flex; align-items:center; min-height:48px; padding:0 16px; border-radius:12px; background:linear-gradient(180deg, #ef3518, #d91705); color:#fff; font-size:31px; line-height:34px; font-weight:900; }
  .package-terms { display:inline-flex; align-items:center; min-height:38px; margin-top:10px; padding:0 13px; border-radius:12px; background:#f8f2ef; color:#d91b0b; font-size:14px; line-height:18px; font-weight:900; white-space:nowrap; }
  .product-label { position:absolute; z-index:2; right:22px; top:134px; color:#fff; font-size:34px; line-height:36px; font-weight:900; font-style:italic; letter-spacing:.07em; text-transform:uppercase; }
  .payment-card { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:8px auto 0; padding:17px 18px; max-width:790px; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(30,20,10,.1); }
  .payment-copy { color:#252525; font-size:13px; line-height:17px; font-weight:600; }
  .payment-logos { display:flex; align-items:center; gap:10px; color:#174a9f; font-size:16px; line-height:18px; font-weight:900; white-space:nowrap; }
  .dot-row { display:flex; justify-content:center; gap:8px; margin:2px 0 16px; }
  .dot { width:7px; height:7px; border-radius:50%; background:#ffd2c9; }
  .dot.is-active { width:22px; border-radius:999px; background:#e12610; }
  .package-modal { position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:20px; background:rgba(10,10,10,.62); }
  .package-modal.is-open { display:flex; }
  .modal-card { width:min(100%, 480px); max-height:92vh; overflow:auto; border-radius:28px; background:#fff; padding:32px 28px; box-shadow:0 32px 80px rgba(0,0,0,.15); }
  .modal-image { display:block; width:100%; height:auto; border-radius:20px; background:#fff8e8; }
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
  .estimate-card { border-radius:14px; background:linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); border:1.5px solid #e8e8e8; padding:16px 14px; }
  .estimate-label { color:#888; font-size:11px; line-height:14px; font-weight:800; letter-spacing:.5px; text-transform:uppercase; }
  .estimate-value { margin-top:8px; color:#d91b0b; font-size:18px; line-height:24px; font-weight:900; }
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
    .packages-page { padding-inline:13px; }
    .hero-title .black { font-size:40px; line-height:39px; }
    .hero-title .red { font-size:47px; line-height:46px; }
    .hero-copy { font-size:20px; line-height:26px; }
    .package-card { flex-basis:91%; min-height:296px; }
    .package-content { padding:28px 20px 24px; max-width:62%; }
    .package-name { font-size:28px; line-height:32px; }
    .product-label { right:13px; font-size:29px; line-height:32px; }
    .price { font-size:27px; padding-inline:13px; }
    .package-terms { font-size:13px; padding-inline:11px; }
  }
  @media (min-width:760px) {
    .packages-page { padding:34px 22px 42px; }
    .packages-page::before { width:270px; height:460px; right:-90px; }
    .hero-title .black { font-size:62px; line-height:60px; }
    .hero-title .red { font-size:76px; line-height:72px; }
    .hero-copy { font-size:28px; line-height:34px; }
    .package-track { gap:22px; margin-right:0; }
    .package-card { flex-basis:520px; }
  }
</style>

<main class="packages-page">
  <div class="packages-shell">
    <div class="top-row">
      <a class="back-link" href="{{ route('dashboard') }}" aria-label="Back to dashboard">&lsaquo;</a>
      <div class="brand-text">Lotteria Partner</div>
    </div>

    <h1 class="hero-title">
      <span class="black">Our</span>
      <span class="red">Packages</span>
    </h1>
    <p class="hero-copy">Big flavors. Bigger value.<br>Made for every craving.</p>

    <p class="swipe-hint">Swipe packages</p>
    <section class="package-track" aria-label="Swipeable package list">
      <article class="package-card crunch" role="button" tabindex="0" data-package-key="crunch" data-package-title="Basic Package - Basic Share" data-package-price="120" data-package-rate="0.6" data-package-days="180" data-package-image="{{ asset('basic.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">01</span>Basic</h2>
          <p class="package-desc">Basic share package for steady returns.</p>
          <div class="price-row"><span class="price">$120</span></div>
          <div class="package-terms">0.60% daily · 180 days</div>
        </div>
        <div class="product-label">Basic</div>
      </article>

      <article class="package-card loaded" role="button" tabindex="0" data-package-key="loaded" data-package-title="Standard Package - Standard Share" data-package-price="800" data-package-rate="0.7" data-package-days="150" data-package-image="{{ asset('standard.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">02</span>Standard</h2>
          <p class="package-desc">Standard share package for strong market growth.</p>
          <div class="price-row"><span class="price">$800</span></div>
          <div class="package-terms">0.70% daily · 150 days</div>
        </div>
        <div class="product-label">Standard</div>
      </article>

      <article class="package-card supreme" role="button" tabindex="0" data-package-key="supreme" data-package-title="Premium Package - Premium Package" data-package-price="4000" data-package-rate="0.75" data-package-days="120" data-package-image="{{ asset('premium.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">03</span>Premium</h2>
          <p class="package-desc">Premium package for higher return potential.</p>
          <div class="price-row"><span class="price">$4,000</span></div>
          <div class="package-terms">0.75% daily · 120 days</div>
        </div>
        <div class="product-label">Premium</div>
      </article>

      <article class="package-card premium-plus" role="button" tabindex="0" data-package-key="premium_plus" data-package-title="Premium+ Package - Elite Share" data-package-price="8000" data-package-rate="0.9" data-package-days="80" data-package-image="{{ asset('premium+.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">04</span>Premium+</h2>
          <p class="package-desc">Elite package for maximum returns.</p>
          <div class="price-row"><span class="price">$8,000</span></div>
          <div class="package-terms">0.90% daily · 80 days</div>
        </div>
        <div class="product-label">Premium+</div>
      </article>
    </section>

    <div class="dot-row" aria-hidden="true">
      <span class="dot is-active"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <section class="payment-card" aria-label="Payment methods">
      <div class="payment-copy">Pay securely with your preferred payment method</div>
      <div class="payment-logos">VISA MC UPI Paytm</div>
    </section>
  </div>
</main>

<div class="package-modal" id="packageModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-label="Package details">
    <img class="modal-image" id="packageModalImage" src="" alt="">
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
      <span>Amount in USD</span>
      <input type="number" name="amount" id="amountInput" min="1" step="0.01" value="{{ old('amount') }}" placeholder="Enter amount">
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
          <img src="{{ asset('landbank_logo_2021_12_12_18_42_13.jpg') }}" alt="Landbank logo">
          <img src="{{ asset('bank-of-the-philippine-islands-bpi-logo-vector.png') }}" alt="BPI logo">
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
        <img src="{{ asset('landbank_logo_2021_12_12_18_42_13.jpg') }}" alt="Landbank logo">
        <span>Landbank</span>
      </button>
      <button class="bank-logo-item" type="button" data-bank-qr="{{ asset('BPIQR.png') }}" data-bank-name="BPI">
        <img src="{{ asset('bank-of-the-philippine-islands-bpi-logo-vector.png') }}" alt="BPI logo">
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
    <img class="modal-image" id="qrModalImage" src="" alt="Bank QR code">
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
    var phpRate = {{ json_encode(config('currency.usd_to_php', 58)) }};
    if (!track || !dots.length) return;

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
      dots.forEach(function (dot, index) {
        dot.classList.toggle('is-active', index === active);
      });
    }

    track.addEventListener('scroll', function () {
      window.requestAnimationFrame(updateDots);
    }, { passive:true });
    updateDots();

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
        days: card.dataset.packageDays
      };
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      if (modalCancel) modalCancel.focus();
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

    function openAmountModal() {
      if (!amountModal || !selectedPackage) return;
      closeModal(false);
      amountPackageKey.value = selectedPackage.key;
      amountPackageTitle.textContent = selectedPackage.title;
      amountPackageCopy.textContent = 'Minimum $' + Number(selectedPackage.price).toLocaleString() + ' with ' + selectedPackage.rate + '% daily interest for ' + selectedPackage.days + ' days.';
      amountInput.min = selectedPackage.price;
      amountInput.placeholder = 'Minimum $' + Number(selectedPackage.price).toLocaleString();
      if (!amountInput.value) amountInput.value = selectedPackage.price;
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
      var amount = Number(amountInput.value || 0);
      var rate = Number(selectedPackage.rate || 0) / 100;
      var days = Number(selectedPackage.days || 0);
      var daily = amount * rate;
      var weekly = daily * 7;
      var total = daily * days;

      if (dailyEstimate) dailyEstimate.textContent = money(daily);
      if (weeklyEstimate) weeklyEstimate.textContent = money(weekly);
      if (totalEstimate) totalEstimate.textContent = money(total);
      if (estimateNote) {
        var convertedLabel = selectedCurrency === 'PHP' ? 'PHP estimate at ₱' + phpRate + ' per $1. ' : '';
        estimateNote.textContent = convertedLabel + money(amount) + ' x ' + selectedPackage.rate + '% = ' + money(daily) + ' daily. Estimated total income for ' + days + ' days is ' + money(total) + '.';
      }
    }

    cards.forEach(function (card) {
      card.addEventListener('pointerdown', function (event) {
        pointerStartX = event.clientX;
        pointerStartY = event.clientY;
      });
      card.addEventListener('click', function (event) {
        var movedX = Math.abs(event.clientX - pointerStartX);
        var movedY = Math.abs(event.clientY - pointerStartY);
        if (movedX > 12 || movedY > 12) return;
        openModal(card);
      });
      card.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
          openModal(card);
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
      var totalReturn = dailyIncome * durationDays;

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
        selectedCurrency = button.dataset.currency || 'USD';
        currencyButtons.forEach(function (item) {
          item.classList.toggle('is-active', item === button);
        });
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
