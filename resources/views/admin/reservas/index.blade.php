@extends('layouts.admin')
@section('title','Reservas')
@section('page-title','Gestión de Reservas')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
  <form method="GET" class="d-flex gap-2 flex-wrap">
    <select name="estado" class="tt-input" style="max-width:200px" onchange="this.form.submit()">
      <option value="">Todos los estados</option>
      @foreach(['pendiente','confirmada','cancelada','completada'] as $st)
        <option value="{{ $st }}" {{ request('estado')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
    <input type="date" name="fecha" class="tt-input" style="max-width:180px"
           value="{{ request('fecha') }}" onchange="this.form.submit()">
  </form>

  <form action="{{ route('admin.reservas.liberar') }}" method="POST">
    @csrf
    <button type="submit" class="btn-add" style="background:var(--c-gold);color:#000;gap:0.4rem">
      <i class="bi bi-unlock-fill"></i> Liberar Vencidas
    </button>
  </form>
</div>

@if(session('success'))
  <div class="alert-tt-success mb-3">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
  </div>
@endif

<div class="adm-table-wrap reveal">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Recurso</th>
        <th>Cliente</th>
        <th>DNI</th>
        <th>Fecha</th>
        <th>Hora inicio</th>
        <th>Finaliza</th>
        <th>Duración</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($reservas as $r)
        @php
          $vencida = in_array($r->estado, ['confirmada','pendiente']) && now()->greaterThan($r->hora_fin);
        @endphp
        <tr {{ $vencida ? 'style=opacity:.7' : '' }}>
          <td>
            <span class="badge-tt {{ $r->recurso->tipo==='mesa'?'badge-warning':'badge-info' }}">
              {{ $r->recurso->tipo_label }} #{{ $r->recurso->numero }}
            </span>
          </td>
          <td style="font-weight:600">{{ $r->nombre }}</td>
          <td style="color:var(--c-muted)">{{ $r->dni }}</td>
          <td style="color:var(--c-muted)">{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
          <td>{{ $r->hora_inicio }}</td>
          <td style="color:var(--c-muted);font-size:0.82rem">
            {{ $r->hora_fin->format('H:i') }}
            @if($vencida)
              <span class="badge-tt badge-danger ms-1" style="font-size:0.7rem;padding:0.1rem 0.4rem">Vencida</span>
            @endif
          </td>
          <td><span class="badge-tt badge-info">{{ $r->duracion_label }}</span></td>
          <td>
            <span class="badge-tt {{ match($r->estado){
              'confirmada'=>'badge-success','cancelada'=>'badge-danger',
              'completada'=>'badge-info',default=>'badge-warning'} }}">
              {{ $r->estado_label }}
            </span>
          </td>
          <td>
            <form action="{{ route('admin.reservas.estado',$r) }}" method="POST" class="d-flex gap-1">
              @csrf @method('PATCH')
              <select name="estado" class="tt-input" style="font-size:0.75rem;padding:0.2rem 0.5rem;max-width:130px">
                @foreach(['pendiente','confirmada','cancelada','completada'] as $st)
                  <option value="{{ $st }}" {{ $r->estado===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn-add" style="font-size:0.75rem">OK</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="9" class="text-center py-4" style="color:var(--c-muted)">No hay reservas</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $reservas->links('pagination::bootstrap-5') }}</div>
@endsection
