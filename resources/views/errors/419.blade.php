<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sesión Expirada · Tierra y Taza</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { min-height:100vh; display:flex; align-items:center; justify-content:center; background:var(--c-bg); }
    .error-box { max-width:420px; width:100%; text-align:center; padding:3rem 2rem; }
  </style>
</head>
<body>
<div class="error-box">
  <div style="font-size:4rem;color:var(--c-gold);margin-bottom:1.5rem">
    <i class="bi bi-clock-history"></i>
  </div>
  <h2 style="font-weight:800;margin-bottom:0.5rem">Sesión Expirada</h2>
  <p style="color:var(--c-muted);margin-bottom:2rem">
    El formulario expiró por inactividad. Por favor vuelve a ingresar tus credenciales.
  </p>
  <div class="d-flex flex-column gap-2">
    <a href="{{ route('admin.select_rol') }}" class="btn-primary-tt justify-content-center">
      <i class="bi bi-shield-lock me-2"></i>Ir al Panel Admin
    </a>
    <a href="{{ route('home') }}" class="btn-ghost-tt justify-content-center">
      <i class="bi bi-house me-2"></i>Volver a la Tienda
    </a>
  </div>
  <p style="color:var(--c-muted);font-size:0.75rem;margin-top:2rem">
    <i class="bi bi-info-circle me-1"></i>Si el error persiste, borra las cookies de tu navegador para <strong>localhost</strong>.
  </p>
</div>
<script>
  // Auto-redirect después de 4 segundos
  setTimeout(() => { window.location.href = '{{ route("admin.select_rol") }}'; }, 4000);
</script>
</body>
</html>
