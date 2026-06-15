@extends('layouts.app')
@section('title','Editar Perfil')
@section('content')
<div class="container py-5" style="max-width:640px">
  <div class="page-header-tt reveal">
    <a href="{{ route('perfil.index') }}" class="btn-ghost-tt" style="padding:0.4rem 0.8rem;flex-shrink:0">
      <i class="bi bi-arrow-left"></i>
    </a>
    <div class="page-header-icon">
      <i class="bi bi-person-gear"></i>
    </div>
    <div>
      <h1 style="margin:0;font-size:1.5rem">Editar Perfil</h1>
      <span style="color:var(--c-muted);font-size:0.82rem">Actualiza tus datos personales</span>
    </div>
  </div>

  @if(session('success'))
    <div class="auth-alert-error mb-4"
         style="background:rgba(34,197,94,0.12);border-color:rgba(34,197,94,0.35);color:#86efac">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
  @endif

  <div class="pedido-detalle-card">
    <form action="{{ route('perfil.update') }}" method="POST">
      @csrf

      <div class="auth-field">
        <label class="tt-label">Nombre completo</label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-person tt-input-icon"></i>
          <input type="text" name="nombre" class="tt-input tt-input-padded"
                 value="{{ old('nombre', $user->nombre) }}" required>
        </div>
        @error('nombre')<div style="color:#f87171;font-size:0.8rem;margin-top:0.25rem">{{ $message }}</div>@enderror
      </div>

      <div class="auth-field">
        <label class="tt-label">Correo electrónico</label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-envelope tt-input-icon"></i>
          <input type="email" name="email" class="tt-input tt-input-padded"
                 value="{{ old('email', $user->email) }}" required>
        </div>
        @error('email')<div style="color:#f87171;font-size:0.8rem;margin-top:0.25rem">{{ $message }}</div>@enderror
      </div>

      <div class="auth-field">
        <label class="tt-label">DNI (8 dígitos)</label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-credit-card tt-input-icon"></i>
          <input type="text" name="dni" class="tt-input tt-input-padded"
                 value="{{ old('dni', $user->dni) }}" maxlength="8" required>
        </div>
        @error('dni')<div style="color:#f87171;font-size:0.8rem;margin-top:0.25rem">{{ $message }}</div>@enderror
      </div>

      <div class="divider-gold my-4"></div>
      <p style="color:var(--c-muted);font-size:0.82rem;margin-bottom:1rem">
        <i class="bi bi-info-circle me-1"></i>Deja en blanco si no deseas cambiar tu contraseña.
      </p>

      <div class="auth-field">
        <label class="tt-label">Contraseña actual <span style="color:#f87171">*</span></label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-shield-lock tt-input-icon"></i>
          <input type="password" name="password_actual" class="tt-input tt-input-padded"
                 placeholder="Requerida para cambiar contraseña" id="editPassActual">
          <button type="button" class="tt-pass-toggle" onclick="togglePass('editPassActual',this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
        @error('password_actual')<div style="color:#f87171;font-size:0.8rem;margin-top:0.25rem">{{ $message }}</div>@enderror
      </div>

      <div class="auth-field">
        <label class="tt-label">Nueva contraseña</label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-lock tt-input-icon"></i>
          <input type="password" name="password" class="tt-input tt-input-padded"
                 placeholder="Mínimo 8 caracteres" id="editPass">
          <button type="button" class="tt-pass-toggle" onclick="togglePass('editPass',this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
        @error('password')<div style="color:#f87171;font-size:0.8rem;margin-top:0.25rem">{{ $message }}</div>@enderror
      </div>

      <div class="auth-field">
        <label class="tt-label">Confirmar contraseña</label>
        <div class="tt-input-icon-wrap">
          <i class="bi bi-lock-fill tt-input-icon"></i>
          <input type="password" name="password_confirmation" class="tt-input tt-input-padded"
                 placeholder="Repite la nueva contraseña" id="editPass2">
          <button type="button" class="tt-pass-toggle" onclick="togglePass('editPass2',this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="auth-btn-submit mt-2">
        <i class="bi bi-save-fill me-2"></i>Guardar Cambios
      </button>
    </form>
  </div>
</div>
@push('scripts')
<script>
function togglePass(id, btn) {
  const input = document.getElementById(id);
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.querySelector('i').className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
@endsection