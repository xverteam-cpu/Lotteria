<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://www.lotteria.vn/grs-static/icons/logo_512.png">
  <link rel="shortcut icon" type="image/png" href="https://www.lotteria.vn/grs-static/icons/logo_512.png">
  <title>Lotteria</title>
  <style>
    body { background:#eef1f4; font-family: Arial, Helvetica, sans-serif; margin:0; }
    .container { max-width:1100px; margin:0 auto; padding:18px; }
    a { color:#d90000; }
    .page-loader {
      position:fixed;
      inset:0;
      z-index:9999;
      display:none;
      align-items:center;
      justify-content:center;
      background:#ffffff;
    }
    .page-loader.is-active { display:flex; }
    .page-loader-circle {
      width:58px;
      height:58px;
      border-radius:50%;
      border:6px solid rgba(227,27,35,0.18);
      border-top-color:#e31b23;
      animation:page-loader-spin .8s linear infinite;
    }
    @keyframes page-loader-spin {
      to { transform:rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="page-loader" aria-hidden="true">
    <div class="page-loader-circle"></div>
  </div>
  <div class="container">
    @yield('content')
  </div>
  <script>
    (function () {
      var loader = document.querySelector('.page-loader');
      if (!loader) return;

      document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        if (link.target && link.target !== '_self') return;

        var href = link.getAttribute('href');
        if (!href || href === '#' || href.charAt(0) === '#') return;

        var nextUrl = new URL(href, window.location.href);
        if (nextUrl.origin !== window.location.origin || nextUrl.href === window.location.href) return;

        event.preventDefault();
        loader.classList.add('is-active');
        setTimeout(function () {
          window.location.href = nextUrl.href;
        }, 120);
      });
    })();
  </script>
</body>
</html>
