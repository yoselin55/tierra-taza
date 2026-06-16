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
      Respuesta anterior: {{ $inc->respuesta }}
    </div>
  @endif

  <form action="{{ route('admin.incidencias.responder', $inc) }}" method="POST">
    @csrf @method('PATCH')
    <input type="hidden" name="estado" id="estado-{{ $inc->id }}" value="en_proceso">
    <div class="mb-2">
      <textarea name="respuesta" class="tt-input" rows="2"
                placeholder="Escribe tu respuesta al cliente..." required
                style="font-size:0.82rem">{{ old('respuesta') }}</textarea>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      {{-- En proceso --}}
      <button type="submit"
              onclick="document.getElementById('estado-{{ $inc->id }}').value='en_proceso'"
              class="btn-add"
              style="color:#fbbf24;border-color:rgba(245,158,11,0.4);flex:1;justify-content:center;font-size:0.82rem">
        <i class="bi bi-hourglass-split me-1"></i> En proceso
      </button>
      {{-- Validar --}}
      <button type="submit"
              onclick="document.getElementById('estado-{{ $inc->id }}').value='validada'"
              class="btn-add"
              style="color:#86efac;border-color:rgba(34,197,94,0.4);flex:1;justify-content:center;font-size:0.82rem">
        <i class="bi bi-check-circle-fill me-1"></i> Validar
      </button>
      {{-- Rechazar --}}
      <button type="submit"
              onclick="document.getElementById('estado-{{ $inc->id }}').value='rechazada'"
              class="btn-add"
              style="color:#f87171;border-color:rgba(239,68,68,0.4);flex:1;justify-content:center;font-size:0.82rem">
        <i class="bi bi-x-circle-fill me-1"></i> Rechazar
      </button>
    </div>
  </form>

  <div style="font-size:0.75rem;color:var(--c-muted);margin-top:0.75rem;text-align:right">
    {{ $inc->fecha->format('d/m/Y H:i') }}
  </div>
</div>
