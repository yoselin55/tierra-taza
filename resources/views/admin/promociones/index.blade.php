@extends('layouts.admin')
@section('title','Promociones')
@section('content')

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">Promociones</h1>
    <p class="adm-page-sub">Categorías de ofertas activas en el inicio</p>
  </div>
  <a href="{{ route('admin.promociones.create') }}" class="btn-primary-tt">
    <i class="bi bi-plus-lg"></i> Nueva Promoción
  </a>
</div>

@if(session('ok'))
  <div class="alert-tt alert-ok mb-4"><i class="bi bi-check-circle-fill"></i> {{ session('ok') }}</div>
@endif

<div class="adm-panel">
  <table class="adm-table w-100">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Color</th>
        <th>Productos</th>
        <th>Vigencia</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($promociones as $p)
        <tr>
          <td>
            <div style="font-weight:700">{{ $p->nombre }}</div>
            @if($p->descripcion)
              <div style="font-size:0.75rem;color:var(--c-muted)">{{ $p->descripcion }}</div>
            @endif
          </td>
          <td>
            <span style="display:inline-block;width:24px;height:24px;border-radius:6px;background:{{ $p->color }};border:1px solid rgba(255,255,255,0.15)"></span>
          </td>
          <td>
            <span class="badge-tt">{{ $p->productos_count }} productos</span>
          </td>
          <td style="font-size:0.8rem;color:var(--c-muted)">
            @if($p->fecha_inicio || $p->fecha_fin)
              {{ $p->fecha_inicio?->format('d/m/Y') ?? '—' }} → {{ $p->fecha_fin?->format('d/m/Y') ?? 'Sin fin' }}
            @else
              Sin límite de fecha
            @endif
          </td>
          <td>
            <button class="badge-tt {{ $p->activa ? 'badge-ok' : 'badge-muted' }} js-toggle-promo"
                    data-id="{{ $p->id }}"
                    data-activa="{{ $p->activa ? '1' : '0' }}"
                    style="border:none;cursor:pointer">
              {{ $p->activa ? 'Activa' : 'Inactiva' }}
            </button>
          </td>
          <td>
            <div class="d-flex gap-2 justify-content-end">
              <a href="{{ route('admin.promociones.edit', $p) }}" class="btn-ghost-tt" style="padding:0.35rem 0.75rem;font-size:0.8rem">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <form action="{{ route('admin.promociones.destroy', $p) }}" method="POST"
                    onsubmit="return confirm('¿Eliminar esta promoción? Los productos quedarán sin oferta.')">
                @csrf @method('DELETE')
                <button class="btn-danger-tt" style="padding:0.35rem 0.75rem;font-size:0.8rem">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--c-muted)">No hay promociones creadas aún.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@push('scripts')
<script>
document.querySelectorAll('.js-toggle-promo').forEach(function(btn) {
  btn.addEventListener('click', async function() {
    const id = btn.dataset.id;
    const resp = await fetch('/admin/promociones/' + id + '/toggle', {
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    });
    const d = await resp.json();
    btn.textContent = d.activa ? 'Activa' : 'Inactiva';
    btn.className = 'badge-tt ' + (d.activa ? 'badge-ok' : 'badge-muted') + ' js-toggle-promo';
    btn.dataset.activa = d.activa ? '1' : '0';
    window.toast && window.toast('Promoción ' + (d.activa ? 'activada' : 'desactivada'), 'ok');
  });
});
</script>
@endpush
@endsection
