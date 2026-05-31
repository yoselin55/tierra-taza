@extends('layouts.admin')
@section('title','Validar Pagos')
@section('page-title','Validación de Pagos')
@section('page-sub','Revisa y aprueba los pagos pendientes de los clientes')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap align-items-center">
  <form method="GET" class="d-flex gap-2">
    <select name="estado" class="tt-input" style="max-width:200px" onchange="this.form.submit()">
      <option value="">Todos</option>
      @foreach(['pendiente'=>'Pendientes','completado'=>'Aprobados','rechazado'=>'Rechazados'] as $k=>$v)
        <option value="{{ $k }}" {{ request('estado')===$k?'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
  </form>
  <span class="badge-tt badge-warning ms-auto">
    {{ $pagos->where('estado','pendiente')->count() }} pendiente(s)
  </span>
</div>

@if(session('success'))
  <div class="alert-tt-success mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-3">
  @forelse($pagos as $pago)
    @php
      $p = $pago->pedido;
      $datos = $pago->datos_pago ?? [];
    @endphp
    <div class="col-12 col-lg-6">
      <div class="pago-card reveal {{ $pago->estado === 'pendiente' ? 'pendiente' : '' }}">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <div style="font-size:0.7rem;color:var(--c-muted)">Pedido #{{ $p->id }}</div>
            <div style="font-weight:700">{{ $p->nombre_cliente }}</div>
            <div style="font-size:0.8rem;color:var(--c-muted)">{{ $p->user->email ?? '' }}</div>
          </div>
          <div class="text-end">
            <span class="badge-tt {{ $pago->estado_badge }}">{{ $pago->estado_label }}</span>
            <div style="color:var(--c-gold);font-weight:700;font-size:1.1rem;margin-top:0.25rem">
              S/ {{ number_format($p->total,2) }}
            </div>
          </div>
        </div>

        {{-- Método e info de pago --}}
        <div class="pago-metodo-info mb-3">
          <div style="font-weight:600;font-size:0.875rem;margin-bottom:0.5rem">
            <i class="bi bi-wallet2 me-1" style="color:var(--c-gold)"></i>
            {{ $p->metodo_pago_label }}
          </div>
          @if(!empty($datos))
            <div class="row g-2" style="font-size:0.82rem">
              @foreach($datos as $campo => $valor)
                @if($valor)
                  <div class="col-6">
                    <span style="color:var(--c-muted)">{{ ucfirst(str_replace('_',' ',$campo)) }}:</span>
                    <span style="font-weight:600"> {{ $valor }}</span>
                  </div>
                @endif
              @endforeach
            </div>
          @else
            <span style="color:var(--c-muted);font-size:0.82rem">Sin datos adicionales (efectivo)</span>
          @endif
        </div>

        {{-- Nota cajero si existe --}}
        @if($pago->notas_cajero)
          <div class="mb-3 px-3 py-2 rounded-2"
               style="background:rgba(200,150,60,0.08);border:1px solid rgba(200,150,60,0.2);font-size:0.82rem">
            <i class="bi bi-chat-quote me-1" style="color:var(--c-gold)"></i>
            Nota cajero: {{ $pago->notas_cajero }}
          </div>
        @endif

        {{-- Botón comprobante si aprobado --}}
        @if($pago->estado === 'completado')
          <div class="d-flex gap-2 mt-1">
            <a href="{{ route('admin.pagos.comprobante', $pago) }}" target="_blank"
               class="btn-add flex-grow-1"
               style="justify-content:center;padding:0.5rem;font-size:0.8rem;border-color:rgba(34,197,94,0.35);color:#22c55e;gap:0.4rem">
              <i class="bi bi-file-earmark-check"></i> Ver Comprobante
            </a>
          </div>
        @endif

        {{-- Acciones (solo si pendiente) --}}
        @if($pago->estado === 'pendiente')
          <form action="{{ route('admin.pagos.validar', $pago) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-2">
              <textarea name="notas_cajero" class="tt-input" rows="2"
                        placeholder="Observaciones (opcional)..." style="font-size:0.82rem"></textarea>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" name="accion" value="aprobar" class="btn-add flex-grow-1"
                      style="color:#22c55e;border-color:rgba(34,197,94,0.4);justify-content:center;padding:0.6rem">
                <i class="bi bi-check-circle-fill me-1"></i> Aprobar
              </button>
              <button type="submit" name="accion" value="rechazar" class="btn-add flex-grow-1"
                      style="color:#ef4444;border-color:rgba(239,68,68,0.4);justify-content:center;padding:0.6rem"
                      onclick="return confirm('¿Rechazar este pago?')">
                <i class="bi bi-x-circle-fill me-1"></i> Rechazar
              </button>
            </div>
          </form>
        @endif

        {{-- Fecha --}}
        <div style="font-size:0.75rem;color:var(--c-muted);margin-top:0.75rem;text-align:right">
          {{ $pago->fecha->format('d/m/Y H:i') }}
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5" style="color:var(--c-muted)">
      <i class="bi bi-check-circle d-block mb-2" style="font-size:2.5rem;color:var(--c-gold)"></i>
      No hay pagos en este estado.
    </div>
  @endforelse
</div>

<div class="mt-4">{{ $pagos->links('pagination::bootstrap-5') }}</div>
@endsection