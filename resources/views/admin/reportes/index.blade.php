@extends('layouts.admin')
@section('title','Reportes')
@section('page-title','Reportes y Estadísticas')

@section('content')
<!-- Filtros -->
<div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
  <form method="GET" class="d-flex gap-2 flex-wrap">
    <div class="d-flex rounded-3 overflow-hidden" style="border:1px solid var(--c-border)">
      @foreach(['diario'=>'Diario','mensual'=>'Mensual','anual'=>'Anual'] as $k=>$v)
        <a href="{{ route('admin.reportes.index',['periodo'=>$k]) }}"
           class="px-4 py-2 text-decoration-none fw-600"
           style="background:{{ $periodo===$k?'var(--c-gold)':'transparent' }};color:{{ $periodo===$k?'#fff':'var(--c-muted)' }};font-size:0.875rem;font-weight:600">
          {{ $v }}
        </a>
      @endforeach
    </div>
    <input type="date" name="fecha" class="tt-input" style="max-width:180px"
           value="{{ $fecha }}" onchange="this.form.submit()">
    <input type="hidden" name="periodo" value="{{ $periodo }}">
  </form>
  <button onclick="window.print()" class="btn-ghost-tt">
    <i class="bi bi-printer"></i> Imprimir
  </button>
</div>

<!-- Stats del periodo -->
<div class="row g-4 mb-4">
  @foreach([
    ['bi bi-cash-coin','Ingresos Totales','S/ '.number_format($stats['ingresos_total'],2),'badge-success'],
    ['bi bi-box-seam-fill','Total Pedidos',$stats['total_pedidos'],'badge-info'],
    ['bi bi-check-circle-fill','Entregados',$stats['pedidos_entregados'],'badge-success'],
    ['bi bi-graph-up-arrow','Ticket Promedio','S/ '.number_format($stats['ticket_promedio'],2),'badge-warning'],
  ] as [$ico,$lbl,$val,$b])
    <div class="col-6 col-lg-3 reveal">
      <div class="stat-card">
        <div class="stat-ico"><i class="{{ $ico }}"></i></div>
        <div class="stat-num">{{ $val }}</div>
        <div class="stat-lbl">{{ $lbl }}</div>
      </div>
    </div>
  @endforeach
</div>

<div class="row g-4">
  <!-- Productos más vendidos -->
  <div class="col-lg-5 reveal">
    <div class="admin-panel admin-panel-body">
      <h6 style="font-weight:700;margin-bottom:1.25rem"><i class="bi bi-trophy-fill text-gold me-2"></i>Top Productos</h6>
      @forelse($productosMasVendidos as $item)
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="font-size:1.2rem;width:30px;text-align:center;color:var(--c-gold);font-weight:800">
            {{ $loop->iteration }}
          </div>
          <div class="flex-grow-1">
            <div style="font-weight:600;font-size:0.875rem">{{ $item->producto->nombre }}</div>
            <div style="color:var(--c-muted);font-size:0.75rem">{{ $item->producto->categoria_label }}</div>
          </div>
          <span class="badge-tt badge-success">{{ $item->total_vendido }} und.</span>
        </div>
      @empty
        <p style="color:var(--c-muted);font-size:0.875rem">Sin datos en este período</p>
      @endforelse
    </div>
  </div>

  <!-- Lista pedidos -->
  <div class="col-lg-7 reveal">
    <div class="adm-table-wrap" style="max-height:400px;overflow-y:auto">
      <table class="adm-table">
        <thead>
          <tr><th>#</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th></tr>
        </thead>
        <tbody>
          @forelse($pedidos as $p)
            <tr>
              <td style="color:var(--c-muted)">#{{ $p->id }}</td>
              <td>{{ $p->nombre_cliente }}</td>
              <td style="color:var(--c-gold);font-weight:700">S/ {{ number_format($p->total,2) }}</td>
              <td>
                <span class="badge-tt {{ $p->estado==='entregado'?'badge-success':'badge-warning' }}">
                  {{ $p->estado_label }}
                </span>
              </td>
              <td style="color:var(--c-muted);font-size:0.8rem">{{ $p->fecha->format('d/m H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-4" style="color:var(--c-muted)">Sin pedidos</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
