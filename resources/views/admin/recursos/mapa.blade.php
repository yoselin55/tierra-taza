@extends('layouts.admin')
@section('title','Mesas & Coworking')
@section('page-title','Mapa de Espacios')
@section('page-sub','Clic en un espacio para cambiar su estado')

@section('content')

{{-- Toggle reservas --}}
<div class="toggle-reservas mb-4">
  <div>
    <div style="font-weight:700;font-size:0.95rem">Sistema de Reservas</div>
    <div style="font-size:0.8rem;color:var(--c-muted)" id="reservas-estado-txt">
      {{ $reservasHabilitadas ? 'Activo — los clientes pueden reservar' : 'Bloqueado — no se aceptan reservas' }}
    </div>
  </div>
  <div class="toggle-switch ms-auto" title="Activar / bloquear reservas">
    <input type="checkbox" id="toggleReservas" {{ $reservasHabilitadas ? 'checked' : '' }}>
    <label class="toggle-switch-label" for="toggleReservas"></label>
  </div>
  <span class="badge-tt {{ $reservasHabilitadas ? 'badge-success' : 'badge-danger' }}" id="reservas-badge">
    {{ $reservasHabilitadas ? 'Habilitado' : 'Bloqueado' }}
  </span>
</div>

{{-- Leyenda --}}
<div class="d-flex gap-3 mb-4 flex-wrap" style="font-size:0.82rem">
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:rgba(34,197,94,0.3);border:2px solid #22c55e;vertical-align:middle"></span> Disponible</span>
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:rgba(239,68,68,0.3);border:2px solid #ef4444;vertical-align:middle"></span> Ocupado</span>
  <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:rgba(245,158,11,0.3);border:2px solid #f59e0b;vertical-align:middle"></span> Mantenimiento</span>
  <span style="margin-left:auto;color:var(--c-muted)">Clic para cambiar estado</span>
</div>

{{-- Mesas --}}
<div class="mapa-seccion mb-4">
  <div class="d-flex align-items-center gap-2 mb-1">
    <i class="bi bi-table" style="color:var(--c-gold)"></i>
    <span style="font-weight:700">Mesas del Local</span>
    <span class="kanban-count" style="background:rgba(200,150,60,0.12);color:var(--c-gold)">{{ $mesas->count() }}</span>
  </div>
  <div style="font-size:0.78rem;color:var(--c-muted);margin-bottom:0.25rem">Área de consumo en tienda</div>
  <div class="mapa-grid" id="grid-mesas">
    @foreach($mesas as $m)
      <div class="mapa-espacio"
           data-id="{{ $m->id }}"
           data-tipo="mesa"
           data-estado="{{ $m->estado }}"
           title="Mesa #{{ $m->numero }} — {{ ucfirst($m->estado) }}">
        <i class="bi {{ $m->estado === 'mantenimiento' ? 'bi-cone-striped' : ($m->estado === 'ocupado' ? 'bi-person-fill' : 'bi-table') }}"></i>
        <span>#{{ $m->numero }}</span>
        <span style="font-size:0.65rem;font-weight:500">{{ $m->capacidad }}p</span>
        <div class="mapa-tooltip">{{ ucfirst($m->estado) }}</div>
      </div>
    @endforeach
  </div>
</div>

{{-- Coworking --}}
<div class="mapa-seccion">
  <div class="d-flex align-items-center gap-2 mb-1">
    <i class="bi bi-briefcase" style="color:var(--c-gold)"></i>
    <span style="font-weight:700">Espacios Coworking</span>
    <span class="kanban-count" style="background:rgba(200,150,60,0.12);color:var(--c-gold)">{{ $coworkings->count() }}</span>
  </div>
  <div style="font-size:0.78rem;color:var(--c-muted);margin-bottom:0.25rem">Zona de trabajo individual / grupal</div>
  <div class="mapa-grid" id="grid-coworking">
    @foreach($coworkings as $c)
      <div class="mapa-espacio"
           data-id="{{ $c->id }}"
           data-tipo="coworking"
           data-estado="{{ $c->estado }}"
           title="Coworking #{{ $c->numero }} — {{ ucfirst($c->estado) }}">
        <i class="bi {{ $c->estado === 'mantenimiento' ? 'bi-cone-striped' : ($c->estado === 'ocupado' ? 'bi-laptop-fill' : 'bi-briefcase') }}"></i>
        <span>#{{ $c->numero }}</span>
        <span style="font-size:0.65rem;font-weight:500">{{ $c->capacidad }}p</span>
        <div class="mapa-tooltip">{{ ucfirst($c->estado) }}</div>
      </div>
    @endforeach
  </div>
</div>

{{-- Modal de estado --}}
<div class="modal fade modal-tierra" id="modalEstado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:340px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEstadoTitle">Cambiar estado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p style="font-size:0.875rem;color:var(--c-muted);margin-bottom:1rem" id="modalEstadoSub"></p>
        <div class="d-flex flex-column gap-2" id="estadoOpciones">
          @foreach(['disponible'=>['Disponible','bi-check-circle','#22c55e'],'ocupado'=>['Ocupado','bi-person-fill','#ef4444'],'mantenimiento'=>['Mantenimiento','bi-cone-striped','#f59e0b']] as $val=>[$label,$icon,$color])
            <button class="btn-add js-set-estado w-100"
                    data-val="{{ $val }}"
                    style="justify-content:flex-start;gap:0.6rem;border-color:{{ $color }}33;color:{{ $color }}">
              <i class="bi {{ $icon }}"></i> {{ $label }}
            </button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const csrf = document.querySelector('meta[name=csrf-token]').content;
  let activeId = null;
  const modal  = new bootstrap.Modal(document.getElementById('modalEstado'));

  // Abrir modal al clic en espacio
  document.querySelectorAll('.mapa-espacio').forEach(el => {
    el.addEventListener('click', function () {
      activeId = this.dataset.id;
      const tipo   = this.dataset.tipo === 'mesa' ? 'Mesa' : 'Coworking';
      const num    = this.querySelector('span').textContent;
      document.getElementById('modalEstadoTitle').textContent = `${tipo} ${num}`;
      document.getElementById('modalEstadoSub').textContent   = `Estado actual: ${this.dataset.estado}`;
      modal.show();
    });
  });

  // Cambiar estado al elegir opción
  document.querySelectorAll('.js-set-estado').forEach(btn => {
    btn.addEventListener('click', async function () {
      const estado = this.dataset.val;
      modal.hide();
      try {
        const r = await fetch(`/admin/recursos/${activeId}/estado`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({ estado }),
        });
        const d = await r.json();
        if (d.success) {
          const el = document.querySelector(`.mapa-espacio[data-id="${activeId}"]`);
          if (el) {
            el.dataset.estado = estado;
            // Update icon
            const iconMap = { disponible: el.dataset.tipo === 'mesa' ? 'bi-table' : 'bi-briefcase', ocupado: el.dataset.tipo === 'mesa' ? 'bi-person-fill' : 'bi-laptop-fill', mantenimiento: 'bi-cone-striped' };
            el.querySelector('i').className = 'bi ' + iconMap[estado];
            el.querySelector('.mapa-tooltip').textContent = d.label;
          }
          window.toast?.('Estado actualizado: ' + d.label, 'ok');
        }
      } catch {
        window.toast?.('Error de red', 'err');
      }
    });
  });

  // Toggle reservas
  document.getElementById('toggleReservas').addEventListener('change', async function () {
    try {
      const r = await fetch('/admin/recursos/toggle-reservas', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: '{}',
      });
      const d = await r.json();
      if (d.success) {
        document.getElementById('reservas-estado-txt').textContent = d.habilitado
          ? 'Activo — los clientes pueden reservar'
          : 'Bloqueado — no se aceptan reservas';
        const badge = document.getElementById('reservas-badge');
        badge.textContent  = d.habilitado ? 'Habilitado' : 'Bloqueado';
        badge.className    = 'badge-tt ' + (d.habilitado ? 'badge-success' : 'badge-danger');
        window.toast?.(d.mensaje, 'ok');
      }
    } catch {
      window.toast?.('Error de red', 'err');
      this.checked = !this.checked; // revert
    }
  });
})();
</script>
@endpush
@endsection
