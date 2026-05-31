@extends('layouts.admin')
@section('title','Pedido #' . $pedido->id)
@section('page-title','Pedido #' . $pedido->id)

@section('content')
<div class="row g-4">
  <div class="col-lg-8">
    <div class="admin-panel admin-panel-body mb-4">
      <h6 style="font-weight:700;margin-bottom:1.5rem">Productos del Pedido</h6>
      @foreach($pedido->detalles as $d)
        <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--c-border)">
          <img src="{{ $d->producto->imagen_url }}" style="width:52px;height:52px;border-radius:8px;object-fit:cover">
          <div class="flex-grow-1">
            <div style="font-weight:600">{{ $d->producto->nombre }}</div>
            <div style="color:var(--c-muted);font-size:0.8rem">S/ {{ number_format($d->precio,2) }} × {{ $d->cantidad }}</div>
          </div>
          <span style="color:var(--c-gold);font-weight:700">S/ {{ number_format($d->subtotal,2) }}</span>
        </div>
      @endforeach
      <div class="d-flex justify-content-between" style="font-size:1.1rem;font-weight:800;color:var(--c-gold)">
        <span>Total</span>
        <span>S/ {{ number_format($pedido->total,2) }}</span>
      </div>
    </div>

    @if($pedido->incidencias->isNotEmpty())
      <div style="background:rgba(255,61,113,0.06);border:1px solid rgba(255,61,113,0.2);border-radius:var(--radius-lg);padding:1.5rem;transition:box-shadow 0.3s var(--ease)">
        <h6 style="font-weight:700;color:var(--c-red);margin-bottom:1rem"><i class="bi bi-exclamation-triangle-fill me-2"></i>Incidencias</h6>
        @foreach($pedido->incidencias as $inc)
          <div class="mb-2 p-3" style="background:rgba(255,61,113,0.05);border-radius:8px">
            <div style="font-weight:600">{{ $inc->tipo_label }}</div>
            <div style="color:var(--c-muted);font-size:0.875rem">{{ $inc->descripcion }}</div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="col-lg-4">
    {{-- Info del pedido --}}
    <div class="admin-panel admin-panel-body mb-3">
      <h6 style="font-weight:700;margin-bottom:1rem">Info del Pedido</h6>
      <div class="d-flex flex-column gap-2" style="font-size:0.875rem">
        @foreach([
          ['Cliente',$pedido->nombre_cliente],
          ['DNI',$pedido->dni_cliente],
          ['Email',$pedido->user->email??'—'],
          ['Pago',$pedido->metodo_pago_label],
          ['Fecha',$pedido->fecha->format('d/m/Y H:i')],
        ] as [$lbl,$val])
          <div class="d-flex justify-content-between">
            <span style="color:var(--c-muted)">{{ $lbl }}</span>
            <span style="font-weight:600">{{ $val }}</span>
          </div>
          <div class="divider-gold" style="margin:0.25rem 0"></div>
        @endforeach
      </div>
    </div>

    {{-- Panel de cambio de estado según rol --}}
    @php $user = auth()->user(); @endphp

    @if($user->esBarista() || $user->esCoordinadorDelivery() || $user->esCajero() || $user->esAdminSistema() || $user->esAdminGeneral())
    <div class="admin-panel admin-panel-body">
      <h6 style="font-weight:700;margin-bottom:1rem">
        <i class="bi bi-arrow-repeat me-2 text-gold"></i>Actualizar Estado
      </h6>

      <div style="font-size:0.8rem;color:var(--c-muted);margin-bottom:1rem">
        Estado actual:
        <span class="badge-tt {{ match($pedido->estado){
          'pendiente'=>'badge-warning','en_preparacion'=>'badge-info',
          'casi_listo'=>'badge-purple','listo'=>'badge-success',
          'recogido'=>'badge-info','en_camino'=>'badge-info',
          'cerca_destino'=>'badge-warning','entregado'=>'badge-success',
          'cancelado'=>'badge-danger',default=>'badge-warning'
        } }}" id="detalle-badge">{{ $pedido->estado_label }}</span>
      </div>

      <div class="d-flex flex-column gap-2">
        @if($user->esBarista())
          @if($pedido->estado === 'pendiente')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="en_preparacion" style="font-size:0.85rem">
              <i class="bi bi-fire me-2"></i>Marcar Preparando
            </button>
          @elseif($pedido->estado === 'en_preparacion')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="casi_listo" style="font-size:0.85rem;background:linear-gradient(135deg,#a855f7,#7c3aed)">
              <i class="bi bi-stars me-2"></i>Marcar Casi Listo
            </button>
          @elseif($pedido->estado === 'casi_listo')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="listo" style="font-size:0.85rem;background:linear-gradient(135deg,#22c55e,#16a34a)">
              <i class="bi bi-check-circle-fill me-2"></i>Listo para Despacho
            </button>
          @else
            <p style="color:var(--c-muted);font-size:0.8rem;margin:0">
              <i class="bi bi-info-circle me-1"></i>No hay acciones disponibles para este estado.
            </p>
          @endif

        @elseif($user->esCoordinadorDelivery())
          @if($pedido->estado === 'listo')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="recogido" style="font-size:0.85rem;background:linear-gradient(135deg,#f59e0b,#d97706)">
              <i class="bi bi-box-seam-fill me-2"></i>Confirmar Recogida
            </button>
          @elseif($pedido->estado === 'recogido')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="en_camino" style="font-size:0.85rem;background:linear-gradient(135deg,#3b82f6,#2563eb)">
              <i class="bi bi-bicycle me-2"></i>Marcar En Camino
            </button>
          @elseif($pedido->estado === 'en_camino')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="cerca_destino" style="font-size:0.85rem;background:linear-gradient(135deg,#f59e0b,#d97706)">
              <i class="bi bi-geo-alt-fill me-2"></i>Cerca al Destino
            </button>
          @elseif($pedido->estado === 'cerca_destino')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="entregado" style="font-size:0.85rem;background:linear-gradient(135deg,#22c55e,#16a34a)">
              <i class="bi bi-house-fill me-2"></i>Marcar Entregado
            </button>
          @else
            <p style="color:var(--c-muted);font-size:0.8rem;margin:0">
              <i class="bi bi-info-circle me-1"></i>No hay acciones disponibles para este estado.
            </p>
          @endif

        @elseif($user->esCajero())
          @if($pedido->estado === 'pendiente')
            <button class="btn-primary-tt justify-content-center js-detalle-estado"
                    data-estado="en_preparacion" style="font-size:0.85rem;background:linear-gradient(135deg,#22c55e,#16a34a)">
              <i class="bi bi-check2-circle me-2"></i>Confirmar Cobro
            </button>
          @else
            <p style="color:var(--c-muted);font-size:0.8rem;margin:0">
              <i class="bi bi-info-circle me-1"></i>Pago ya procesado.
            </p>
          @endif

        @else
          {{-- Admin sistema / Admin general: control total --}}
          <select class="tt-input js-detalle-select" style="font-size:0.85rem">
            @foreach([
              'pendiente'      => 'Pendiente',
              'en_preparacion' => 'En Preparación',
              'casi_listo'     => 'Casi Listo',
              'listo'          => 'Listo para Despacho',
              'recogido'       => 'Recogido por Delivery',
              'en_camino'      => 'En Camino',
              'cerca_destino'  => 'Cerca al Destino',
              'entregado'      => 'Entregado',
              'cancelado'      => 'Cancelado',
            ] as $st => $stLabel)
              <option value="{{ $st }}" {{ $pedido->estado===$st ? 'selected' : '' }}>{{ $stLabel }}</option>
            @endforeach
          </select>
          <button class="btn-primary-tt justify-content-center" id="btn-apply-estado" style="font-size:0.85rem">
            <i class="bi bi-check-lg me-2"></i>Aplicar Cambio
          </button>
        @endif
      </div>
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
const pedidoId = {{ $pedido->id }};

async function cambiarEstado(nuevoEstado, btnEl) {
  if (btnEl) { btnEl.disabled = true; btnEl.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...'; }

  const r = await fetch(`/admin/pedidos/${pedidoId}/estado`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
    },
    body: JSON.stringify({ estado: nuevoEstado }),
  });

  const d = await r.json();
  if (d.success) {
    const badge = document.getElementById('detalle-badge');
    if (badge) { badge.textContent = d.label; badge.className = `badge-tt ${d.badge}`; }
    window.toast?.('Estado actualizado correctamente', 'ok');
    // Recargar para mostrar nuevas acciones disponibles
    setTimeout(() => location.reload(), 900);
  } else {
    window.toast?.(d.error ?? 'No se pudo actualizar', 'err');
    if (btnEl) { btnEl.disabled = false; btnEl.textContent = 'Reintentar'; }
  }
}

// Botones de acción rápida
document.querySelectorAll('.js-detalle-estado').forEach(btn => {
  btn.addEventListener('click', () => cambiarEstado(btn.dataset.estado, btn));
});

// Admin: select + botón
const applyBtn = document.getElementById('btn-apply-estado');
if (applyBtn) {
  applyBtn.addEventListener('click', () => {
    const sel = document.querySelector('.js-detalle-select');
    if (sel) cambiarEstado(sel.value, applyBtn);
  });
}
</script>
@endpush
@endsection
