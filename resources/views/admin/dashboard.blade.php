@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Resumen general del sistema')

@section('content')
<!-- Stats -->
<div class="row g-4 mb-4">
  @foreach([
    ['bi bi-cup-hot-fill','Pedidos Hoy',$stats['pedidos_hoy'],'badge-warning'],
    ['bi bi-hourglass-split','Pendientes',$stats['pedidos_pendientes'],'badge-danger'],
    ['bi bi-people-fill','Clientes',$stats['total_clientes'],'badge-info'],
    ['bi bi-calendar-check-fill','Reservas Hoy',$stats['reservas_hoy'],'badge-success'],
    ['bi bi-currency-dollar','Ingresos Hoy', 'S/ '.number_format($stats['ingresos_hoy'],2),'badge-success'],
    ['bi bi-exclamation-triangle-fill','Stock Bajo',$stats['productos_stock_bajo'],'badge-danger'],
  ] as [$ico,$lbl,$val,$badge])
    <div class="col-6 col-lg-4 col-xl-2 reveal">
      <div class="stat-card h-100">
        <div class="stat-ico"><i class="{{ $ico }}"></i></div>
        <div class="stat-num">{{ $val }}</div>
        <div class="stat-lbl">{{ $lbl }}</div>
      </div>
    </div>
  @endforeach
</div>

<div class="row g-4">
  <!-- Pedidos recientes -->
  <div class="col-lg-8 reveal">
    <div class="admin-panel">
      <div class="admin-panel-header">
        <h6 style="font-weight:700;margin:0">Pedidos Recientes</h6>
        <a href="{{ route('admin.pedidos.index') }}" class="btn-add" style="font-size:0.75rem">Ver todos</a>
      </div>
      <div class="table-responsive">
        <table class="adm-table">
          <thead>
            <tr>
              <th>#</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th><th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($pedidos_recientes as $p)
              <tr>
                <td><span style="color:var(--c-muted)">#{{ $p->id }}</span></td>
                <td>{{ $p->user->nombre ?? '—' }}</td>
                <td style="color:var(--c-gold);font-weight:700">S/ {{ number_format($p->total,2) }}</td>
                <td>
                  <span class="badge-tt {{ match($p->estado){
                    'pendiente'=>'badge-warning','en_preparacion'=>'badge-info',
                    'casi_listo'=>'badge-purple','listo'=>'badge-success',
                    'recogido'=>'badge-info','en_camino'=>'badge-info',
                    'cerca_destino'=>'badge-warning','entregado'=>'badge-success',
                    'cancelado'=>'badge-danger',default=>'badge-warning'} }}"
                    id="badge-{{ $p->id }}">{{ $p->estado_label }}</span>
                </td>
                <td style="color:var(--c-muted);font-size:0.8rem">{{ $p->fecha->format('d/m H:i') }}</td>
                <td>
                  <a href="{{ route('admin.pedidos.detalle',$p) }}" class="btn-add" style="font-size:0.7rem;padding:0.2rem 0.6rem">
                    Ver
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center py-4" style="color:var(--c-muted)">No hay pedidos</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Stock bajo -->
  <div class="col-lg-4 reveal">
    <div class="admin-panel h-100">
      <div class="admin-panel-header">
        <h6 style="font-weight:700;margin:0;color:var(--c-red)"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stock Bajo</h6>
      </div>
      <div class="p-3">
        @forelse($productos_bajo_stock as $prod)
          <div class="d-flex align-items-center gap-3 p-2 rounded-3 mb-2"
               style="background:rgba(255,61,113,0.06);border:1px solid rgba(255,61,113,0.15)">
            <div style="font-size:1.3rem;color:var(--c-red)"><i class="bi bi-box-seam-fill"></i></div>
            <div class="flex-grow-1">
              <div style="font-weight:600;font-size:0.875rem">{{ $prod->nombre }}</div>
              <div style="color:var(--c-muted);font-size:0.75rem">{{ $prod->categoria_label }}</div>
            </div>
            <span class="badge-tt badge-danger">{{ $prod->stock }} und.</span>
          </div>
        @empty
          <div class="text-center py-4" style="color:var(--c-muted)">
            <i class="bi bi-check-circle-fill text-success fs-4 d-block mb-2"></i>
            Stock en orden
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
