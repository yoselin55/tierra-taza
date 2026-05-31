<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso Admin · Tierra y Taza</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="select-rol-page">
  <div class="select-rol-bg"></div>

  <div class="container position-relative" style="z-index:2">
    @if(session('error'))
      <div class="alert alert-warning text-center rounded-3 mb-4 reveal" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);color:#fbbf24">
        <i class="bi bi-clock-history me-2"></i>{{ session('error') }}
      </div>
    @endif
    <div class="text-center mb-5 reveal">
      <a href="{{ route('home') }}" style="text-decoration:none" class="d-inline-flex flex-column align-items-center gap-2 mb-3">
        <img src="{{ asset('images/logo.jpg') }}"
             style="width:76px;height:76px;object-fit:contain;border-radius:16px;background:#fff;padding:4px;border:2px solid rgba(200,150,60,0.4);box-shadow:0 0 30px rgba(200,150,60,0.15);transition:transform 0.3s"
             onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
        <div style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--c-gold);letter-spacing:-0.3px">
          Tierra <em>y</em> Taza
        </div>
      </a>
      <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;color:var(--c-text)">Acceso Administrativo</h1>
      <p style="color:var(--c-muted)">Selecciona tu tipo de acceso para continuar</p>
    </div>

    <div class="row g-4 justify-content-center">
      @foreach([
        ['barista',              'bi bi-fire',           'Barista / Cocinero',  'Preparar pedidos y actualizar estados de cocina',    '#3b82f6'],
        ['cajero',               'bi bi-receipt',        'Cajero',              'Registrar cobros, emitir comprobantes y reportes',   '#22c55e'],
        ['coordinador_delivery', 'bi bi-truck',          'Coord. Delivery',     'Asignar repartidores y coordinar entregas',          '#f59e0b'],
        ['admin_sistema',        'bi bi-hdd-stack-fill', 'Admin del Sistema',   'Gestionar catálogo, inventario y plataforma',        '#a855f7'],
        ['admin_general',        'bi bi-shield-fill',    'Admin General',       'Control total del sistema y todos los módulos',      '#ef4444'],
      ] as [$rol,$ico,$titulo,$desc,$color])
        <div class="col-6 col-lg-4 reveal">
          <a href="{{ route('admin.login',['rol'=>$rol]) }}"
             class="rol-card" style="--rol-accent:{{ $color }}">
            <div class="rol-ico" style="border-color:{{ $color }}22;color:{{ $color }}">
              <i class="{{ $ico }}" style="font-size:1.8rem"></i>
            </div>
            <h5>{{ $titulo }}</h5>
            <p>{{ $desc }}</p>
            <div style="color:{{ $color }};font-size:0.8rem;font-weight:600;margin-top:0.75rem">
              Ingresar <i class="bi bi-arrow-right ms-1"></i>
            </div>
          </a>
        </div>
      @endforeach
    </div>

    <div class="text-center mt-5 reveal">
      <a href="{{ route('home') }}" class="btn-ghost-tt">
        <i class="bi bi-house"></i> Volver a la tienda
      </a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
