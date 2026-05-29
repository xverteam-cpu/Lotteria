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
  .package-card { position:relative; flex:0 0 88%; min-height:286px; scroll-snap-align:center; border-radius:28px; background:#fff; border:1px solid rgba(224,30,10,.08); box-shadow:0 14px 28px rgba(45,24,10,.14); overflow:hidden; }
  .package-card::after { content:''; position:absolute; right:-40px; top:108px; width:62%; height:96px; background:linear-gradient(90deg, #e12a10, #d61505); box-shadow:0 8px 20px rgba(165,24,9,.22); z-index:1; }
  .package-content { position:relative; z-index:2; padding:30px 24px 26px; max-width:58%; }
  .package-name { margin:0; color:#e12610; font-size:32px; line-height:36px; font-weight:900; font-style:italic; letter-spacing:.04em; text-transform:uppercase; }
  .package-number { color:#f5a400; margin-right:8px; }
  .package-desc { margin:16px 0 0; color:#232323; font-size:16px; line-height:21px; font-weight:500; }
  .price-row { display:flex; align-items:center; gap:10px; margin-top:18px; }
  .price { display:inline-flex; align-items:center; min-height:48px; padding:0 16px; border-radius:12px; background:linear-gradient(180deg, #ef3518, #d91705); color:#fff; font-size:31px; line-height:34px; font-weight:900; }
  .save { display:inline-flex; align-items:center; min-height:48px; padding:0 15px; border-radius:12px; background:#f8f2ef; color:#d91b0b; font-size:18px; line-height:22px; font-weight:900; white-space:nowrap; }
  .product-label { position:absolute; z-index:2; right:22px; top:134px; color:#fff; font-size:34px; line-height:36px; font-weight:900; font-style:italic; letter-spacing:.07em; text-transform:uppercase; }
  .product-img { position:absolute; z-index:3; right:25%; top:76px; max-width:160px; max-height:210px; object-fit:contain; filter:drop-shadow(0 10px 10px rgba(0,0,0,.18)); pointer-events:none; }
  .package-card.loaded .product-img { top:70px; right:29%; max-width:178px; max-height:222px; }
  .package-card.supreme .product-img { top:88px; right:24%; max-width:210px; max-height:190px; }
  .payment-card { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:8px auto 0; padding:17px 18px; max-width:790px; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(30,20,10,.1); }
  .payment-copy { color:#252525; font-size:13px; line-height:17px; font-weight:600; }
  .payment-logos { display:flex; align-items:center; gap:10px; color:#174a9f; font-size:16px; line-height:18px; font-weight:900; white-space:nowrap; }
  .dot-row { display:flex; justify-content:center; gap:8px; margin:2px 0 16px; }
  .dot { width:7px; height:7px; border-radius:50%; background:#ffd2c9; }
  .dot.is-active { width:22px; border-radius:999px; background:#e12610; }
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
    .product-img { right:19%; }
    .package-card.loaded .product-img { right:23%; }
    .package-card.supreme .product-img { right:18%; }
    .price { font-size:27px; padding-inline:13px; }
    .save { font-size:16px; padding-inline:12px; }
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
      <article class="package-card crunch">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">01</span>Crunch</h2>
          <p class="package-desc">Crispy satisfaction in every bite.</p>
          <div class="price-row"><span class="price">&#8377;199</span><span class="save">Save &#8377;50</span></div>
        </div>
        <img class="product-img" src="{{ asset('images/package-drink.png') }}" alt="Crunch drink">
        <div class="product-label">Crunch</div>
      </article>

      <article class="package-card loaded">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">02</span>Loaded</h2>
          <p class="package-desc">Loaded fries. Max taste. Zero regrets.</p>
          <div class="price-row"><span class="price">&#8377;249</span><span class="save">Save &#8377;70</span></div>
        </div>
        <img class="product-img" src="{{ asset('images/package-fries.png') }}" alt="Loaded fries">
        <div class="product-label">Loaded</div>
      </article>

      <article class="package-card supreme">
        <div class="package-content">
          <h2 class="package-name"><span class="package-number">03</span>Supreme</h2>
          <p class="package-desc">The ultimate combo for true cravings.</p>
          <div class="price-row"><span class="price">&#8377;299</span><span class="save">Save &#8377;100</span></div>
        </div>
        <img class="product-img" src="{{ asset('images/package-burger.png') }}" alt="Supreme burger">
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

<script>
  (function () {
    var track = document.querySelector('.package-track');
    var dots = Array.prototype.slice.call(document.querySelectorAll('.dot'));
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
  })();
</script>
@endsection
