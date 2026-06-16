<div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0.9rem;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid var(--c-border);font-size:0.82rem">
  <i class="bi bi-check2-circle flex-shrink-0" style="color:#86efac;font-size:1rem"></i>
  <div style="min-width:0;flex:1">
    <span style="font-weight:600;color:var(--c-text)">{{ $p->nombre_cliente }}</span>
    <span style="color:var(--c-muted)"> — Pedido #{{ $p->id }}</span>
    <span style="color:var(--c-muted);margin-left:0.4rem">·</span>
    <span style="color:var(--c-muted);margin-left:0.4rem">
      <i class="bi {{ $inc->tipo_icono }} me-1"></i>{{ $inc->tipo_label }}
    </span>
  </div>
  @if($inc->imagen_url)
    <a href="{{ $inc->imagen_url }}" target="_blank" title="Ver foto">
      <img src="{{ $inc->imagen_url }}" style="width:32px;height:32px;object-fit:cover;border-radius:6px;border:1px solid rgba(212,168,75,0.25);flex-shrink:0">
    </a>
  @endif
  <span style="color:var(--c-muted);white-space:nowrap;flex-shrink:0">{{ $inc->fecha->format('d/m/Y') }}</span>
</div>
