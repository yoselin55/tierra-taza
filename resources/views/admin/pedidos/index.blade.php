@extends('layouts.admin')
@section('title', auth()->user()->esBarista() ? 'Cocina' : (auth()->user()->esCoordinadorDelivery() ? 'Delivery' : 'Pedidos'))
@section('page-title', auth()->user()->esBarista() ? 'Estación de Cocina' : (auth()->user()->esCoordinadorDelivery() ? 'Control de Delivery' : 'Gestión de Pedidos'))
@section('page-sub', auth()->user()->esBarista() ? 'Cambiar estados de preparación' : (auth()->user()->esCoordinadorDelivery() ? 'Seguimiento de entregas' : 'Administración de pedidos'))

@section('content')

{{-- VISTA BARISTA / COCINERO --}}
@if($user->esBarista())

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
  <span class="badge-tt badge-info"><i class="bi bi-fire me-1"></i>Modo Cocina</span>
  <span style="font-size:0.85rem;color:var(--c-muted)">
    Solo ves pedidos en tu zona · Actualiza el estado al avanzar
  </span>
  <button onclick="location.reload()" class="btn-add ms-auto" style="gap:0.4rem">
    <i class="bi bi-arrow-clockwise"></i> Actualizar
  </button>
</div>

{{-- Columnas Kanban --}}
<div class="row g-3">
  @php
    $columnas = [
      'pendiente'      => ['label'=>'Por preparar',        'color'=>'#f59e0b', 'icon'=>'bi-clock-history'],
      'en_preparacion' => ['label'=>'En preparación',      'color'=>'#60a5fa', 'icon'=>'bi-fire'],
      'casi_listo'     => ['label'=>'Casi listo',           'color'=>'#a855f7', 'icon'=>'bi-stars'],
      'listo'          => ['label'=>'Listo para despacho',  'color'=>'#22c55e', 'icon'=>'bi-check-circle-fill'],
    ];
    $siguientes = ['pendiente'=>'en_preparacion','en_preparacion'=>'casi_listo','casi_listo'=>'listo'];
    $btnLabels  = ['pendiente'=>'Iniciar preparación','en_preparacion'=>'Marcar casi listo','casi_listo'=>'Listo para despacho'];
    $btnIcons   = ['pendiente'=>'bi-fire','en_preparacion'=>'bi-stars','casi_listo'=>'bi-check-circle-fill'];
    $grupos     = $pedidos->groupBy('estado');
  @endphp

  @foreach($columnas as $estado => $col)
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="kanban-col">
        <div class="kanban-col-header" style="border-color:{{ $col['color'] }}">
          <i class="bi {{ $col['icon'] }}" style="color:{{ $col['color'] }}"></i>
          <span style="color:{{ $col['color'] }};font-weight:700">{{ $col['label'] }}</span>
          <span class="kanban-count" style="background:{{ $col['color'] }}22;color:{{ $col['color'] }}">
            {{ $grupos->get($estado, collect())->count() }}
          </span>
        </div>
        <div class="kanban-cards">
          @forelse($grupos->get($estado, collect()) as $p)
            <div class="pedido-card-cocina {{ $estado === 'pendiente' ? 'nuevo-pedido' : '' }}">
              <div class="d-flex justify-content-between mb-2">
                <div>
                  <div style="font-weight:700">{{ $p->nombre_cliente }}</div>
                  <div style="font-size:0.72rem;color:var(--c-muted)">#{{ $p->id }} · {{ $p->fecha->format('H:i') }}</div>
                </div>
                <span class="badge-tt {{ $p->estado_badge }}" id="badge-{{ $p->id }}">{{ $p->estado_label }}</span>
              </div>
              @if($p->notas)
                <div class="mb-2 p-2 rounded" style="background:rgba(200,150,60,0.08);font-size:0.8rem;color:var(--c-muted)">
                  <i class="bi bi-chat-text me-1" style="color:var(--c-gold)"></i>{{ $p->notas }}
                </div>
              @endif
              <ul style="list-style:none;padding:0;margin:0 0 1rem 0;font-size:0.85rem">
                @foreach($p->detalles as $d)
                  <li class="d-flex justify-content-between py-1" style="border-bottom:1px solid var(--c-border)">
                    <span><span style="color:var(--c-gold);font-weight:700">{{ $d->cantidad }}×</span> {{ $d->producto->nombre }}</span>
                  </li>
                @endforeach
              </ul>
              @if(isset($siguientes[$estado]))
                <button class="btn-estado-cocina js-estado-btn w-100"
                        data-pedido="{{ $p->id }}"
                        data-estado="{{ $siguientes[$estado] }}"
                        style="border-color:{{ $col['color'] }}55;color:{{ $col['color'] }}">
                  <i class="bi {{ $btnIcons[$estado] }}"></i> {{ $btnLabels[$estado] }}
                </button>
              @else
                <div class="text-center py-1" style="font-size:0.8rem;color:var(--c-green)">
                  <i class="bi bi-check2-all me-1"></i>Esperando delivery
                </div>
              @endif
            </div>
          @empty
            <div class="kanban-empty"><i class="bi bi-check2 me-1"></i>Vacío</div>
          @endforelse
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- VISTA COORDINADOR DELIVERY --}}
@elseif($user->esCoordinadorDelivery())

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
  <span class="badge-tt badge-warning"><i class="bi bi-truck me-1"></i>Modo Delivery</span>
  <span style="font-size:0.85rem;color:var(--c-muted)">
    Gestiona el estado de cada entrega
  </span>
  <button onclick="location.reload()" class="btn-add ms-auto" style="gap:0.4rem">
    <i class="bi bi-arrow-clockwise"></i> Actualizar
  </button>
</div>

<div class="row g-3">
  @php
    $colsDelivery = [
      'listo'         => ['label'=>'Para recoger',   'color'=>'#22c55e', 'icon'=>'bi-box-seam'],
      'recogido'      => ['label'=>'Recogido',        'color'=>'#60a5fa', 'icon'=>'bi-bag-check'],
      'en_camino'     => ['label'=>'En camino',       'color'=>'#f59e0b', 'icon'=>'bi-truck'],
      'cerca_destino' => ['label'=>'Cerca destino',   'color'=>'#a855f7', 'icon'=>'bi-geo-alt-fill'],
      'entregado'     => ['label'=>'Entregado',       'color'=>'#6b7280', 'icon'=>'bi-house-check-fill'],
    ];
    $sigDelivery = ['listo'=>'recogido','recogido'=>'en_camino','en_camino'=>'cerca_destino','cerca_destino'=>'entregado'];
    $btnD = ['listo'=>'Recoger pedido','recogido'=>'Salió a entrega','en_camino'=>'Cerca al destino','cerca_destino'=>'Confirmar entrega'];
    $icoD = ['listo'=>'bi-bag-check','recogido'=>'bi-truck','en_camino'=>'bi-geo-alt-fill','cerca_destino'=>'bi-house-check-fill'];
    $gruposD = $pedidos->groupBy('estado');
  @endphp

  @foreach($colsDelivery as $estado => $col)
    <div class="col-sm-6 col-xl" style="min-width:200px">
      <div class="kanban-col">
        <div class="kanban-col-header" style="border-color:{{ $col['color'] }}">
          <i class="bi {{ $col['icon'] }}" style="color:{{ $col['color'] }}"></i>
          <span style="color:{{ $col['color'] }};font-weight:700;font-size:0.85rem">{{ $col['label'] }}</span>
          <span class="kanban-count" style="background:{{ $col['color'] }}22;color:{{ $col['color'] }}">
            {{ $gruposD->get($estado, collect())->count() }}
          </span>
        </div>
        <div class="kanban-cards">
          @forelse($gruposD->get($estado, collect()) as $p)
            <div class="pedido-card-cocina">
              <div class="d-flex justify-content-between mb-2">
                <div>
                  <div style="font-weight:700;font-size:0.9rem">{{ $p->nombre_cliente }}</div>
                  <div style="font-size:0.7rem;color:var(--c-muted)">#{{ $p->id }}</div>
                </div>
                <span class="badge-tt {{ $p->estado_badge }}" id="badge-{{ $p->id }}" style="font-size:0.7rem">{{ $p->estado_label }}</span>
              </div>
              <div style="color:var(--c-gold);font-weight:700;font-size:1rem;margin-bottom:0.5rem">
                S/ {{ number_format($p->total,2) }}
              </div>
              <div style="font-size:0.75rem;color:var(--c-muted);margin-bottom:0.75rem">
                {{ $p->metodo_pago_label }} · {{ $p->fecha->format('H:i') }}
              </div>
              @if(isset($sigDelivery[$estado]))
                <button class="btn-estado-cocina js-estado-btn w-100"
                        data-pedido="{{ $p->id }}"
                        data-estado="{{ $sigDelivery[$estado] }}"
                        style="border-color:{{ $col['color'] }}55;color:{{ $col['color'] }}">
                  <i class="bi {{ $icoD[$estado] }}"></i> {{ $btnD[$estado] }}
                </button>
              @else
                <div class="text-center py-1" style="font-size:0.8rem;color:var(--c-green)">
                  <i class="bi bi-check2-all me-1"></i>Completado
                </div>
              @endif
            </div>
          @empty
            <div class="kanban-empty"><i class="bi bi-check2 me-1"></i>Vacío</div>
          @endforelse
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- VISTA TABLA — cajero / admin --}}
@else

@php
  $todosEstados = [
    'pendiente'=>'Pendiente pago','en_preparacion'=>'En preparación',
    'casi_listo'=>'Casi listo','listo'=>'Listo para despacho',
    'recogido'=>'Recogido','en_camino'=>'En camino',
    'cerca_destino'=>'Cerca destino','entregado'=>'Entregado','cancelado'=>'Cancelado',
  ];
@endphp

<div class="d-flex gap-2 mb-4 flex-wrap align-items-center justify-content-between">
  <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
    <select name="estado" class="tt-input" style="max-width:220px" onchange="this.form.submit()">
      <option value="">Todos los estados</option>
      @foreach($todosEstados as $k=>$v)
        <option value="{{ $k }}" {{ request('estado')===$k?'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
  </form>
  @if($user->esCajero() || $user->esAdminGeneral())
    <a href="{{ route('admin.pagos.index') }}" class="btn-add" style="gap:0.4rem">
      <i class="bi bi-wallet2"></i> Ir a Pagos
    </a>
  @endif
</div>

<div class="adm-table-wrap reveal">
  <table class="adm-table">
    <thead>
      <tr>
        <th>#</th><th>Cliente</th><th>Total</th><th>Pago</th>
        <th>Estado</th><th>Fecha</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pedidos as $p)
        <tr>
          <td style="color:var(--c-muted)">#{{ $p->id }}</td>
          <td>
            <div style="font-weight:600">{{ $p->nombre_cliente }}</div>
            <div style="font-size:0.75rem;color:var(--c-muted)">{{ $p->user->email ?? '' }}</div>
          </td>
          <td style="color:var(--c-gold);font-weight:700">S/ {{ number_format($p->total,2) }}</td>
          <td><span class="badge-tt badge-info" style="font-size:0.72rem">{{ $p->metodo_pago_label }}</span></td>
          <td><span class="badge-tt {{ $p->estado_badge }}" id="badge-{{ $p->id }}">{{ $p->estado_label }}</span></td>
          <td style="color:var(--c-muted);font-size:0.8rem">{{ $p->fecha->format('d/m H:i') }}</td>
          <td>
            <div class="d-flex gap-1 flex-wrap align-items-center">
              <a href="{{ route('admin.pedidos.detalle',$p) }}" class="btn-add" style="font-size:0.75rem">
                <i class="bi bi-eye"></i>
              </a>
              @if($user->esAdminGeneral() || $user->esAdminSistema())
                <select class="tt-input js-estado-select" data-pedido="{{ $p->id }}"
                        style="font-size:0.75rem;padding:0.25rem 0.5rem;max-width:170px">
                  @foreach($todosEstados as $st=>$stLabel)
                    <option value="{{ $st }}" {{ $p->estado===$st?'selected':'' }}>{{ $stLabel }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center py-5" style="color:var(--c-muted)">
            <i class="bi bi-bag-x d-block mb-2" style="font-size:2rem"></i>
            No hay pedidos
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endif

<div class="mt-3">{{ $pedidos->links('pagination::bootstrap-5') }}</div>

@push('scripts')
<script>
document.querySelectorAll('.js-estado-btn').forEach(btn => {
  btn.addEventListener('click', async function () {
    const id = this.dataset.pedido, estado = this.dataset.estado;
    this.disabled = true;
    const orig = this.innerHTML;
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    try {
      const r = await fetch(`/admin/pedidos/${id}/estado`, {
        method: 'PATCH',
        headers: {'Content-Type':'application/json','Accept':'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
        body: JSON.stringify({ estado }),
      });
      const d = await r.json();
      if (d.success) {
        window.toast?.('Estado actualizado: ' + d.label, 'ok');
        setTimeout(() => location.reload(), 700);
      } else {
        window.toast?.(d.error ?? 'Error', 'err');
        this.disabled = false; this.innerHTML = orig;
      }
    } catch(e) {
      window.toast?.('Error de red', 'err');
      this.disabled = false; this.innerHTML = orig;
    }
  });
});

document.querySelectorAll('.js-estado-select').forEach(sel => {
  sel.addEventListener('change', async function () {
    const id = this.dataset.pedido, estado = this.value;
    const r = await fetch(`/admin/pedidos/${id}/estado`, {
      method: 'PATCH',
      headers: {'Content-Type':'application/json','Accept':'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
      body: JSON.stringify({ estado }),
    });
    const d = await r.json();
    if (d.success) {
      const b = document.getElementById(`badge-${id}`);
      if (b) { b.textContent = d.label; b.className = `badge-tt ${d.badge}`; }
      window.toast?.('Estado actualizado', 'ok');
    } else {
      window.toast?.(d.error ?? 'Error', 'err');
    }
  });
});
</script>
@endpush
@endsection
