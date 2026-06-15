@extends('layouts.app')
@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-5">

  <!-- Encabezado -->
  <div class="page-header-tt reveal">
    <div class="page-header-icon">
      <i class="bi bi-bag-check-fill"></i>
    </div>
    <div>
      <h1>Mis Pedidos</h1>
      <span style="color:var(--c-muted);font-size:0.85rem">Historial y seguimiento de tus pedidos</span>
    </div>
  </div>

  @if($pedidos->isEmpty())
    <div class="page-empty reveal">
      <div class="page-empty-icon">
        <i class="bi bi-bag-x"></i>
      </div>
      <h3>Aún no tienes pedidos</h3>
      <p>Explora nuestro catálogo y haz tu primer pedido</p>
      <a href="{{ route('catalogo') }}" class="btn-primary-tt">
        <i class="bi bi-grid-fill"></i> Ver Catálogo
      </a>
    </div>

  @else
    <div class="d-flex flex-column gap-3">
      @foreach($pedidos as $pedido)
        <div class="pedido-card">

          <!-- Header del pedido -->
          <div class="pedido-card-header">
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <div>
                <div class="pedido-num">Pedido #{{ $pedido->id }}</div>
                <div class="pedido-fecha">{{ $pedido->fecha->format('d/m/Y · H:i') }}</div>
              </div>
              <span class="badge {{ $pedido->estado_badge }} px-3 py-2"
                    id="estado-badge-{{ $pedido->id }}"
                    data-estado="{{ $pedido->estado }}">
                {{ $pedido->estado_label }}
              </span>
            </div>

            <div class="d-flex align-items-center gap-3">
              <div class="text-end">
                <div class="pedido-total">S/ {{ number_format($pedido->total, 2) }}</div>
                <div class="pedido-metodo">
                  <i class="bi bi-credit-card" style="font-size:0.7rem"></i>
                  {{ $pedido->metodo_pago_label }}
                </div>
              </div>
              <div class="pedido-actions">
                <a href="{{ route('pedidos.detalle', $pedido) }}" class="pedido-btn">
                  <i class="bi bi-eye"></i> Ver
                </a>
                <a href="{{ route('pedidos.boleta', $pedido) }}" class="pedido-btn gold">
                  <i class="bi bi-receipt"></i> Boleta
                </a>
              </div>
            </div>
          </div>

          <!-- Barra de progreso -->
          <div class="pedido-card-body">
            <div class="progreso-pedido" id="progreso-{{ $pedido->id }}">
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
      @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
      {{ $pedidos->links('pagination::bootstrap-5') }}
    </div>
  @endif

</div>

@push('scripts')
<script>
(function () {
  var activos = [];
  document.querySelectorAll('[id^="estado-badge-"]').forEach(function (el) {
    var estado = el.dataset.estado;
    if (estado !== 'entregado' && estado !== 'cancelado') {
      activos.push(parseInt(el.id.replace('estado-badge-', '')));
    }
  });

  if (activos.length === 0) return;

  function actualizarProgreso(pedidoId, paso) {
    var barra = document.getElementById('progreso-' + pedidoId);
    if (!barra) return;
    barra.querySelectorAll('.progreso-paso').forEach(function (el, i) {
      var stepNum = i + 1;
      el.classList.remove('completado', 'activo');
      if (paso > stepNum) el.classList.add('completado');
      else if (paso === stepNum) el.classList.add('activo');
    });
  }

  setInterval(async function () {
    try {
      var resp = await fetch('{{ route("pedidos.estados_poll") }}');
      if (!resp.ok) return;
      var pedidos = await resp.json();

      pedidos.forEach(function (p) {
        var badge = document.getElementById('estado-badge-' + p.id);
        if (!badge) return;
        if (badge.dataset.estado !== p.estado) {
          badge.dataset.estado = p.estado;
          badge.className = 'badge ' + p.badge + ' px-3 py-2';
          badge.textContent = p.label;
          actualizarProgreso(p.id, p.paso);
          window.toast && window.toast('Tu pedido #' + p.id + ' ahora está: ' + p.label, 'info');
          if (p.estado === 'entregado' || p.estado === 'cancelado') {
            activos = activos.filter(function (id) { return id !== p.id; });
          }
        }
      });
    } catch (e) {}
  }, 7000);
})();
</script>
@endpush
@endsection
