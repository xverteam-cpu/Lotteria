@extends('layouts.app')

@section('content')
<style>
  :root{--lot-red:#D71920;--charcoal:#111827;--bg:#F8FAFC;--gold:#D4AF37;--text:#1F2937;--glass:rgba(255,255,255,0.90)}
  .franchise-hero{background-image:linear-gradient(rgba(17,24,39,0.45),rgba(17,24,39,0.45)),url('{{ asset('Lotteria.png') }}');background-size:cover;background-position:center;color:#fff;padding:72px 20px;border-radius:12px;margin-bottom:28px}
  .hero-inner{max-width:1100px;margin:0 auto;display:flex;gap:28px;align-items:center;justify-content:space-between;flex-wrap:wrap}
  .hero-content{max-width:680px}
  .hero-title{font-size:clamp(28px,4.6vw,48px);margin:0;font-weight:800;letter-spacing:-0.02em;color:#fff}
  .hero-sub{color:rgba(255,255,255,0.92);margin:12px 0 18px;font-size:18px}
  .hero-stats{display:flex;gap:12px;margin:18px 0}
  .stat{background:rgba(255,255,255,0.06);padding:12px 14px;border-radius:10px;text-align:center}
  .stat .num{font-weight:800;font-size:18px;color:var(--gold)}
  .cta{display:inline-flex;gap:12px;align-items:center;background:var(--lot-red);color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;border:0;cursor:pointer;font-weight:700}
  .container{max-width:1100px;margin:0 auto;padding:0 18px}

  /* Packages */
  .packages{margin-top:36px}
  .cards-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;align-items:stretch}
  .package-card{background:var(--glass);border-radius:24px;padding:22px;box-shadow:0 20px 50px rgba(0,0,0,.08);border:1px solid rgba(17,24,39,0.04);display:flex;flex-direction:column;position:relative}
  .package-card .media{height:160px;border-radius:14px;overflow:hidden;margin-bottom:12px;background-size:cover;background-position:center}
  .package-title{font-size:18px;margin:0;color:var(--charcoal);font-weight:800}
  .package-price{font-size:20px;font-weight:900;color:var(--lot-red);margin-top:8px}
  .package-what{color:#374151;margin:10px 0;flex:1}
  .features{list-style:none;padding:0;margin:12px 0;color:#374151}
  .features li{margin:8px 0}
  .btn-row{display:flex;gap:12px;margin-top:12px}
  .btn-select{background:var(--lot-red);color:#fff;padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:700;flex:1}
  .btn-view{background:#fff;color:var(--lot-red);padding:10px 14px;border-radius:10px;border:1px solid rgba(215,25,32,0.12);cursor:pointer;flex:1}

  /* Ribbon */
  .ribbon{position:absolute;left:18px;top:18px;background:var(--gold);color:var(--charcoal);padding:6px 12px;border-radius:999px;font-weight:800;font-size:12px;box-shadow:0 8px 18px rgba(0,0,0,0.08)}

  /* Comparison */
  .compare{width:100%;border-collapse:collapse;margin-top:32px;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.06)}
  .compare th,.compare td{padding:14px 18px;border-bottom:1px solid #eef2f7;text-align:left}
  .compare th{background:#f8fafc;font-weight:800;color:var(--text)}

  /* Timeline */
  .timeline{margin-top:36px;display:flex;flex-direction:column;gap:12px}
  .step{display:flex;gap:12px;align-items:flex-start}
  .step .num{width:44px;height:44px;border-radius:12px;background:var(--lot-red);color:#fff;display:grid;place-items:center;font-weight:900}
  .step .desc{color:#374151}

  /* Stats */
  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:28px}
  .stat-card{background:#fff;padding:18px;border-radius:12px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,0.06)}
  .stat-card .big{font-size:28px;font-weight:900;color:var(--charcoal)}

  @media (max-width:900px){.cards-grid{grid-template-columns:1fr}.stats-grid{grid-template-columns:repeat(2,1fr)}.hero-inner{flex-direction:column;align-items:flex-start}}
</style>

<div class="franchise-hero">
  <div class="hero-inner container">
    <div class="hero-content">
      <div style="color:var(--gold);font-weight:800">LOTTERIA PHILIPPINES</div>
      <h1 class="hero-title">Become Part of a Global Restaurant Brand</h1>
      <p class="hero-sub">1,600+ Stores Worldwide · Millions of Customers Served. Own a LOTTERIA franchise today.</p>
      <div class="hero-stats">
        <div class="stat"><div class="num">1,600+</div><div style="font-size:12px;color:rgba(255,255,255,0.85)">Stores Worldwide</div></div>
        <div class="stat"><div class="num">40+</div><div style="font-size:12px;color:rgba(255,255,255,0.85)">Years Experience</div></div>
        <div class="stat"><div class="num">Millions</div><div style="font-size:12px;color:rgba(255,255,255,0.85)">Served Annually</div></div>
      </div>
      <a href="#packages" class="cta">View Franchise Packages</a>
    </div>
    <div style="min-width:260px;text-align:right;">
      <img src="{{ asset('Lotteria.png') }}" alt="Lotteria" style="max-width:220px;border-radius:12px;box-shadow:0 8px 26px rgba(0,0,0,0.12)">
    </div>
  </div>
</div>

<div class="container" id="packages">
  <h2 style="margin:0 0 12px;color:var(--charcoal)">Franchise Packages</h2>
  <p style="color:#6b7280;margin:0 0 18px">Premium packages designed for different site sizes and operational needs.</p>

  <div class="packages">
    <div class="cards-grid">
      <div class="package-card" data-package="franchise_40pyeong">
        <div class="media" style="background-image:url('{{ asset('Franchise.png') }}')"></div>
        <div class="package-body">
          <div class="package-title">LOTTERIA EXPRESS — 40 PYEONG</div>
          <div class="package-price">₱23.5M</div>
          <div class="package-what">Ideal for shopping centers and business districts. Compact dine-in format with strong ROI potential.</div>
          <ul class="features">
            <li>✓ Brand Rights</li>
            <li>✓ Training & Onboarding</li>
            <li>✓ Operations Support</li>
            <li>✓ Marketing Assistance</li>
          </ul>
          <div class="btn-row">
            <button class="btn-select select-package" data-package="franchise_40pyeong">Select Package</button>
            <button class="btn-view view-package" data-presentation="{{ asset('Lotteria_Philippines_40_Pyeong_Franchise_Cost_Presentation.pptx') }}" data-image="{{ asset('Franchise.png') }}">View Details</button>
          </div>
        </div>
      </div>

      <div class="package-card" data-package="franchise_60pyeong">
        <div class="ribbon">MOST POPULAR</div>
        <div class="media" style="background-image:url('{{ asset('60pyeong.png') }}')"></div>
        <div class="package-body">
          <div class="package-title">LOTTERIA PREMIUM — 60 PYEONG</div>
          <div class="package-price">₱45M</div>
          <div class="package-what">Premium full-service branch with extended seating and delivery-ready operations.</div>
          <ul class="features">
            <li>✓ Brand Rights</li>
            <li>✓ Comprehensive Training</li>
            <li>✓ Full Operations Support</li>
            <li>✓ National Marketing</li>
            <li>✓ Site & Construction Assistance</li>
          </ul>
          <div class="btn-row">
            <button class="btn-select select-package" data-package="franchise_60pyeong">Select Package</button>
            <button class="btn-view view-package" data-image="{{ asset('60pyeong.png') }}">View Details</button>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" id="selected_package_key" value="">
  </div>

  <!-- Comparison table -->
  <h3 style="margin-top:28px;color:var(--charcoal)">Quick Comparison</h3>
  <table class="compare" aria-label="Package comparison">
    <thead>
      <tr><th>Feature</th><th>40 Pyeong</th><th>60 Pyeong</th></tr>
    </thead>
    <tbody>
      <tr><td>Investment</td><td>₱23.5M</td><td>₱45M</td></tr>
      <tr><td>Dining Capacity</td><td>✓</td><td>✓</td></tr>
      <tr><td>Drive-Thru</td><td>Optional</td><td>Included</td></tr>
      <tr><td>Training</td><td>✓</td><td>✓</td></tr>
      <tr><td>Marketing</td><td>✓</td><td>✓</td></tr>
      <tr><td>Site Assistance</td><td>✓</td><td>✓</td></tr>
    </tbody>
  </table>

  <!-- Timeline -->
  <h3 style="margin-top:28px;color:var(--charcoal)">Franchise Journey</h3>
  <div class="timeline" role="list">
    <div class="step"><div class="num">1</div><div class="desc">Submit Inquiry</div></div>
    <div class="step"><div class="num">2</div><div class="desc">Attend Presentation</div></div>
    <div class="step"><div class="num">3</div><div class="desc">Financial Evaluation</div></div>
    <div class="step"><div class="num">4</div><div class="desc">Sign Franchise Agreement</div></div>
    <div class="step"><div class="num">5</div><div class="desc">Store Construction</div></div>
    <div class="step"><div class="num">6</div><div class="desc">Grand Opening</div></div>
  </div>

  <!-- Why LOTTERIA -->
  <h3 style="margin-top:28px;color:var(--charcoal)">Why LOTTERIA</h3>
  <div class="stats-grid">
    <div class="stat-card"><div class="big">1,600+</div><div>Stores Worldwide</div></div>
    <div class="stat-card"><div class="big">40+</div><div>Years Experience</div></div>
    <div class="stat-card"><div class="big">Millions</div><div>Served Every Year</div></div>
    <div class="stat-card"><div class="big">Global</div><div>K-Food Brand</div></div>
  </div>

</div>

<!-- Modal -->
<div id="presentationModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:20px;">
  <div style="position:relative;width:100%;height:100%;max-width:1120px;max-height:92vh;background:#fff;border-radius:12px;overflow:auto;">
    <a id="presentationDownload" href="#" download style="position:absolute;left:12px;top:12px;z-index:3;background:#fff;border:0;border-radius:8px;padding:8px 10px;cursor:pointer;box-shadow:0 4px 14px rgba(0,0,0,0.12);color:var(--lot-red);font-weight:800;display:none;text-decoration:none;">Download</a>
    <button id="presentationClose" aria-label="Close" style="position:absolute;right:12px;top:12px;z-index:3;background:#fff;border:0;border-radius:8px;padding:8px 10px;cursor:pointer;box-shadow:0 4px 14px rgba(0,0,0,0.12);">✕</button>
    <img id="presentationImage" style="width:100%;height:auto;display:block;" src="" alt="Presentation" />
    <iframe id="presentationFrame" style="width:100%;height:100%;display:none;border:0;"></iframe>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var selectButtons = document.querySelectorAll('.select-package');
  var packageCards = document.querySelectorAll('.package-card');
  var selectedInput = document.getElementById('selected_package_key');
  var viewButtons = document.querySelectorAll('.view-package');
  var modal = document.getElementById('presentationModal');
  var modalClose = document.getElementById('presentationClose');
  var presentationImage = document.getElementById('presentationImage');

  selectButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var pkg = btn.dataset.package;
      selectedInput.value = pkg;
      packageCards.forEach(function (c) { c.classList.remove('selected'); });
      var card = btn.closest('.package-card');
      if (card) card.classList.add('selected');
      var original = btn.textContent;
      btn.textContent = 'Selected';
      setTimeout(function () { btn.textContent = original; }, 1200);
    });
  });

  var downloadLink = document.getElementById('presentationDownload');

  viewButtons.forEach(function (a) {
    a.addEventListener('click', function (e) {
      e.preventDefault();
      var pres = a.dataset.presentation;
      var img = a.dataset.image;
      var frame = document.getElementById('presentationFrame');
      // Set download link (prefer presentation file, else image)
      if (downloadLink) {
        var dl = pres || img || '';
        if (dl) {
          downloadLink.href = dl;
          downloadLink.style.display = 'inline-block';
          downloadLink.setAttribute('download', '');
        } else {
          downloadLink.style.display = 'none';
          downloadLink.removeAttribute('download');
        }
      }
      if (pres) {
        var src = pres;
        // If it's a PowerPoint or PPTX, use Office online embed viewer (requires public URL)
        if (/\.pptx?$/i.test(pres)) {
          src = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(window.location.origin + pres);
        }
        // If it's a PDF, try Google Docs Viewer
        if (/\.pdf$/i.test(pres)) {
          src = 'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + pres) + '&embedded=true';
        }
        frame.src = src;
        frame.style.display = 'block';
        presentationImage.style.display = 'none';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        return;
      }
      if (img) {
        presentationImage.src = img;
        presentationImage.style.display = 'block';
        frame.style.display = 'none';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    });
  });

  function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
    presentationImage.src = '';
    var frame = document.getElementById('presentationFrame');
    if (frame) { frame.src = ''; frame.style.display = 'none'; }
    if (downloadLink) { downloadLink.style.display = 'none'; downloadLink.removeAttribute('download'); }
  }
  if (modalClose) modalClose.addEventListener('click', closeModal);
  if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

});
</script>

@endsection
