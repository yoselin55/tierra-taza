@extends('layouts.admin')
@section('title','Reclamos')
@section('page-title','Reclamos y Devoluciones')
@section('page-sub','Gestiona los reclamos de los clientes')

@section('content')

<div class="d-flex gap-2 mb-4 flex-wrap">
  <form method="GET" class="d-flex gap-2">
    <select name="estado" class="tt-input" style="max-width:200px" onchange="this.form.submit()">
      <option value="">Todos</option>
      @foreach(['abierta'=>'Abiertos','en_proceso'=>'En proceso','resuelta'=>'Resueltos'] as $k=>$v)
        <option value="{{ $k }}" {{ request('estado')===$k?'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
  </form>
</div>

@if(session('success'))
  <div class="alert-tt-success mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-3">
  @forelse($incidencias as $inc)
    @php $p = $inc->pedido; @endphp
    <div class="col-12 col-lg-6">
      <div class="pago-card reveal {{ $inc->estado === 'abierta' ? 'pendiente' : '' }}">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <div style="font-size:0.7rem;color:var(--c-muted)">Pedido #{{ $p->id }}</div>
            <div style="font-weight:700">{{ $p->nombre_cliente }}</div>
            <div style="font-size:0.8rem;color:var(--c-muted)">{{ $p->user->email ?? '' }}</div>
          </div>
          <div class="text-end">
            <span class="badge-tt {{ $inc->estado_badge }}">{{ $inc->estado_label }}</span>
          </div>
        </div>

        {{-- Tipo y descripción --}}
        <div class="pago-metodo-info mb-3">
          <div style="font-weight:700;margin-bottom:0.4rem">
            <i class="bi {{ $inc->tipo_icono }} me-1" style="color:var(--c-gold)"></i>
            {{ $inc->tipo_label }}
          </div>
          <p style="font-size:0.875rem;color:var(--c-text);margin:0;line-height:1.5">{{ $inc->descripcion }}</p>
        </div>

        {{-- Respuesta previa --}}
        @if($inc->respuesta)
          <div class="mb-3 px-3 py-2 rounded-2"
               style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);font-size:0.82rem">
            <i class="bi bi-reply-fill me-1" style="color:#86efac"></i>
            Respuesta: {{ $inc->respuesta }}
          </div>
        @endif

        {{-- Formulario de respuesta --}}
        @if($inc->estado !== 'resuelta')
          <form action="{{ route('admin.incidencias.responder', $inc) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-2">
              <textarea name="respuesta" class="tt-input" rows="2"
                        placeholder="Escribe tu respuesta al cliente..." required
                        style="font-size:0.82rem">{{ old('respuesta') }}</textarea>
            </div>
            <div class="d-flex gap-2">
              <select name="estado" class="tt-input" style="font-size:0.8rem;padding:0.35rem 0.6rem;max-width:150px">
                <option value="en_proceso">En proceso</option>
                <option value="resuelta">Marcar resuelta</option>
              </select>
              <button type="submit" class="btn-add flex-grow-1"
                      style="color:var(--c-gold);border-color:rgba(200,150,60,0.4);justify-content:center">
                <i class="bi bi-send-fill me-1"></i> Responder
              </button>
            </div>
          </form>
        @else
          <div style="font-size:0.8rem;color:var(--c-muted);text-align:center;padding-top:0.5rem">
            <i class="bi bi-check2-all me-1" style="color:#86efac"></i> Reclamo resuelto
          </div>
        @endif

        <div style="font-size:0.75rem;color:var(--c-muted);margin-top:0.75rem;text-align:right">
          {{ $inc->fecha->format('d/m/Y H:i') }}
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5" style="color:var(--c-muted)">
      <i class="bi bi-emoji-smile d-block mb-2" style="font-size:2.5rem;color:var(--c-gold)"></i>
      No hay reclamos pendientes.
    </div>
  @endforelse
</div>

<div class="mt-4">{{ $incidencias->links('pagination::bootstrap-5') }}</div>
@endsection