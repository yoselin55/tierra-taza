<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Panel') · Tierra y Taza Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2">
  @stack('styles')
</head>
<body>
<div class="adm-layout">

  <!-- SIDEBAR -->
  <aside class="adm-sidebar" id="admSidebar">
    <div class="adm-sidebar-head">
      <div class="adm-brand-logo">
        <img src="{{ asset('images/logo.jpg') }}" alt="Tierra y Taza" class="adm-logo-img">
        <div>
          <div class="adm-brand">Tierra &amp; Taza</div>
          <div class="adm-subbrand">Panel Admin</div>
        </div>
      </div>
      <div class="adm-role-chip">
        <i class="bi bi-circle-fill" style="font-size:6px"></i>
        {{ auth()->user()->rol_label }}
      </div>
    </div>

    <nav class="adm-nav">
      <div class="adm-nav-section">Principal</div>
      <a href="{{ route('admin.dashboard') }}"
         class="adm-link {{ request()->routeIs('admin.dashboard') ? 'activo' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

      @php $rol = auth()->user()->rol; @endphp

      {{-- BARISTA: solo cocina --}}
      @if($rol === 'barista')
        <div class="adm-nav-section">Cocina</div>
        <a href="{{ route('admin.pedidos.index') }}"
           class="adm-link {{ request()->routeIs('admin.pedidos*') ? 'activo' : '' }}">
          <i class="bi bi-fire"></i> Pedidos a Preparar
        </a>

      {{-- COORDINADOR DELIVERY: solo entregas --}}
      @elseif($rol === 'coordinador_delivery')
        <div class="adm-nav-section">Delivery</div>
        <a href="{{ route('admin.pedidos.index') }}"
           class="adm-link {{ request()->routeIs('admin.pedidos*') ? 'activo' : '' }}">
          <i class="bi bi-truck"></i> Entregas Pendientes
        </a>

      {{-- CAJERO: solo sección de caja --}}
      @elseif($rol === 'cajero')
        <div class="adm-nav-section">Caja</div>
        <a href="{{ route('admin.pagos.index') }}"
           class="adm-link {{ request()->routeIs('admin.pagos*') ? 'activo' : '' }}">
          <i class="bi bi-wallet2"></i> Validar Pagos
        </a>
        <a href="{{ route('admin.incidencias.index') }}"
           class="adm-link {{ request()->routeIs('admin.incidencias*') ? 'activo' : '' }}">
          <i class="bi bi-exclamation-triangle"></i> Reclamos
        </a>
        <a href="{{ route('admin.reportes.index') }}"
           class="adm-link {{ request()->routeIs('admin.reportes*') ? 'activo' : '' }}">
          <i class="bi bi-printer"></i> Reportes e Impresión
        </a>

      {{-- ADMIN SISTEMAS: catálogo y sistema --}}
      @elseif($rol === 'admin_sistema')
        <div class="adm-nav-section">Catálogo</div>
        <a href="{{ route('admin.productos.index') }}"
           class="adm-link {{ request()->routeIs('admin.productos*') ? 'activo' : '' }}">
          <i class="bi bi-box-seam"></i> Productos
        </a>
        <div class="adm-nav-section">Sistema</div>
        <a href="{{ route('admin.inventario') }}"
           class="adm-link {{ request()->routeIs('admin.inventario*') ? 'activo' : '' }}">
          <i class="bi bi-archive"></i> Inventario
        </a>
        <a href="{{ route('admin.usuarios') }}"
           class="adm-link {{ request()->routeIs('admin.usuarios*') ? 'activo' : '' }}">
          <i class="bi bi-people"></i> Usuarios
        </a>

      {{-- ADMIN GENERAL: todo --}}
      @elseif($rol === 'admin_general')
        <div class="adm-nav-section">Catálogo</div>
        <a href="{{ route('admin.productos.index') }}"
           class="adm-link {{ request()->routeIs('admin.productos*') ? 'activo' : '' }}">
          <i class="bi bi-box-seam"></i> Productos
        </a>
        <div class="adm-nav-section">Operaciones</div>
        <a href="{{ route('admin.pedidos.index') }}"
           class="adm-link {{ request()->routeIs('admin.pedidos*') ? 'activo' : '' }}">
          <i class="bi bi-bag-check"></i> Pedidos
        </a>
        <a href="{{ route('admin.reservas.index') }}"
           class="adm-link {{ request()->routeIs('admin.reservas*') ? 'activo' : '' }}">
          <i class="bi bi-calendar3"></i> Reservas
        </a>
        <a href="{{ route('admin.recursos.index') }}"
           class="adm-link {{ request()->routeIs('admin.recursos*') ? 'activo' : '' }}">
          <i class="bi bi-grid-3x3-gap"></i> Mesas &amp; Coworking
        </a>
        <div class="adm-nav-section">Caja</div>
        <a href="{{ route('admin.pagos.index') }}"
           class="adm-link {{ request()->routeIs('admin.pagos*') ? 'activo' : '' }}">
          <i class="bi bi-wallet2"></i> Validar Pagos
        </a>
        <a href="{{ route('admin.incidencias.index') }}"
           class="adm-link {{ request()->routeIs('admin.incidencias*') ? 'activo' : '' }}">
          <i class="bi bi-exclamation-triangle"></i> Reclamos
        </a>
        <a href="{{ route('admin.reportes.index') }}"
           class="adm-link {{ request()->routeIs('admin.reportes*') ? 'activo' : '' }}">
          <i class="bi bi-printer"></i> Reportes e Impresión
        </a>
        <div class="adm-nav-section">Sistema</div>
        <a href="{{ route('admin.inventario') }}"
           class="adm-link {{ request()->routeIs('admin.inventario*') ? 'activo' : '' }}">
          <i class="bi bi-archive"></i> Inventario
        </a>
        <a href="{{ route('admin.usuarios') }}"
           class="adm-link {{ request()->routeIs('admin.usuarios*') ? 'activo' : '' }}">
          <i class="bi bi-people"></i> Usuarios
        </a>
      @endif

      <div style="padding:1rem 1.5rem;margin-top:auto">
        <div class="divider-gold"></div>
        <button class="btn-theme w-100 justify-content-center mt-2" data-theme-toggle>
          <i class="bi bi-moon-fill"></i> Modo Oscuro
        </button>
        <a href="{{ route('home') }}" class="adm-link mt-2" style="padding-left:0">
          <i class="bi bi-house"></i> Ver Tienda
        </a>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="adm-link border-0 w-100 text-start"
                  style="background:none;cursor:pointer;padding-left:0;color:var(--c-red)">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
          </button>
        </form>
      </div>
    </nav>
  </aside>

  <!-- MAIN -->
  <div class="adm-main">
    <!-- Topbar -->
    <div class="adm-topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn-ghost-tt d-lg-none" style="padding:0.4rem 0.75rem" data-sidebar-toggle>
          <i class="bi bi-list fs-5"></i>
        </button>
        <div>
          <div style="font-weight:700;font-size:1.1rem">@yield('page-title','Panel')</div>
          <div style="font-size:0.75rem;color:var(--c-muted)">@yield('page-sub','')</div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">

        {{-- Notification Bell --}}
        <div class="notif-wrap" id="notifWrap">
          <button class="notif-btn" id="notifBtn" onclick="toggleNotifPanel()" title="Notificaciones">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="notif-badge" id="notifBadge" style="display:none">0</span>
          </button>
          <div class="notif-panel" id="notifPanel">
            <div class="notif-panel-header">
              <span class="notif-panel-title">Notificaciones</span>
              <button class="notif-clear-btn" onclick="clearNotifPanel()" title="Marcar todo como leído">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
              </button>
            </div>
            <div class="notif-panel-body" id="notifBody">
              <div class="notif-empty-state">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span>Sin notificaciones nuevas</span>
              </div>
            </div>
          </div>
        </div>

        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="adm-topbar-logo d-none d-sm-block">
        <span class="badge-tt badge-warning">{{ auth()->user()->rol_label }}</span>
      </div>
    </div>

    <!-- Alertas -->
    @if(session('success') || session('error'))
      <div class="px-4 pt-3">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
      </div>
    @endif

    <div class="adm-body">
      @yield('content')
    </div>
  </div>
</div>

<!-- Sidebar overlay (mobile) -->
<div class="adm-sidebar-overlay" id="sidebarOverlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}?v=2"></script>

<script>
/* ── NOTIFICATION POLLING ──────────────────────────────────── */
(function () {
  const POLL_MS  = 20000;
  const LS_SINCE = 'tt_notif_since_{{ auth()->id() }}';
  const LS_LIST  = 'tt_notif_list_{{ auth()->id() }}';
  const iconMap  = { pedido:'fire', reserva:'calendar3', pago:'wallet2', truck:'truck' };
  let panelOpen  = false;
  let notifList  = [];

  try { notifList = JSON.parse(localStorage.getItem(LS_LIST) || '[]'); } catch(e) {}

  function getSince() {
    return localStorage.getItem(LS_SINCE) || new Date(Date.now() - 60000).toISOString();
  }

  function updateBadge() {
    const n   = notifList.length;
    const bdg = document.getElementById('notifBadge');
    const btn = document.getElementById('notifBtn');
    if (!bdg || !btn) return;
    if (n > 0) {
      bdg.textContent  = n > 9 ? '9+' : n;
      bdg.style.display = '';
      btn.classList.add('has-notifs');
    } else {
      bdg.style.display = 'none';
      btn.classList.remove('has-notifs');
    }
    renderPanel();
  }

  function renderPanel() {
    const body = document.getElementById('notifBody');
    if (!body) return;
    if (notifList.length === 0) {
      body.innerHTML = `<div class="notif-empty-state">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span>Sin notificaciones nuevas</span>
      </div>`;
      return;
    }
    body.innerHTML = notifList.map(n => `
      <div class="notif-item notif-${n.type}">
        <div class="notif-item-icon"><i class="bi bi-${n.icon}"></i></div>
        <div class="notif-item-body">
          <div class="notif-item-msg">${n.msg}</div>
          <div class="notif-item-time">${n.time}</div>
        </div>
      </div>`).join('');
  }

  async function poll() {
    const since    = getSince();
    const newSince = new Date().toISOString();
    try {
      const resp = await fetch('/admin/notifs/poll?since=' + encodeURIComponent(since), {
        headers: { 'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
      });
      if (!resp.ok) return;
      const data = await resp.json();
      localStorage.setItem(LS_SINCE, newSince);

      if (data.items && data.items.length > 0) {
        notifList = [...data.items, ...notifList].slice(0, 25);
        try { localStorage.setItem(LS_LIST, JSON.stringify(notifList)); } catch(e) {}
        updateBadge();

        data.items.forEach(item => {
          window.toast?.(item.msg, 'info');
        });

        if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
          data.items.forEach(item => {
            try {
              new Notification('Tierra y Taza · ' + item.msg, {
                body: item.time, icon: '/images/logo.jpg', silent: false
              });
            } catch(e) {}
          });
        }
      }
    } catch (e) {}
  }

  window.toggleNotifPanel = function () {
    panelOpen = !panelOpen;
    document.getElementById('notifPanel')?.classList.toggle('open', panelOpen);
  };

  window.clearNotifPanel = function () {
    notifList = [];
    try { localStorage.removeItem(LS_LIST); } catch(e) {}
    updateBadge();
    panelOpen = false;
    document.getElementById('notifPanel')?.classList.remove('open');
  };

  document.addEventListener('click', function (e) {
    const wrap = document.getElementById('notifWrap');
    if (panelOpen && wrap && !wrap.contains(e.target)) {
      panelOpen = false;
      document.getElementById('notifPanel')?.classList.remove('open');
    }
  });

  updateBadge();

  if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
    Notification.requestPermission();
  }

  poll();
  setInterval(poll, POLL_MS);
})();
</script>

<script>
// Mobile sidebar toggle
(function() {
  const sidebar  = document.getElementById('admSidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const toggles  = document.querySelectorAll('[data-sidebar-toggle]');
  const close    = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); };
  toggles.forEach(t => t.addEventListener('click', () => {
    const isOpen = sidebar.classList.toggle('open');
    overlay.classList.toggle('open', isOpen);
  }));
  overlay.addEventListener('click', close);
})();
</script>
@stack('scripts')
</body>
</html>