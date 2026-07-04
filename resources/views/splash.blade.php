<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#e31b23">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Lotteria">
  <link rel="icon" type="image/png" href="{{ asset('lotterialogo.png') }}" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('lotterialogo.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('lotterialogo.png') }}" />
  <link rel="manifest" href="{{ asset('manifest.json') }}" />
  <title>Lotteria - Loading</title>
  <style>
    :root { --red:#e31b23; }
    html,
    body {
      height:100%;
      margin:0;
    }
    body {
      background:#eef1f4;
      font-family:'Helvetica Neue',Helvetica,Arial,-apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;
    }
    .splash {
      position:relative;
      width:100%;
      height:100%;
      display:flex;
      align-items:center;
      justify-content:center;
      background-image:url('/Lotteria.png');
      background-size:cover;
      background-position:center top;
      transition:opacity .45s ease;
    }
    .loader {
      width:64px;
      height:64px;
      border-radius:50%;
      border:6px solid rgba(227,27,35,0.2);
      border-top-color:var(--red);
      animation:spin .8s linear infinite;
    }
    @keyframes spin {
      to { transform:rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="splash">
    <div class="loader" aria-hidden="true"></div>
  </div>

  <script>
    (function () {
      var target = '{{ url('/home') }}';

      setTimeout(function () {
        var splash = document.querySelector('.splash');
        if (!splash) {
          window.location.href = target;
          return;
        }

        splash.style.opacity = '0';
        setTimeout(function () {
          window.location.href = target;
        }, 480);
      }, 5000);
    })();
  </script>
</body>
</html>
