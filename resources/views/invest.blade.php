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
  .benefits { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:8px; margin:26px 0 24px; }
  .benefit { min-width:0; display:grid; grid-template-columns:28px 1fr; gap:7px; align-items:start; }
  .benefit-icon { display:flex; align-items:center; justify-content:center; width:24px; height:24px; border:2px solid #ef2b14; border-radius:7px; color:#ef2b14; font-size:13px; line-height:1; font-weight:900; }
  .benefit-title { color:#222; font-size:12px; line-height:15px; font-weight:900; }
  .benefit-text { margin-top:1px; color:#4b4b4b; font-size:11px; line-height:14px; font-weight:500; }
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
  .modal-card { width:min(100%, 430px); max-height:92vh; overflow:auto; border-radius:24px; background:#fff; padding:14px; box-shadow:0 24px 60px rgba(0,0,0,.3); }
  .modal-image { display:block; width:100%; height:auto; border-radius:18px; background:#fff8e8; }
  .modal-actions { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:14px; }
  .modal-button { display:inline-flex; align-items:center; justify-content:center; min-height:46px; border-radius:999px; border:0; font-size:14px; line-height:18px; font-weight:900; letter-spacing:.04em; text-transform:uppercase; text-decoration:none; cursor:pointer; }
  .modal-button.confirm { background:#d91b0b; color:#fff; box-shadow:0 10px 22px rgba(217,27,11,.22); }
  .modal-button.cancel { background:#fff5f5; color:#d91b0b; border:1px solid #ffc5cd; }
  .amount-title { margin:4px 0 8px; color:#d91b0b; font-size:23px; line-height:28px; font-weight:900; }
  .amount-copy { margin:0 0 14px; color:#475569; font-size:14px; line-height:20px; font-weight:600; }
  .amount-field { display:block; margin-top:12px; }
  .amount-field span { display:block; margin-bottom:7px; color:#252525; font-size:13px; line-height:17px; font-weight:900; }
  .amount-field input { width:100%; min-height:48px; border-radius:14px; border:1px solid #ffc5cd; padding:0 14px; color:#001a33; font-size:16px; font-weight:800; outline:none; }
  .amount-field input:focus { border-color:#d91b0b; box-shadow:0 0 0 3px rgba(217,27,11,.12); }
  .estimate-grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:8px; margin-top:14px; }
  .currency-toggle { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:14px; }
  .currency-button { min-height:38px; border-radius:999px; border:1px solid #ffc5cd; background:#fff5f5; color:#d91b0b; font-size:13px; line-height:17px; font-weight:900; cursor:pointer; }
  .currency-button.is-active { background:#d91b0b; color:#fff; border-color:#d91b0b; box-shadow:0 8px 18px rgba(217,27,11,.18); }
  .estimate-card { border-radius:14px; background:#fff8e8; border:1px solid #ffe0a3; padding:11px 10px; }
  .estimate-label { color:#8a4b00; font-size:10px; line-height:13px; font-weight:900; letter-spacing:.05em; text-transform:uppercase; }
  .estimate-value { margin-top:5px; color:#d91b0b; font-size:16px; line-height:20px; font-weight:900; }
  .estimate-note { margin:10px 0 0; color:#64748b; font-size:12px; line-height:17px; font-weight:600; }
  .form-error { margin:0 0 12px; border-radius:12px; background:#fff5f5; border:1px solid #ffc5cd; padding:10px 12px; color:#d91b0b; font-size:13px; line-height:18px; font-weight:800; }
  @media (max-width:430px) {
    .packages-page { padding-inline:13px; }
    .hero-title .black { font-size:40px; line-height:39px; }
    .hero-title .red { font-size:47px; line-height:46px; }
    .hero-copy { font-size:20px; line-height:26px; }
    .benefits { gap:6px; }
    .benefit { grid-template-columns:1fr; gap:5px; }
    .benefit-text { font-size:10px; line-height:13px; }
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
    .benefits { max-width:560px; gap:20px; }
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

    <section class="benefits" aria-label="Package benefits">
      <div class="benefit">
        <span class="benefit-icon">+</span>
        <div><div class="benefit-title">Best Value</div><div class="benefit-text">Save more with every combo</div></div>
      </div>
      <div class="benefit">
        <span class="benefit-icon">!</span>
        <div><div class="benefit-title">Fast & Easy</div><div class="benefit-text">Quick checkout & instant order</div></div>
      </div>
      <div class="benefit">
        <span class="benefit-icon">&#10003;</span>
        <div><div class="benefit-title">Secure</div><div class="benefit-text">100% safe payments</div></div>
      </div>
    </section>

    <p class="swipe-hint">Swipe packages</p>
    <section class="package-track" aria-label="Swipeable package list">
      <article class="package-card crunch" role="button" tabindex="0" data-package-key="crunch" data-package-title="Crunch Package" data-package-price="250" data-package-rate="0.5" data-package-days="150" data-package-image="{{ asset('images/Crunch-Package.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">01</span>Crunch</h2>
          <p class="package-desc">Crispy satisfaction in every bite.</p>
          <div class="price-row"><span class="price">$250</span></div>
          <div class="package-terms">0.5% daily · 150 days</div>
        </div>
        <div class="product-label">Crunch</div>
      </article>

      <article class="package-card loaded" role="button" tabindex="0" data-package-key="loaded" data-package-title="Loaded Package" data-package-price="900" data-package-rate="0.7" data-package-days="120" data-package-image="{{ asset('images/Loaded-Package.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">02</span>Loaded</h2>
          <p class="package-desc">Loaded fries. Max taste. Zero regrets.</p>
          <div class="price-row"><span class="price">$900</span></div>
          <div class="package-terms">0.7% daily · 120 days</div>
        </div>
        <div class="product-label">Loaded</div>
      </article>

      <article class="package-card supreme" role="button" tabindex="0" data-package-key="supreme" data-package-title="Supreme Package" data-package-price="10000" data-package-rate="0.9" data-package-days="90" data-package-image="{{ asset('images/Supreme-Package.png') }}">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">03</span>Supreme</h2>
          <p class="package-desc">The ultimate combo for true cravings.</p>
          <div class="price-row"><span class="price">$10,000</span></div>
          <div class="package-terms">0.9% daily · 90 days</div>
        </div>
        <div class="product-label">Supreme</div>
      </article>
    </section>

    <div class="dot-row" aria-hidden="true">
      <span class="dot is-active"></span>
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
  <form class="modal-card" role="dialog" aria-modal="true" aria-label="Investment amount" method="post" action="{{ route('investments.store') }}">
    @csrf
    <input type="hidden" name="package" id="amountPackageKey" value="{{ old('package') }}">
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
      <button class="modal-button confirm" type="submit">Confirm</button>
      <button class="modal-button cancel" type="button" id="amountModalCancel">Cancel</button>
    </div>
  </form>
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
    var amountModal = document.getElementById('amountModal');
    var amountPackageKey = document.getElementById('amountPackageKey');
    var amountPackageTitle = document.getElementById('amountPackageTitle');
    var amountPackageCopy = document.getElementById('amountPackageCopy');
    var amountInput = document.getElementById('amountInput');
    var amountModalCancel = document.getElementById('amountModalCancel');
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
    var phpRate = 58;
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
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeModal();
        closeAmountModal();
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
