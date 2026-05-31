@extends('layouts.app')
@section('title','Mi Perfil')
@section('content')
<div class="container py-5">
  <div class="row g-4">

    {{-- ── Columna izquierda: foto + datos --}}
    <div class="col-lg-4">
      <div class="perfil-card text-center">
        {{-- Foto y cambiar --}}
        <div class="perfil-avatar-wrap">
          <img src="{{ $user->avatar_url }}" alt="avatar" class="perfil-avatar" id="avatarPreview">
          <form action="{{ route('perfil.foto') }}" method="POST" enctype="multipart/form-data" id="fotoForm">
            @csrf
            <label class="perfil-avatar-btn" for="fotoInput" title="Cambiar foto">
              <i class="bi bi-camera-fill"></i>
            </label>
            <input type="file" id="fotoInput" name="foto" accept="image/*" class="d-none"
                   onchange="previewFoto(this)">
          </form>
        </div>

        <h4 class="mt-3 mb-1" style="font-weight:700">{{ $user->nombre }}</h4>
        <div style="color:var(--c-muted);font-size:0.875rem">{{ $user->email }}</div>
        <span class="badge-tt badge-warning mt-2 d-inline-block">{{ $user->rol_label }}</span>

        <div class="divider-gold my-4"></div>

        <div class="d-flex flex-column gap-2 text-start" style="font-size:0.875rem">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-credit-card" style="color:var(--c-gold);width:20px"></i>
            <span style="color:var(--c-muted)">DNI:</span>
            <span style="font-weight:600">{{ $user->dni }}</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar3" style="color:var(--c-gold);width:20px"></i>
            <span style="color:var(--c-muted)">Miembro desde:</span>
            <span style="font-weight:600">{{ $user->created_at->format('M Y') }}</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bag-check" style="color:var(--c-gold);width:20px"></i>
            <span style="color:var(--c-muted)">Pedidos:</span>
            <span style="font-weight:600">{{ $user->pedidos()->count() }}</span>
          </div>
        </div>

        <a href="{{ route('perfil.edit') }}" class="auth-btn-submit mt-4" style="font-size:0.875rem">
          <i class="bi bi-pencil-fill me-2"></i>Editar Perfil
        </a>
      </div>
    </div>

    {{-- ── Columna derecha: últimos pedidos --}}
    <div class="col-lg-8">
      @if(session('success'))
        <div class="auth-alert-error mb-3"
             style="background:rgba(34,197,94,0.12);border-color:rgba(34,197,94,0.35);color:#86efac">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
      @endif

      <div style="background:var(--c-surface);border:1px solid var(--c-border);border-radius:var(--radius);padding:1.5rem">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 style="font-weight:700;margin:0">Mis Últimos Pedidos</h5>
          <a href="{{ route('pedidos.mis_pedidos') }}" style="color:var(--c-gold);font-size:0.875rem;text-decoration:none">
            Ver todos <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        @forelse($pedidos as $p)
          <div class="perfil-pedido-row">
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <div style="font-size:0.75rem;color:var(--c-muted)">#{{ $p->id }}</div>
              <div>
                <div style="font-weight:600;font-size:0.875rem">{{ $p->fecha->format('d/m/Y H:i') }}</div>
                <div style="font-size:0.75rem;color:var(--c-muted)">{{ $p->metodo_pago_label }}</div>
              </div>
              <span class="badge-tt {{ $p->estado_badge }}" style="margin-left:auto">{{ $p->estado_label }}</span>
              <span style="color:var(--c-gold);font-weight:700">S/ {{ number_format($p->total,2) }}</span>
              <a href="{{ route('pedidos.detalle',$p) }}" class="btn-add" style="font-size:0.75rem">
                <i class="bi bi-eye"></i>
              </a>
            </div>
            {{-- Pago pendiente de validación --}}
            @if($p->pago && $p->pago->estado === 'pendiente')
              <div class="mt-2 px-3 py-2 rounded-2"
                   style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);font-size:0.8rem;color:#fbbf24">
                <i class="bi bi-clock-history me-1"></i>
                Pago pendiente de validación por el cajero.
              </div>
            @elseif($p->pago && $p->pago->estado === 'rechazado')
              <div class="mt-2 px-3 py-2 rounded-2"
                   style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);font-size:0.8rem;color:#f87171">
                <i class="bi bi-x-circle me-1"></i>
                Pago rechazado.
                @if($p->pago->notas_cajero) "{{ $p->pago->notas_cajero }}" @endif
              </div>
            @endif
          </div>
        @empty
          <div class="text-center py-5" style="color:var(--c-muted)">
            <i class="bi bi-bag-x d-block mb-2" style="font-size:2rem"></i>
            Aún no tienes pedidos.
            <a href="{{ route('catalogo') }}" style="color:var(--c-gold)">Ir al catálogo</a>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('avatarPreview').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    // Auto-submit
    document.getElementById('fotoForm').submit();
  }
}
</script>
@endpush
@endsection