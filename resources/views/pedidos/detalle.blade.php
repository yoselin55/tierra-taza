@extends('layouts.app')
@section('title', 'Pedido #' . $pedido->id)
@section('content')
<div class="container py-5">

  <div class="page-header-tt reveal">
    <a href="{{ route('pedidos.mis_pedidos') }}" class="btn-ghost-tt" style="padding:0.4rem 0.8rem;flex-shrink:0">
      <i class="bi bi-arrow-left"></i>
    </a>
    <div class="page-header-icon">
      <i class="bi bi-bag-fill"></i>
    </div>
    <div>
      <h1 style="margin:0;font-size:1.5rem">Pedido <span style="color:var(--c-gold)">#{{ $pedido->id }}</span></h1>
      <span class="badge-tt {{ $pedido->estado_badge }} mt-1">{{ $pedido->estado_label }}</span>
    </div>
  </div>

  @if(session('success'))
    <div class="auth-alert-error mb-4"
         style="background:rgba(34,197,94,0.12);border-color:rgba(34,197,94,0.35);color:#86efac">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
  @endif

  <div class="row g-4">
    <div class="col-lg-8">

      {{-- Estado del pedido --}}
      <div class="pedido-detalle-card mb-4">
        <h5 class="pedido-detalle-title"><i class="bi bi-signpost-2-fill me-2" style="color:var(--c-gold)"></i>Estado del Pedido</h5>
        <div class="progreso-pedido">
          @foreach([
            [1,'bi bi-clipboard-check-fill','Recibido'],
            [2,'bi bi-fire','Preparando'],
            [3,'bi bi-check-circle-fill','Listo'],
            [4,'bi bi-truck','En camino'],
            [5,'bi bi-geo-alt-fill','Cerca'],
            [6,'bi bi-house-fill','Entregado'],
          ] as [$paso,$icono,$label])
            <div class="progreso-paso {{ $pedido->estado_paso >= $paso ? ($pedido->estado_paso > $paso ? 'completado' : 'activo') : '' }}">
              <div class="progreso-circulo"><i class="{{ $icono }}"></i></div>
              <div class="progreso-label">{{ $label }}</div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Estado del pago --}}
      @if($pedido->pago)
        <div class="pedido-detalle-card mb-4">
          <h5 class="pedido-detalle-title"><i class="bi bi-wallet2 me-2" style="color:var(--c-gold)"></i>Estado del Pago</h5>
          <div class="d-flex align-items-center gap-3 flex-wrap">
            <span class="badge-tt {{ $pedido->pago->estado_badge }}" style="font-size:0.9rem">
              {{ $pedido->pago->estado_label }}
            </span>
            <span style="color:var(--c-muted);font-size:0.875rem">{{ $pedido->metodo_pago_label }}</span>
          </div>
          @if($pedido->pago->estado === 'completado')
            <div class="mt-3">
              <a href="{{ route('pedidos.comprobante', $pedido) }}" target="_blank"
                 class="btn-primary-tt" style="gap:0.5rem;background:rgba(34,197,94,0.12);border-color:rgba(34,197,94,0.35);color:#22c55e">
                <i class="bi bi-file-earmark-check"></i> Descargar Comprobante
              </a>
            </div>
          @elseif($pedido->pago->estado === 'pendiente')
            <div class="mt-3 p-3 rounded-2"
                 style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);font-size:0.85rem;color:#fbbf24">
              <i class="bi bi-clock-history me-1"></i>
              Tu pago está siendo verificado por el cajero. Recibirás confirmación pronto.
            </div>
          @elseif($pedido->pago->estado === 'rechazado')
            <div class="mt-3 p-3 rounded-2"
                 style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);font-size:0.85rem;color:#f87171">
              <i class="bi bi-x-circle me-1"></i>
              Tu pago fue rechazado.
              @if($pedido->pago->notas_cajero) Motivo: "{{ $pedido->pago->notas_cajero }}" @endif
            </div>
          @endif
        </div>
      @endif

      {{-- Productos --}}
      <div class="pedido-detalle-card mb-4">
        <h5 class="pedido-detalle-title"><i class="bi bi-bag-fill me-2" style="color:var(--c-gold)"></i>Productos</h5>
        @foreach($pedido->detalles as $d)
          <div class="d-flex gap-3 align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--c-border)">
            <img src="{{ $d->producto->imagen_url }}"
                 style="width:56px;height:56px;object-fit:cover;border-radius:8px;flex-shrink:0">
            <div class="flex-grow-1">
              <div style="font-weight:600">{{ $d->producto->nombre }}</div>
              <small style="color:var(--c-muted)">S/ {{ number_format($d->precio,2) }} × {{ $d->cantidad }}</small>
            </div>
            <span style="font-weight:700;color:var(--c-gold)">S/ {{ number_format($d->subtotal,2) }}</span>
          </div>
        @endforeach
        <div class="d-flex justify-content-between mt-2">
          <span style="font-weight:700;font-size:1.1rem">Total</span>
          <span class="precio-tag">S/ {{ number_format($pedido->total, 2) }}</span>
        </div>
      </div>

      {{-- Reclamos --}}
      <div class="pedido-detalle-card">
        <h5 class="pedido-detalle-title"><i class="bi bi-megaphone-fill me-2" style="color:var(--c-gold)"></i>Reclamos</h5>

        <div id="reclamos-container">
        @if($pedido->incidencias->isNotEmpty())
          @foreach($pedido->incidencias as $inc)
            <div class="reclamo-item mb-3 {{ $inc->estado }}">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div style="font-weight:700"><i class="bi {{ $inc->tipo_icono }} me-1"></i>{{ $inc->tipo_label }}</div>
                <span class="badge-tt {{ $inc->estado_badge }}">{{ $inc->estado_label }}</span>
              </div>
              <p style="font-size:0.875rem;margin-bottom:0.5rem">{{ $inc->descripcion }}</p>
              @if($inc->respuesta)
                <div class="reclamo-respuesta">
                  <i class="bi bi-reply-fill me-1" style="color:var(--c-gold)"></i>
                  <strong>Respuesta:</strong> {{ $inc->respuesta }}
                </div>
              @else
                <div style="font-size:0.8rem;color:var(--c-muted)">
                  <i class="bi bi-clock me-1"></i>En revisión por el cajero
                </div>
              @endif
            </div>
          @endforeach
          <div class="divider-gold my-3"></div>
        @endif
        </div>{{-- #reclamos-container --}}

        {{-- Formulario nuevo reclamo --}}
        @php $tieneAbierto = $pedido->incidencias->where('estado','abierta')->count() > 0; @endphp
        @if(!$tieneAbierto)
          <div id="reclamo-form-wrap">
          <form id="reclamo-form" action="{{ route('pedidos.incidencia', $pedido) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p style="color:var(--c-muted);font-size:0.875rem;margin-bottom:1rem">
              ¿Tuviste algún problema? Selecciona el tipo de reclamo:
            </p>
            <div class="row g-2 mb-3">
              @foreach([
                ['problema',   'bi-exclamation-triangle-fill','Problema',   'Producto en mal estado o faltante'],
                ['devolucion', 'bi-arrow-counterclockwise',   'Devolución', 'Solicitar reembolso de tu pago'],
                ['reenvio',    'bi-send-fill',                'Reenvío',    'Que te envíen el pedido de nuevo'],
              ] as [$val,$ico,$nom,$desc])
                <div class="col-12 col-sm-4">
                  <label class="reclamo-tipo-option">
                    <input type="radio" name="tipo" value="{{ $val }}" required
                           {{ old('tipo') === $val ? 'checked' : '' }}>
                    <i class="bi {{ $ico }}"></i>
                    <span class="fw-bold d-block">{{ $nom }}</span>
                    <small style="color:var(--c-muted)">{{ $desc }}</small>
                  </label>
                </div>
              @endforeach
            </div>
            <div class="mb-3">
              <label class="tt-label">Describe el problema</label>
              <textarea name="descripcion" class="tt-input" rows="3"
                        placeholder="Cuéntanos qué pasó con tu pedido..." required
                        style="resize:none">{{ old('descripcion') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="tt-label">
                <i class="bi bi-camera-fill me-1" style="color:var(--c-gold)"></i>
                Foto de evidencia <span style="color:#f87171">*</span>
              </label>
              <label id="img-label" for="reclamo-imagen"
                     style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.5rem;border:2px dashed rgba(212,168,75,0.35);border-radius:12px;padding:1.2rem;cursor:pointer;background:rgba(212,168,75,0.04);transition:border-color .2s">
                <i id="img-icon" class="bi bi-upload" style="font-size:1.6rem;color:var(--c-gold)"></i>
                <span id="img-hint" style="font-size:0.82rem;color:var(--c-muted)">Toca para seleccionar una foto</span>
                <img id="img-preview" src="" alt="" style="display:none;max-height:140px;border-radius:8px;object-fit:cover;max-width:100%">
              </label>
              <input type="file" id="reclamo-imagen" name="imagen" accept="image/*" required
                     style="display:none">
            </div>
            <button type="submit" class="btn-primary-tt">
              <i class="bi bi-send-fill"></i> Enviar Reclamo
            </button>
          </form>
          </div>{{-- #reclamo-form-wrap --}}
        @else
          <div style="text-align:center;padding:1rem;color:var(--c-muted);font-size:0.875rem">
            <i class="bi bi-hourglass-split me-1"></i>Ya tienes un reclamo abierto. Espera la respuesta del cajero.
          </div>
        @endif
      </div>
    </div>

    {{-- Sidebar del pedido --}}
    <div class="col-lg-4">
      <div class="pedido-detalle-card">
        <h6 style="font-weight:700;margin-bottom:1rem">Información del Pedido</h6>
        <div class="d-flex flex-column gap-2" style="font-size:0.875rem">
          @foreach([['Fecha',$pedido->fecha->format('d/m/Y H:i')],['Cliente',$pedido->nombre_cliente],['DNI',$pedido->dni_cliente],['Pago',$pedido->metodo_pago_label]] as [$lbl,$val])
            <div class="d-flex justify-content-between">
              <span style="color:var(--c-muted)">{{ $lbl }}</span>
              <span style="font-weight:600">{{ $val }}</span>
            </div>
          @endforeach
          <div class="d-flex justify-content-between">
            <span style="color:var(--c-muted)">Total</span>
            <span style="font-weight:700;color:var(--c-gold)">S/ {{ number_format($pedido->total,2) }}</span>
          </div>
        </div>
        <div class="divider-gold my-3"></div>
        <a href="{{ route('pedidos.boleta',$pedido) }}" class="btn-primary-tt w-100 justify-content-center">
          <i class="bi bi-file-earmark-text-fill"></i> Ver Boleta
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
  var pollUrl   = "{{ route('pedidos.incidencias_poll', $pedido) }}";
  var submitUrl = "{{ route('pedidos.incidencia', $pedido) }}";
  var csrf      = document.querySelector('meta[name="csrf-token"]').content;
  var container = document.getElementById('reclamos-container');
  var formWrap  = document.getElementById('reclamo-form-wrap');
  var form      = document.getElementById('reclamo-form');

  function renderInc(inc) {
    var div = document.createElement('div');
    div.className = 'reclamo-item mb-3 ' + inc.estado;
    var imgHtml = inc.imagen_url
      ? '<div style="margin-bottom:0.5rem"><img src="' + inc.imagen_url + '" style="max-height:100px;border-radius:8px;object-fit:cover;border:1px solid rgba(212,168,75,0.3)"></div>'
      : '';
    var respHtml = inc.respuesta
      ? '<div class="reclamo-respuesta"><i class="bi bi-reply-fill me-1" style="color:var(--c-gold)"></i><strong>Respuesta:</strong> ' + inc.respuesta + '</div>'
      : '<div style="font-size:0.8rem;color:var(--c-muted)"><i class="bi bi-clock me-1"></i>En revisión por el cajero</div>';
    div.innerHTML =
      '<div class="d-flex justify-content-between align-items-start mb-2">' +
        '<div style="font-weight:700"><i class="bi ' + inc.icono + ' me-1"></i>' + inc.tipo + '</div>' +
        '<span class="badge-tt ' + inc.estado_badge + '">' + inc.estado_label + '</span>' +
      '</div>' +
      '<p style="font-size:0.875rem;margin-bottom:0.5rem">' + inc.descripcion + '</p>' +
      imgHtml + respHtml;
    return div;
  }

  function renderIncidencias(list) {
    if (!list.length) return;
    container.innerHTML = '';
    list.forEach(function(inc) { container.appendChild(renderInc(inc)); });
    var hr = document.createElement('div');
    hr.className = 'divider-gold my-3';
    container.appendChild(hr);
  }

  function showAlert(msg, tipo) {
    var el = document.getElementById('reclamo-alert');
    if (!el) {
      el = document.createElement('div');
      el.id = 'reclamo-alert';
      el.style.cssText = 'margin-bottom:1rem;padding:0.75rem 1rem;border-radius:10px;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem';
      if (form) form.parentNode.insertBefore(el, form);
    }
    if (tipo === 'error') {
      el.style.background = 'rgba(239,68,68,0.12)';
      el.style.border = '1px solid rgba(239,68,68,0.35)';
      el.style.color = '#f87171';
      el.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> ' + msg;
    } else {
      el.style.background = 'rgba(34,197,94,0.12)';
      el.style.border = '1px solid rgba(34,197,94,0.35)';
      el.style.color = '#86efac';
      el.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + msg;
    }
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // Preview de imagen
  var imgInput   = document.getElementById('reclamo-imagen');
  var imgPreview = document.getElementById('img-preview');
  var imgHint    = document.getElementById('img-hint');
  var imgIcon    = document.getElementById('img-icon');
  if (imgInput) {
    imgInput.addEventListener('change', function() {
      var file = imgInput.files[0];
      if (!file) return;
      var reader = new FileReader();
      reader.onload = function(ev) {
        imgPreview.src = ev.target.result;
        imgPreview.style.display = 'block';
        imgIcon.style.display = 'none';
        imgHint.textContent = file.name;
      };
      reader.readAsDataURL(file);
    });
  }

  // Envío AJAX del formulario
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      var btn = form.querySelector('button[type=submit]');
      var original = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Enviando...';

      var fd = new FormData(form);
      fetch(submitUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: fd,
      })
      .then(function(r) {
        return r.json().then(function(data) { return { status: r.status, data: data }; });
      })
      .then(function(res) {
        if (res.status === 422) {
          // Errores de validación — mostrar el primero
          var errors = res.data.errors || {};
          var keys = Object.keys(errors);
          var msg = keys.length ? errors[keys[0]][0] : (res.data.message || 'Verifica los campos.');
          showAlert(msg, 'error');
          btn.disabled = false;
          btn.innerHTML = original;
          return;
        }
        if (res.data.ok) {
          // Insertar reclamo al instante
          var newInc = renderInc(res.data.inc);
          container.insertBefore(newInc, container.firstChild);
          var hr = document.createElement('div');
          hr.className = 'divider-gold my-3';
          container.appendChild(hr);
          // Mostrar mensaje de éxito y ocultar formulario
          showAlert('¡Reclamo enviado! El cajero lo revisará y te responderá pronto.', 'ok');
          if (formWrap) {
            formWrap.innerHTML =
              '<div style="text-align:center;padding:1rem;color:var(--c-muted);font-size:0.875rem">' +
              '<i class="bi bi-hourglass-split me-1"></i>Ya tienes un reclamo abierto. Espera la respuesta del cajero.' +
              '</div>';
          }
        } else {
          showAlert('No se pudo enviar el reclamo. Intenta de nuevo.', 'error');
          btn.disabled = false;
          btn.innerHTML = original;
        }
      })
      .catch(function() {
        showAlert('Error de conexión. Verifica tu internet e intenta de nuevo.', 'error');
        btn.disabled = false;
        btn.innerHTML = original;
      });
    });
  }

  // Polling cada 5 segundos para actualizar respuestas del cajero
  setInterval(function() {
    fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r) { return r.json(); })
      .then(function(d) { if (d.incidencias && d.incidencias.length) renderIncidencias(d.incidencias); })
      .catch(function() {});
  }, 5000);
})();
</script>
@endpush