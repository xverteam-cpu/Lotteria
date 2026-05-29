<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="icon" type="image/png" href="https://www.lotteria.vn/grs-static/icons/logo_512.png">
  <title>{{ $mode === 'setup' ? 'Create PIN' : 'PIN Login' }} - Lotteria</title>
  <style>
    * { box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
    body { margin:0; min-height:100vh; font-family:'Helvetica Neue',Helvetica,Arial,-apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif; background:#fff7e9; color:#041438; }
    .pin-page { min-height:100vh; display:flex; align-items:stretch; justify-content:center; background:#fff7e9; }
    .pin-shell {
      position:relative;
      width:100%;
      max-width:430px;
      min-height:100vh;
      overflow:hidden;
      padding:58px 28px 34px;
      background:#fff url("{{ asset('images/Crunch%20(1).png') }}") center bottom / cover no-repeat;
      box-shadow:0 12px 34px rgba(15,23,42,.14);
    }
    .pin-content { position:relative; z-index:1; display:flex; min-height:calc(100vh - 92px); flex-direction:column; align-items:center; }
    .pin-spacer { height:108px; }
    .pin-title { margin:0; color:#071846; font-size:24px; line-height:30px; font-weight:900; text-align:center; }
    .pin-subtitle { margin:8px 0 0; color:#8b91a3; font-size:14px; line-height:20px; font-weight:700; text-align:center; }
    .pin-error { margin:14px 0 0; border-radius:12px; background:#fff5f5; border:1px solid #ffc5cd; padding:10px 12px; color:#d91b0b; font-size:13px; line-height:18px; font-weight:800; text-align:center; }
    .pin-digits { display:flex; justify-content:center; gap:18px; margin:26px 0 22px; }
    .pin-digit { display:flex; align-items:center; justify-content:center; width:32px; height:28px; border-bottom:3px solid #ffba08; color:#071846; font-size:20px; line-height:24px; font-weight:900; transition:transform .15s ease; }
    .pin-digit.is-filled { transform:translateY(-2px); }
    .pin-digit.is-masked { color:#ffba08; font-size:22px; }
    .keypad { display:grid; grid-template-columns:repeat(3, 1fr); gap:14px 24px; width:236px; margin-top:4px; }
    .key {
      display:flex;
      align-items:center;
      justify-content:center;
      width:50px;
      height:50px;
      margin:0 auto;
      border:0;
      border-radius:50%;
      background:#ffb300;
      color:#fff;
      font-size:23px;
      line-height:1;
      font-weight:800;
      cursor:pointer;
      box-shadow:0 8px 18px rgba(255,179,0,.22);
      touch-action:manipulation;
      user-select:none;
      -webkit-user-select:none;
    }
    .key:active { transform:scale(.96); }
    .key.blank { visibility:hidden; }
    .key.delete { background:#fff0d6; color:#ffad00; box-shadow:none; }
    .pin-submit {
      width:236px;
      min-height:46px;
      margin-top:20px;
      border:0;
      border-radius:999px;
      background:#e31b23;
      color:#fff;
      font-size:14px;
      line-height:18px;
      font-weight:900;
      letter-spacing:.08em;
      text-transform:uppercase;
      cursor:pointer;
      box-shadow:0 10px 22px rgba(227,27,35,.24);
      touch-action:manipulation;
    }
    .pin-submit:disabled { opacity:.45; cursor:not-allowed; box-shadow:none; }
    .pin-helper { margin:12px 0 0; color:#8b91a3; font-size:12px; line-height:17px; font-weight:700; text-align:center; }
    @media (min-width:760px) {
      .pin-page { padding:24px; }
      .pin-shell { min-height:760px; border-radius:28px; }
      .pin-content { min-height:668px; }
    }
  </style>
</head>
<body>
  <main class="pin-page">
    <section class="pin-shell">
      <form class="pin-content" method="post" action="{{ $mode === 'setup' ? route('pin.store') : route('pin.verify') }}">
        @csrf
        <div class="pin-spacer" aria-hidden="true"></div>
        <h1 class="pin-title">{{ $mode === 'setup' ? 'Create your PIN' : 'Welcome back!' }}</h1>
        <p class="pin-subtitle">{{ $mode === 'setup' ? 'Enter your 4 digit MPIN' : 'Enter your MPIN to login' }}</p>

        @if ($errors->any())
          <div class="pin-error">{{ $errors->first() }}</div>
        @endif

        <input type="hidden" id="pinInput" name="pin" value="{{ old('pin') }}">

        <div class="pin-digits" aria-label="PIN digits">
          <span class="pin-digit"></span>
          <span class="pin-digit"></span>
          <span class="pin-digit"></span>
          <span class="pin-digit"></span>
        </div>

        <div class="keypad" aria-label="PIN keypad">
          @foreach ([1,2,3,4,5,6,7,8,9] as $number)
            <button class="key" type="button" data-key="{{ $number }}">{{ $number }}</button>
          @endforeach
          <button class="key blank" type="button" tabindex="-1" aria-hidden="true"></button>
          <button class="key" type="button" data-key="0">0</button>
          <button class="key delete" type="button" data-delete aria-label="Delete">&times;</button>
        </div>

        <button class="pin-submit" id="pinSubmit" type="submit" disabled>{{ $mode === 'setup' ? 'Save PIN' : 'Login' }}</button>
        <p class="pin-helper">{{ $mode === 'setup' ? 'You will only create this once.' : 'Use the 4 digit PIN you created.' }}</p>
      </form>
    </section>
  </main>

  <script>
    (function () {
      var input = document.getElementById('pinInput');
      var submit = document.getElementById('pinSubmit');
      var indicators = Array.prototype.slice.call(document.querySelectorAll('.pin-digit'));
      var keys = Array.prototype.slice.call(document.querySelectorAll('[data-key]'));
      var deleteKey = document.querySelector('[data-delete]');
      var revealTimer = null;
      var autoSubmit = {{ $mode === 'login' && ! $errors->any() ? 'true' : 'false' }};
      var hasSubmitted = false;

      function sync() {
        var value = input.value.slice(0, 4);
        var visibleIndex = value.length - 1;
        input.value = value;
        indicators.forEach(function (indicator, index) {
          var isFilled = index < value.length;
          var isVisible = index === visibleIndex;
          indicator.classList.toggle('is-filled', isFilled);
          indicator.classList.toggle('is-masked', isFilled && !isVisible);
          indicator.textContent = isFilled ? (isVisible ? value[index] : '•') : '';
        });
        submit.disabled = value.length !== 4;
        if (autoSubmit && !hasSubmitted && value.length === 4) {
          hasSubmitted = true;
          window.setTimeout(function () {
            input.form.submit();
          }, 180);
        }
      }

      function maskAll() {
        var value = input.value;
        indicators.forEach(function (indicator, index) {
          var isFilled = index < value.length;
          indicator.classList.toggle('is-filled', isFilled);
          indicator.classList.toggle('is-masked', isFilled);
          indicator.textContent = isFilled ? '•' : '';
        });
      }

      function revealLatest() {
        window.clearTimeout(revealTimer);
        sync();
        revealTimer = window.setTimeout(maskAll, 650);
      }

      keys.forEach(function (key) {
        key.addEventListener('click', function () {
          if (input.value.length >= 4) return;
          input.value += key.dataset.key;
          revealLatest();
        });
      });

      if (deleteKey) {
        deleteKey.addEventListener('click', function () {
          input.value = input.value.slice(0, -1);
          revealLatest();
        });
      }

      document.addEventListener('keydown', function (event) {
        if (/^[0-9]$/.test(event.key) && input.value.length < 4) {
          input.value += event.key;
          revealLatest();
          event.preventDefault();
        }
        if (event.key === 'Backspace') {
          input.value = input.value.slice(0, -1);
          revealLatest();
          event.preventDefault();
        }
      });

      sync();
    })();
  </script>
</body>
</html>
