@extends('layouts.app')
@section('title', 'Reservas')

@section('content')
<div class="container py-5">
  <div class="text-center mb-5 reveal">
    <h1 class="display-5 fw-bold">Reserva tu <span style="color:var(--c-gold)">Espacio</span></h1>
    <p class="lead" style="color:var(--c-muted)">Selecciona una mesa o cubículo en el mapa interactivo</p>
  </div>

  <!-- Leyenda -->
  <div class="d-flex gap-4 justify-content-center mb-5 flex-wrap">
    <div class="d-flex align-items-center gap-2">
      <div class="leyenda-dot disponible-dot"></div>
      <span style="font-size:0.875rem;font-weight:500">Disponible</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <div class="leyenda-dot ocupado-dot"></div>
      <span style="font-size:0.875rem;font-weight:500">Ocupado</span>
    </div>
  </div>

  <!-- MAPA INTERACTIVO -->
  <div class="mapa-cafeteria mb-5">

    <!-- ══ ZONA CAFÉ ══ -->
    <div class="zona-divider-banner">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
      ZONA CAFÉ — MESAS
    </div>

    <div class="zona-section">
      <div class="zona-header">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        Zona Café — Mesas
      </div>

      <div class="zona-split">
        <!-- Grid de mesas -->
        <div class="zona-grid-col">
          <div class="recurso-grid">
            @foreach($mesas as $mesa)
              <button class="recurso-card {{ $mesa->estado === 'disponible' ? 'disponible' : 'ocupado' }}"
                      id="recurso-{{ $mesa->id }}"
                      data-id="{{ $mesa->id }}"
                      data-tipo="{{ $mesa->tipo }}"
                      data-numero="{{ $mesa->numero }}"
                      data-capacidad="{{ $mesa->capacidad }}"
                      data-estado="{{ $mesa->estado }}"
                      {{ $mesa->estado !== 'disponible' ? 'disabled' : '' }}
                      @if($mesa->estado === 'disponible') onclick="abrirModal(this)" @endif>
                <div class="recurso-card-icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 5h18M3 5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2M3 5l2 12M21 5l-2 12M9 17v2M15 17v2M8 19h8"/>
                  </svg>
                </div>
                <div class="recurso-card-info">
                  <span class="recurso-card-num">Mesa {{ $mesa->numero }}</span>
                  <span class="recurso-card-cap">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $mesa->capacidad }} pers.
                  </span>
                </div>
                <div class="recurso-card-status">
                  @if($mesa->estado === 'disponible')
                    <span class="status-pill disponible-pill">Libre</span>
                  @else
                    <span class="status-pill ocupado-pill">Ocupada</span>
                  @endif
                </div>
              </button>
            @endforeach
          </div>
        </div>

        <!-- Imagen zona café -->
        <div class="zona-img-col">
          <div class="zona-img-card" onclick="abrirLightbox('{{ asset('images/mesas.png') }}', 'Área de Mesas — Zona Café')">
            <img src="{{ asset('images/mesas.png') }}" alt="Zona Café" class="zona-img-photo">
            <div class="zona-img-overlay">
              <div class="zona-img-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                <span>Área de Mesas</span>
              </div>
              <div class="zona-img-expand">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                Ver imagen
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Separador -->
    <div class="zona-divider-banner">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      ZONA COWORKING
    </div>

    <!-- ══ ZONA COWORKING ══ -->
    <div class="zona-section">
      <div class="zona-header">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        Zona Coworking — Cubículos
      </div>

      <div class="zona-split zona-split--reverse">
        <!-- Imagen zona coworking -->
        <div class="zona-img-col">
          <div class="zona-img-card" onclick="abrirLightbox('{{ asset('images/coworking2.png') }}', 'Área Coworking — Cubículos')">
            <img src="{{ asset('images/coworking2.png') }}" alt="Zona Coworking" class="zona-img-photo">
            <div class="zona-img-overlay">
              <div class="zona-img-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <span>Área Coworking</span>
              </div>
              <div class="zona-img-expand">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                Ver imagen
              </div>
            </div>
          </div>
        </div>

        <!-- Grid de cubículos -->
        <div class="zona-grid-col">
          <div class="recurso-grid">
            @foreach($coworkings as $coworking)
              <button class="recurso-card {{ $coworking->estado === 'disponible' ? 'disponible' : 'ocupado' }}"
                      id="recurso-{{ $coworking->id }}"
                      data-id="{{ $coworking->id }}"
                      data-tipo="{{ $coworking->tipo }}"
                      data-numero="{{ $coworking->numero }}"
                      data-capacidad="{{ $coworking->capacidad }}"
                      data-estado="{{ $coworking->estado }}"
                      {{ $coworking->estado !== 'disponible' ? 'disabled' : '' }}
                      @if($coworking->estado === 'disponible') onclick="abrirModal(this)" @endif>
                <div class="recurso-card-icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                    <line x1="8" y1="21" x2="16" y2="21"/>
                    <line x1="12" y1="17" x2="12" y2="21"/>
                  </svg>
                </div>
                <div class="recurso-card-info">
                  <span class="recurso-card-num">Cub. {{ $coworking->numero }}</span>
                  <span class="recurso-card-cap">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $coworking->capacidad }} pers.
                  </span>
                </div>
                <div class="recurso-card-status">
                  @if($coworking->estado === 'disponible')
                    <span class="status-pill disponible-pill">Libre</span>
                  @else
                    <span class="status-pill ocupado-pill">Ocupado</span>
                  @endif
                </div>
              </button>
            @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- LIGHTBOX -->
<div class="lightbox-backdrop" id="lightboxBackdrop" onclick="cerrarLightbox()">
  <div class="lightbox-content" onclick="event.stopPropagation()">
    <button class="lightbox-close" onclick="cerrarLightbox()">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img src="" alt="" id="lightboxImg" class="lightbox-img">
    <div class="lightbox-caption" id="lightboxCaption"></div>
  </div>
</div>

<!-- MODAL RESERVA -->
<div class="modal fade modal-tierra" id="modalReserva" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalTitulo">Reservar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        @guest
          <div class="text-center py-4">
            <div class="page-empty-icon mx-auto mb-3">
              <i class="bi bi-lock-fill"></i>
            </div>
            <h5 class="fw-bold mb-1">Inicia sesión para reservar</h5>
            <p style="color:var(--c-muted);font-size:0.875rem;margin-bottom:1.5rem">Necesitas una cuenta para hacer una reserva</p>
            <div class="d-flex gap-2 justify-content-center">
              <a href="{{ route('login') }}" class="btn-primary-tt">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
              </a>
              <a href="{{ route('register') }}" class="btn-ghost-tt">
                <i class="bi bi-person-plus"></i> Registrarse
              </a>
            </div>
          </div>
        @else
          <form id="formReserva">
            @csrf
            <input type="hidden" id="recurso_id" name="recurso_id">

            <div class="mb-3">
              <label class="tt-label">Nombre completo</label>
              <div class="tt-input-icon-wrap">
                <i class="bi bi-person tt-input-icon"></i>
                <input type="text" name="nombre" id="r_nombre" class="tt-input tt-input-padded"
                       value="{{ auth()->user()->nombre }}" required placeholder="Tu nombre completo">
              </div>
            </div>

            <div class="mb-3">
              <label class="tt-label">DNI</label>
              <div class="tt-input-icon-wrap">
                <i class="bi bi-credit-card tt-input-icon"></i>
                <input type="text" name="dni" id="r_dni" class="tt-input tt-input-padded"
                       value="{{ auth()->user()->dni }}" maxlength="8" required placeholder="12345678">
              </div>
            </div>

            <div class="mb-3">
              <label class="tt-label">Fecha</label>
              <div class="tt-input-icon-wrap">
                <i class="bi bi-calendar3 tt-input-icon"></i>
                <input type="date" name="fecha" id="r_fecha" class="tt-input tt-input-padded" required
                       min="{{ date('Y-m-d') }}">
              </div>
            </div>

            <div class="mb-3">
              <label class="tt-label">Hora de inicio</label>
              <div class="tt-input-icon-wrap">
                <i class="bi bi-clock tt-input-icon"></i>
                <select name="hora_inicio" id="r_hora" class="tt-input tt-input-padded" required>
                  @foreach(['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'] as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3" id="campo_personas" style="display:none">
              <label class="tt-label">Número de personas</label>
              <div class="tt-input-icon-wrap">
                <i class="bi bi-people tt-input-icon"></i>
                <input type="number" name="personas" id="r_personas" class="tt-input tt-input-padded"
                       min="1" max="10" value="2" placeholder="2">
              </div>
            </div>

            <div class="mb-4" id="campo_duracion">
              <label class="tt-label mb-2">Duración</label>
              <div class="row g-2">
                @foreach([['1h','1 hora'],['4h','4 horas'],['dia','Día completo']] as [$val, $lab])
                  <div class="col-4">
                    <label class="pago-option text-center w-100 py-2">
                      <input type="radio" name="duracion" value="{{ $val }}" {{ $val === '1h' ? 'checked' : '' }}>
                      <div style="font-weight:700;font-size:0.85rem">{{ $lab }}</div>
                    </label>
                  </div>
                @endforeach
              </div>
            </div>

            <button type="submit" class="btn-primary-tt w-100 justify-content-center mt-1" id="btnReservar"
                    style="border-radius:var(--radius-sm);padding:0.95rem">
              <i class="bi bi-calendar-check-fill me-2"></i>Confirmar Reserva
            </button>
          </form>
        @endguest
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Mover modal y lightbox al <body> para evitar conflictos de stacking context
  document.addEventListener('DOMContentLoaded', function() {
    const m = document.getElementById('modalReserva');
    const lb = document.getElementById('lightboxBackdrop');
    if (m)  document.body.appendChild(m);
    if (lb) document.body.appendChild(lb);
  });

  const ttIsGuest = @json(auth()->guest());
  const ttLoginUrl = '{{ route("login") }}';

  let recursoActual = null;
  const modal = new bootstrap.Modal(document.getElementById('modalReserva'));

  function abrirModal(btn) {
    if (ttIsGuest) {
      window.toast('Inicia sesión para reservar una mesa o cubículo', 'info');
      setTimeout(function() { window.location.href = ttLoginUrl; }, 1800);
      return;
    }

    recursoActual = btn;
    const tipo   = btn.dataset.tipo;
    const numero = btn.dataset.numero;

    document.getElementById('recurso_id').value = btn.dataset.id;
    document.getElementById('modalTitulo').textContent =
      (tipo === 'mesa' ? 'Reservar Mesa ' : 'Reservar Cubículo ') + numero;

    document.getElementById('campo_personas').style.display = tipo === 'mesa' ? 'block' : 'none';
    document.getElementById('campo_duracion').style.display = 'block';
    document.getElementById('r_fecha').value = new Date().toISOString().split('T')[0];

    modal.show();
  }

  // Lightbox
  function abrirLightbox(src, caption) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxCaption').textContent = caption;
    const lb = document.getElementById('lightboxBackdrop');
    lb.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function cerrarLightbox() {
    document.getElementById('lightboxBackdrop').classList.remove('active');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });

  // Submit AJAX
  document.getElementById('formReserva')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnReservar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Reservando...';

    const formData = new FormData(this);
    const data = {};
    formData.forEach((v, k) => data[k] = v);

    try {
      const resp = await fetch('/reservas', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      });

      const result = await resp.json();

      if (result.success) {
        if (recursoActual) {
          recursoActual.classList.remove('disponible');
          recursoActual.classList.add('ocupado');
          recursoActual.disabled = true;
          recursoActual.removeAttribute('onclick');
          const pill = recursoActual.querySelector('.status-pill');
          if (pill) { pill.textContent = 'Ocupada'; pill.className = 'status-pill ocupado-pill'; }
        }
        modal.hide();
        window.toast(result.message, 'ok');
      } else {
        window.toast(result.error || 'Error al reservar', 'err');
      }
    } catch (err) {
      window.toast('Error de conexión. Intenta de nuevo.', 'err');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-calendar-check-fill me-2"></i>Confirmar Reserva';
    }
  });

  document.querySelectorAll('[name="duracion"]').forEach(r => {
    r.closest('.pago-option')?.addEventListener('click', function() {
      document.querySelectorAll('.pago-option').forEach(o => o.classList.remove('selected'));
      this.classList.add('selected');
      r.checked = true;
    });
  });

  setInterval(async () => {
    try {
      const resp = await fetch('/reservas/estado');
      const recursos = await resp.json();
      recursos.forEach(r => {
        const btn = document.getElementById(`recurso-${r.id}`);
        if (btn && btn.dataset.estado !== r.estado) {
          btn.dataset.estado = r.estado;
          const pill = btn.querySelector('.status-pill');
          if (r.estado === 'ocupado') {
            btn.classList.remove('disponible'); btn.classList.add('ocupado');
            btn.disabled = true;
            if (pill) { pill.textContent = 'Ocupado'; pill.className = 'status-pill ocupado-pill'; }
          } else {
            btn.classList.remove('ocupado'); btn.classList.add('disponible');
            btn.disabled = false; btn.setAttribute('onclick', 'abrirModal(this)');
            if (pill) { pill.textContent = 'Libre'; pill.className = 'status-pill disponible-pill'; }
          }
        }
      });
    } catch(e) {}
  }, 30000);
</script>
@endpush
@endsection