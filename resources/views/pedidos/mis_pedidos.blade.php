@extends('layouts.app')
@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-5">
  <h1 class="fw-bold mb-4"><i class="bi bi-bag-check me-2" style="color:var(--c-gold)"></i>Mis Pedidos</h1>

  @if($pedidos->isEmpty())
    <div class="text-center py-5">
      <div style="font-size:3rem;color:var(--c-muted)"><i class="bi bi-bag-x"></i></div>
      <h3 class="mt-3 fw-bold">Aún no tienes pedidos</h3>
      <p class="text-muted">¡Explora nuestro catálogo y haz tu primer pedido!</p>
      <a href="{{ route('catalogo') }}" class="btn-cafe btn btn-lg mt-3">Ver Catálogo</a>
    </div>
  @else
    <div class="row g-4">
      @foreach($pedidos as $pedido)
        <div class="col-12">
          <div class="p-4 rounded-3" style="background:var(--c-surface);border:1px solid var(--c-border)">
            <div class="row align-items-center">
              <div class="col-md-3">
                <div class="text-muted small">Pedido #{{ $pedido->id }}</div>
                <div class="fw-bold">{{ $pedido->fecha->format('d/m/Y H:i') }}</div>
              </div>
              <div class="col-md-3">
                <span class="badge {{ $pedido->estado_badge }} px-3 py-2" id="estado-badge-{{ $pedido->id }}">
                  {{ $pedido->estado_label }}
                </span>
              </div>
              <div class="col-md-2">
                <span class="precio-tag">S/ {{ number_format($pedido->total, 2) }}</span>
              </div>
              <div class="col-md-2">
                <small class="text-muted">{{ $pedido->metodo_pago_label }}</small>
              </div>
              <div class="col-md-2 text-end">
                <a href="{{ route('pedidos.detalle', $pedido) }}" class="btn btn-sm btn-outline-secondary me-1">
                  Ver
                </a>
                <a href="{{ route('pedidos.boleta', $pedido) }}" class="btn btn-sm btn-outline-warning">
                  Boleta
                </a>
              </div>
            </div>

            <!-- Barra de progreso -->
            <div class="mt-3">
              <div class="progreso-pedido">
                @foreach([
                  [1, 'bi bi-clipboard-check-fill', 'Pendiente'],
                  [2, 'bi bi-fire', 'Preparando'],
                  [3, 'bi bi-check-circle-fill', 'Listo'],
                  [4, 'bi bi-bicycle', 'En camino'],
                  [5, 'bi bi-house-fill', 'Entregado'],
                ] as [$paso, $icono, $label])
                  <div class="progreso-paso {{ $pedido->estado_paso >= $paso ? ($pedido->estado_paso > $paso ? 'completado' : 'activo') : '' }}">
                    <div class="progreso-circulo"><i class="{{ $icono }}"></i></div>
                    <div class="progreso-label">{{ $label }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
      {{ $pedidos->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
