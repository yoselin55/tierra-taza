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
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2">
  @stack('styles')
</head>
<body>

@stack('splash')

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
          @if(auth()->user()->esCliente())
          <!-- Campana de notificaciones del cliente -->
          <div style="position:relative" id="userNotifWrap">
            <button id="userNotifBtn"
                    style="position:relative;background:transparent;border:none;padding:0.45rem 0.6rem;border-radius:var(--radius);color:var(--c-text);cursor:pointer;transition:background 0.2s"
                    onmouseenter="this.style.background='var(--c-border)'" onmouseleave="this.style.background='transparent'"
                    onclick="toggleUserNotif()">
              <i class="bi bi-bell-fill" style="font-size:1.1rem"></i>
              <span id="userNotifBadge"
                    style="display:none;position:absolute;top:2px;right:2px;min-width:16px;height:16px;background:var(--c-gold);color:#000;border-radius:50px;font-size:0.6rem;font-weight:800;line-height:16px;text-align:center;padding:0 3px">0</span>
            </button>
            <div id="userNotifPanel"
                 style="display:none;position:absolute;right:0;top:calc(100% + 8px);width:300px;background:var(--c-surface);border:1px solid var(--c-border);border-radius:var(--radius);box-shadow:0 8px 32px rgba(0,0,0,0.4);z-index:9999">
              <div style="padding:0.75rem 1rem;border-bottom:1px solid var(--c-border);display:flex;justify-content:space-between;align-items:center">
                <span style="font-weight:700;font-size:0.875rem"><i class="bi bi-bell-fill me-2" style="color:var(--c-gold)"></i>Mis Pedidos</span>
                <a href="{{ route('pedidos.mis_pedidos') }}" style="font-size:0.75rem;color:var(--c-gold)">Ver todos</a>
              </div>
              <div id="userNotifBody" style="max-height:260px;overflow-y:auto;padding:0.5rem 0">
                <div style="padding:1rem;text-align:center;color:var(--c-muted);font-size:0.8rem">Sin pedidos activos</div>
              </div>
            </div>
          </div>
          @endif

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
          <a href="{{ route('login') }}"    class="btn-ghost-tt login-btn"   style="padding:0.45rem 1rem;font-size:0.85rem"><i class="bi bi-person d-sm-none"></i><span class="btn-login-text">Iniciar Sesión</span></a>
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
<script src="{{ asset('js/app.js') }}?v=2"></script>
@stack('scripts')

<!-- COOKIE CONSENT BANNER -->
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-label="Aviso de cookies">
  <div class="cookie-banner-icon"><i class="bi bi-cookie"></i></div>
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

@auth
@if(auth()->user()->esCliente())
<script>
(function () {
  var btn    = document.getElementById('userNotifBtn');
  var panel  = document.getElementById('userNotifPanel');
  var badge  = document.getElementById('userNotifBadge');
  var body   = document.getElementById('userNotifBody');
  if (!btn) return;

  var notifList = [];

  var ESTADO_LABELS = {
    pendiente:      'Pendiente pago',
    en_preparacion: 'En preparación',
    casi_listo:     'Casi listo',
    listo:          'Listo para despacho',
    recogido:       'Recogido por delivery',
    en_camino:      'En camino',
    cerca_destino:  'Cerca al destino',
    entregado:      'Entregado',
    cancelado:      'Cancelado',
  };

  var ESTADO_COLOR = {
    pendiente:      '#f59e0b',
    en_preparacion: '#60a5fa',
    casi_listo:     '#a78bfa',
    listo:          '#22c55e',
    recogido:       '#60a5fa',
    en_camino:      '#60a5fa',
    cerca_destino:  '#f59e0b',
    entregado:      '#22c55e',
    cancelado:      '#ef4444',
  };

  // IDs vistos por el usuario en esta sesión
  var vistosIds = JSON.parse(sessionStorage.getItem('tt_notif_vistos') || '[]');

  function saveVistos() {
    sessionStorage.setItem('tt_notif_vistos', JSON.stringify(vistosIds));
  }

  function dismissNotif(id) {
    vistosIds.push(id);
    saveVistos();
    notifList = notifList.filter(function (n) { return n.id !== id; });
    renderPanel();
  }

  function renderPanel() {
    // Filtrar los que el usuario ya vio/descartó
    var visibles = notifList.filter(function (p) { return vistosIds.indexOf(p.id) === -1; });

    if (visibles.length === 0) {
      body.innerHTML = '<div style="padding:1.25rem;text-align:center;color:var(--c-muted);font-size:0.8rem">Sin pedidos activos</div>';
      badge.style.display = 'none';
      return;
    }
    badge.style.display = 'inline-block';
    badge.textContent = visibles.length;
    body.innerHTML = visibles.map(function (p) {
      var color = ESTADO_COLOR[p.estado] || 'var(--c-gold)';
      return '<div style="padding:0.65rem 1rem;border-bottom:1px solid var(--c-border);display:flex;justify-content:space-between;align-items:center;gap:0.5rem">'
        + '<div style="flex:1;min-width:0">'
          + '<div style="font-size:0.8rem;font-weight:600">Pedido #' + p.id + '</div>'
          + '<div style="font-size:0.72rem;color:' + color + ';margin-top:2px"><i class="bi bi-circle-fill" style="font-size:0.45rem;vertical-align:middle;margin-right:4px"></i>' + (p.label || ESTADO_LABELS[p.estado] || p.estado) + '</div>'
        + '</div>'
        + '<div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0">'
          + '<a href="{{ route("pedidos.mis_pedidos") }}" style="font-size:0.7rem;color:var(--c-gold);white-space:nowrap">Ver</a>'
          + '<button onclick="dismissNotif(' + p.id + ')" style="background:none;border:none;color:var(--c-muted);cursor:pointer;font-size:0.85rem;padding:0;line-height:1" title="Descartar"><i class="bi bi-x"></i></button>'
        + '</div>'
      + '</div>';
    }).join('');
  }

  async function poll() {
    try {
      var resp = await fetch('{{ route("pedidos.estados_poll") }}');
      if (!resp.ok) return;
      var pedidos = await resp.json();

      pedidos.forEach(function (p) {
        var existing = notifList.find(function (n) { return n.id === p.id; });
        if (existing) {
          if (existing.estado !== p.estado) {
            // El estado cambió — quitar de vistos para que vuelva a aparecer
            vistosIds = vistosIds.filter(function (id) { return id !== p.id; });
            saveVistos();
            existing.estado = p.estado;
            existing.label  = p.label;
            window.toast && window.toast('Pedido #' + p.id + ': ' + p.label, 'info');
          }
        } else {
          notifList.push({ id: p.id, estado: p.estado, label: p.label });
        }
      });

      // Quitar pedidos entregados/cancelados
      var idsActivos = pedidos.map(function (p) { return p.id; });
      notifList = notifList.filter(function (n) { return idsActivos.indexOf(n.id) !== -1; });
      // Limpiar vistos de pedidos que ya no existen
      vistosIds = vistosIds.filter(function (id) { return idsActivos.indexOf(id) !== -1; });
      saveVistos();

      renderPanel();
    } catch (e) {}
  }

  window.toggleUserNotif = function () {
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
  };

  window.dismissNotif = dismissNotif;

  document.addEventListener('click', function (e) {
    var wrap = document.getElementById('userNotifWrap');
    if (wrap && !wrap.contains(e.target)) panel.style.display = 'none';
  });

  poll();
  setInterval(poll, 8000);
})();
</script>
@endif
@endauth
</body>
</html>
