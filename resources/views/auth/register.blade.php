@extends('layouts.app')
@section('title','Registro')
@section('content')
<div class="auth-page">
  {{-- Panel visual izquierdo --}}
  <div class="auth-visual">
    <div class="auth-visual-orb1"></div>
    <div class="auth-visual-orb2"></div>
    <div class="auth-visual-content">
      <img src="{{ asset('images/logo.jpg') }}" alt="Tierra y Taza" class="auth-visual-logo">
      <div class="auth-visual-title">Tierra <em>y</em> Taza</div>
      <p class="auth-visual-sub">Únete a nuestra comunidad</p>
      <div class="auth-visual-pills">
        <span><i class="bi bi-bag-heart-fill me-1"></i>Pedidos online</span>
        <span><i class="bi bi-calendar3 me-1"></i>Reservas</span>
        <span><i class="bi bi-star-fill me-1"></i>Experiencias</span>
      </div>
    </div>
  </div>

  {{-- Formulario --}}
  <div class="auth-form-wrap">
    <div class="auth-box">
      <div class="auth-box-logo d-lg-none">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-logo-sm">
      </div>

      <div class="auth-title">Crea tu cuenta</div>
      <p class="auth-subtitle">Completa tus datos para unirte a Tierra y Taza.</p>

      @if($errors->any())
        <div class="auth-alert-error">
          @foreach($errors->all() as $e)
            <div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="auth-form">
        @csrf

        <div class="auth-field">
          <label class="tt-label">Nombre completo</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-person tt-input-icon"></i>
            <input type="text" name="nombre" class="tt-input tt-input-padded"
                   value="{{ old('nombre') }}" placeholder="Tu nombre" required autofocus>
          </div>
        </div>

        <div class="auth-field">
          <label class="tt-label">Correo electrónico</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-envelope tt-input-icon"></i>
            <input type="email" name="email" class="tt-input tt-input-padded"
                   value="{{ old('email') }}" placeholder="tu@email.com" required>
          </div>
        </div>

        <div class="auth-field">
          <label class="tt-label">DNI (8 dígitos)</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-credit-card tt-input-icon"></i>
            <input type="text" name="dni" class="tt-input tt-input-padded"
                   value="{{ old('dni') }}" placeholder="12345678" maxlength="8" required>
          </div>
        </div>

        <div class="auth-field">
          <label class="tt-label">Contraseña</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-lock tt-input-icon"></i>
            <input type="password" name="password" class="tt-input tt-input-padded"
                   placeholder="Mínimo 8 caracteres" required id="regPass">
            <button type="button" class="tt-pass-toggle" onclick="togglePass('regPass',this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="auth-field">
          <label class="tt-label">Confirmar contraseña</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-lock-fill tt-input-icon"></i>
            <input type="password" name="password_confirmation" class="tt-input tt-input-padded"
                   placeholder="Repite tu contraseña" required id="regPass2">
            <button type="button" class="tt-pass-toggle" onclick="togglePass('regPass2',this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="auth-btn-submit">
          <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
        </button>
      </form>

      <div class="divider-gold my-4"></div>
      <p style="text-align:center;color:var(--c-muted);font-size:0.875rem">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="auth-link">Inicia sesión</a>
      </p>
    </div>
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