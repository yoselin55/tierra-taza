@php
  $esValidada  = $inc->estado === 'validada';
  $esRechazada = $inc->estado === 'rechazada';
  $iconoEstado = $esValidada ? 'bi-check2-circle' : ($esRechazada ? 'bi-x-circle-fill' : 'bi-check2-all');
  $colorEstado = $esValidada ? '#86efac' : ($esRechazada ? '#f87171' : '#86efac');
@endphp
<div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0.9rem;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid var(--c-border);font-size:0.82rem">
  <i class="bi {{ $iconoEstado }} flex-shrink-0" style="color:{{ $colorEstado }};font-size:1rem"></i>
  <div style="min-width:0;flex:1">
    <span style="font-weight:600;color:var(--c-text)">{{ $p->nombre_cliente }}</span>
    <span style="color:var(--c-muted)"> — Pedido #{{ $p->id }}</span>
    <span style="color:var(--c-muted);margin-left:0.4rem">·</span>
    <span style="color:var(--c-muted);margin-left:0.4rem">
      <i class="bi {{ $inc->tipo_icono }} me-1"></i>{{ $inc->tipo_label }}
    </span>
    @if($inc->respuesta)
      <span style="color:var(--c-muted);margin-left:0.4rem">·</span>
      <span style="color:var(--c-muted);margin-left:0.4rem;font-style:italic">"{{ \Str::limit($inc->respuesta, 60) }}"</span>
    @endif
  </div>
  <span class="badge-tt {{ $inc->estado_badge }}" style="font-size:0.7rem;flex-shrink:0">{{ $inc->estado_label }}</span>
  @if($inc->imagen_url)
    <a href="{{ $inc->imagen_url }}" target="_blank" title="Ver foto">
      <img src="{{ $inc->imagen_url }}" style="width:32px;height:32px;object-fit:cover;border-radius:6px;border:1px solid rgba(212,168,75,0.25);flex-shrink:0">
    </a>
  @endif
  <span style="color:var(--c-muted);white-space:nowrap;flex-shrink:0">{{ $inc->fecha->format('d/m/Y') }}</span>
</div>
