@extends('layouts.admin')
@section('title','Reclamos')
@section('page-title','Reclamos y Devoluciones')
@section('page-sub','Gestiona los reclamos de los clientes')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap">
  <form method="GET" class="d-flex gap-2">
    <select name="estado" class="tt-input" style="max-width:200px" onchange="this.form.submit()">
      <option value="" {{ !$filtro ? 'selected' : '' }}>Todos</option>
      @foreach(['abierta'=>'Abiertos','en_proceso'=>'En proceso','validada'=>'Validados','rechazada'=>'Rechazados'] as $k=>$v)
        <option value="{{ $k }}" {{ $filtro===$k ? 'selected' : '' }}>{{ $v }}</option>
      @endforeach
    </select>
  </form>
</div>

@if(session('success'))
  <div class="alert-tt-success mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
@endif

{{-- ── Vista filtrada (un solo estado) ── --}}
@if($filtro)
  <div class="row g-3">
    @forelse($incidencias as $inc)
      @php $p = $inc->pedido; @endphp
      @if($filtro === 'resuelta')
        {{-- Historial compacto --}}
        <div class="col-12">
          @include('admin.incidencias._fila_resuelta', ['inc' => $inc, 'p' => $p])
        </div>
      @else
        <div class="col-12 col-lg-6">
          @include('admin.incidencias._card_activa', ['inc' => $inc, 'p' => $p])
        </div>
      @endif
    @empty
      <div class="col-12 text-center py-5" style="color:var(--c-muted)">
        <i class="bi bi-inbox d-block mb-2" style="font-size:2.5rem;color:var(--c-gold)"></i>
        No hay reclamos en este estado.
      </div>
    @endforelse
  </div>
  <div class="mt-4">{{ $incidencias->links('pagination::bootstrap-5') }}</div>

{{-- ── Vista completa: activos + historial ── --}}
@else

  {{-- Activos --}}
  @if($activas->isEmpty())
    <div class="text-center py-4 mb-4 rounded-3"
         style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2)">
      <i class="bi bi-check2-all d-block mb-1" style="font-size:2rem;color:#86efac"></i>
      <span style="color:#86efac;font-size:0.9rem">Sin reclamos pendientes</span>
    </div>
  @else
    <div class="row g-3 mb-4">
      @foreach($activas as $inc)
        @php $p = $inc->pedido; @endphp
        <div class="col-12 col-lg-6">
          @include('admin.incidencias._card_activa', ['inc' => $inc, 'p' => $p])
        </div>
      @endforeach
    </div>
  @endif

  {{-- Historial de resueltos --}}
  @if($resueltas->isNotEmpty())
    <div class="mb-2 mt-2 d-flex align-items-center gap-2">
      <div style="flex:1;height:1px;background:var(--c-border)"></div>
      <span style="font-size:0.78rem;color:var(--c-muted);white-space:nowrap">
        <i class="bi bi-clock-history me-1"></i>Historial de resueltos
      </span>
      <div style="flex:1;height:1px;background:var(--c-border)"></div>
    </div>

    <div class="d-flex flex-column gap-2 mb-4">
      @foreach($resueltas as $inc)
        @php $p = $inc->pedido; @endphp
        @include('admin.incidencias._fila_resuelta', ['inc' => $inc, 'p' => $p])
      @endforeach
    </div>
    <div class="mt-2">{{ $resueltas->links('pagination::bootstrap-5') }}</div>
  @endif

@endif

@endsection
