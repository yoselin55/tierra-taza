<div class="pago-card reveal {{ $inc->estado === 'abierta' ? 'pendiente' : '' }}">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <div style="font-size:0.7rem;color:var(--c-muted)">Pedido #{{ $p->id }}</div>
      <div style="font-weight:700">{{ $p->nombre_cliente }}</div>
      <div style="font-size:0.8rem;color:var(--c-muted)">{{ $p->user->email ?? '' }}</div>
    </div>
    <span class="badge-tt {{ $inc->estado_badge }}">{{ $inc->estado_label }}</span>
  </div>

  <div class="pago-metodo-info mb-3">
    <div style="font-weight:700;margin-bottom:0.4rem">
      <i class="bi {{ $inc->tipo_icono }} me-1" style="color:var(--c-gold)"></i>
      {{ $inc->tipo_label }}
    </div>
    <p style="font-size:0.875rem;color:var(--c-text);margin:0;line-height:1.5">{{ $inc->descripcion }}</p>
  </div>

  @if($inc->imagen_url)
    <div class="mb-3">
      <a href="{{ $inc->imagen_url }}" target="_blank">
        <img src="{{ $inc->imagen_url }}" alt="Evidencia"
             style="max-height:130px;border-radius:8px;object-fit:cover;border:1px solid rgba(212,168,75,0.3);cursor:zoom-in">
      </a>
      <div style="font-size:0.75rem;color:var(--c-muted);margin-top:0.3rem">
        <i class="bi bi-camera-fill me-1" style="color:var(--c-gold)"></i>Foto de evidencia
      </div>
    </div>
  @else
    <div class="mb-3" style="font-size:0.78rem;color:rgba(248,113,113,0.8)">
      <i class="bi bi-camera-video-off me-1"></i>Sin foto de evidencia
    </div>
  @endif

  @if($inc->respuesta)
    <div class="mb-3 px-3 py-2 rounded-2"
         style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);font-size:0.82rem">
      <i class="bi bi-reply-fill me-1" style="color:#86efac"></i>
      Respuesta: {{ $inc->respuesta }}
    </div>
  @endif

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

  <div style="font-size:0.75rem;color:var(--c-muted);margin-top:0.75rem;text-align:right">
    {{ $inc->fecha->format('d/m/Y H:i') }}
  </div>
</div>
