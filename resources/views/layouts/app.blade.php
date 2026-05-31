<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Tierra y Taza') · Cafetería Artesanal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body>

@stack('splash')

<!-- LOADER -->
<div class="page-loader"><div class="spin-ring"></div></div>

<!-- NAVBAR -->
<nav class="navbar-tierra" id="mainNavbar">
  <div class="container">
    <div class="nb-inner">

      <!-- Brand: solo logo -->
      <a href="{{ route('home') }}" class="nb-brand">
        <img src="{{ asset('images/logo.jpg') }}" class="nb-logo" alt="Tierra y Taza">
      </a>

      <!-- Nav desktop (centrado) -->
      <div class="nb-nav d-none d-lg-flex">
        <a href="{{ route('home') }}"           class="nav-pill {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('catalogo') }}"       class="nav-pill {{ request()->routeIs('catalogo*') ? 'active' : '' }}">Catálogo</a>
        <a href="{{ route('reservas.index') }}" class="nav-pill {{ request()->routeIs('reservas*') ? 'active' : '' }}">Reservas</a>
        <a href="{{ route('sobre') }}"          class="nav-pill {{ request()->routeIs('sobre') ? 'active' : '' }}">Nosotros</a>
        <a href="{{ route('ubicacion') }}"      class="nav-pill {{ request()->routeIs('ubicacion') ? 'active' : '' }}">Ubicación</a>
      </div>

      <!-- Acciones -->
      <div class="nb-actions">
        <button class="btn-theme" data-theme-toggle>
          <i class="bi bi-moon-fill"></i><span class="btn-theme-label ms-1">Oscuro</span>
        </button>
        <a href="{{ route('carrito') }}" class="btn-cart">
          <i class="bi bi-bag"></i>
          <span class="cart-count" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
        </a>
        @auth
          <div class="dropdown">
            <button class="btn-ghost-tt d-flex align-items-center gap-2"
                    style="padding:0.35rem 0.75rem;font-size:0.85rem" data-bs-toggle="dropdown">
              <img src="{{ auth()->user()->avatar_url }}" alt="avatar"
                   style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:1.5px solid var(--c-gold)">
              <span class="d-none d-sm-inline">{{ mb_substr(auth()->user()->nombre, 0, 12) }}</span>
              <i class="bi bi-chevron-down" style="font-size:0.65rem"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end"
                style="background:var(--c-surface);border:1px solid var(--c-border);border-radius:var(--radius);min-width:200px">
              <li class="px-3 py-2" style="border-bottom:1px solid var(--c-border)">
                <div style="font-weight:700;font-size:0.875rem">{{ auth()->user()->nombre }}</div>
                <div style="font-size:0.75rem;color:var(--c-muted)">{{ auth()->user()->email }}</div>
              </li>
              @if(auth()->user()->esCliente())
                <li>
                  <a class="dropdown-item" href="{{ route('perfil.index') }}" style="color:var(--c-text)">
                    <i class="bi bi-person-circle me-2"></i>Mi Perfil
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('pedidos.mis_pedidos') }}" style="color:var(--c-text)">
                    <i class="bi bi-bag me-2"></i>Mis Pedidos
                  </a>
                </li>
              @endif
              @if(auth()->user()->esAdmin())
                <li>
                  <a class="dropdown-item" href="{{ route('admin.dashboard') }}" style="color:var(--c-text)">
                    <i class="bi bi-speedometer2 me-2"></i>Panel Admin
                  </a>
                </li>
              @endif
              <li><hr class="dropdown-divider" style="border-color:var(--c-border)"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button class="dropdown-item" style="color:var(--c-red)">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}"    class="btn-ghost-tt login-btn"   style="padding:0.45rem 1rem;font-size:0.85rem"><i class="bi bi-person d-sm-none"></i><span class="btn-login-text">Ingresar</span></a>
          <a href="{{ route('register') }}" class="btn-primary-tt btn-register" style="padding:0.45rem 1.2rem;font-size:0.85rem">Registrarse</a>
        @endauth

        <button class="btn-ghost-tt d-lg-none" style="padding:0.45rem 0.75rem"
                data-bs-toggle="collapse" data-bs-target="#mobileNav">
          <i class="bi bi-list fs-5"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile nav -->
  <div class="collapse" id="mobileNav">
    <div class="container py-3 d-flex flex-column gap-2">
      <a href="{{ route('home') }}"           class="nav-pill">Inicio</a>
      <a href="{{ route('catalogo') }}"       class="nav-pill">Catálogo</a>
      <a href="{{ route('reservas.index') }}" class="nav-pill">Reservas</a>
      <a href="{{ route('sobre') }}"          class="nav-pill">Nosotros</a>
      <a href="{{ route('ubicacion') }}"      class="nav-pill">Ubicación</a>
    </div>
  </div>
</nav>

<!-- ALERTAS FLASH -->
@if(session('success') || session('error'))
<div class="container mt-3">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
      <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
</div>
@endif

<main>@yield('content')</main>

<!-- FOOTER -->
<footer class="footer-tt">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <img src="{{ asset('images/logo.jpg') }}"
               style="width:56px;height:56px;object-fit:contain;border-radius:10px;">
          <div>
            <div style="font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--c-gold)">Tierra <em>y</em> Taza</div>
            <div style="font-size:0.65rem;color:var(--c-muted);letter-spacing:0.5px">CAFETERÍA ARTESANAL</div>
          </div>
        </div>
        <p style="color:var(--c-muted);font-size:0.875rem;line-height:1.8;max-width:300px">
          Granos seleccionados de los mejores valles cafetaleros del Perú, preparados con pasión artesanal.
        </p>
        <div class="d-flex gap-2 mt-3">
          @foreach(['instagram','facebook','tiktok','youtube'] as $sn)
            <a href="#" class="social-icon"><i class="bi bi-{{ $sn }}"></i></a>
          @endforeach
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-gold mb-3" style="font-size:0.75rem;letter-spacing:1.5px;text-transform:uppercase;font-weight:700">Menú</h6>
        <a href="{{ route('catalogo') }}"       class="footer-link">Catálogo</a>
        <a href="{{ route('reservas.index') }}" class="footer-link">Reservas</a>
        <a href="{{ route('carrito') }}"        class="footer-link">Carrito</a>
        <a href="{{ route('sobre') }}"          class="footer-link">Nosotros</a>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-gold mb-3" style="font-size:0.75rem;letter-spacing:1.5px;text-transform:uppercase;font-weight:700">Legal</h6>
        <a href="{{ route('terminos') }}"    class="footer-link">Términos y Condiciones</a>
        <a href="{{ route('aviso.legal') }}" class="footer-link">Aviso Legal</a>
        <a href="{{ route('privacidad') }}"  class="footer-link">Privacidad</a>
        <a href="{{ route('cookies') }}"     class="footer-link">Cookies</a>
        <a href="{{ route('ubicacion') }}"   class="footer-link">Ubicación</a>
      </div>
      <div class="col-lg-4">
        <h6 class="text-gold mb-3" style="font-size:0.75rem;letter-spacing:1.5px;text-transform:uppercase;font-weight:700">Contacto</h6>
        <div class="d-flex flex-column gap-2" style="font-size:0.875rem;color:var(--c-muted)">
          <span><i class="bi bi-geo-alt-fill text-gold me-2"></i>Av. Larco 1234, Miraflores, Lima</span>
          <span><i class="bi bi-telephone-fill text-gold me-2"></i>+51 987 654 321</span>
          <span><i class="bi bi-envelope-fill text-gold me-2"></i>hola@tierraytaza.pe</span>
          <span><i class="bi bi-clock-fill text-gold me-2"></i>Lun–Dom 7:00 am – 10:00 pm</span>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© {{ date('Y') }} Tierra y Taza · Cafetería Artesanal Peruana</span>
      <span style="color:var(--c-muted);font-size:0.75rem">
        Hecho con <i class="bi bi-heart-fill" style="color:var(--c-gold)"></i> en Perú
      </span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

<!-- COOKIE CONSENT BANNER -->
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-label="Aviso de cookies">
  <div class="cookie-banner-icon">🍪</div>
  <div class="cookie-banner-text">
    <strong>Tierra y Taza utiliza cookies para mejorar tu experiencia</strong>
    <p>
      Usamos cookies propias y de terceros para ofrecerte la mejor experiencia posible.
      Al continuar navegando aceptas su uso según nuestra
      <a href="{{ route('cookies') }}">Política de Cookies</a> y
      <a href="{{ route('privacidad') }}">Política de Privacidad</a>.
    </p>
  </div>
  <a href="{{ route('cookies') }}" class="cookie-banner-config">
    <i class="bi bi-sliders"></i> Configuración
  </a>
  <div class="cookie-banner-actions">
    <button class="cookie-btn-decline" onclick="cookieConsent('essential')">No, gracias</button>
    <button class="cookie-btn-accept"  onclick="cookieConsent('all')">Sí, acepto</button>
  </div>
</div>
<script>
(function () {
  const KEY = 'tt_cookie_v2';
  const banner = document.getElementById('cookieBanner');

  function bannerHeight() {
    return banner ? banner.offsetHeight : 0;
  }
  function showBanner() {
    if (!banner) return;
    banner.classList.add('show');
    document.body.style.paddingBottom = bannerHeight() + 'px';
  }
  function hideBanner() {
    if (!banner) return;
    banner.classList.remove('show');
    document.body.style.paddingBottom = '';
  }

  if (!localStorage.getItem(KEY)) {
    setTimeout(showBanner, 800);
  }

  window.cookieConsent = function (choice) {
    localStorage.setItem(KEY, choice);
    hideBanner();
  };
})();
</script>
</body>
</html>
