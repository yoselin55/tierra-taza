@extends('layouts.app')
@section('title','Iniciar Sesión')
@section('content')
<div class="auth-page">
  {{-- Panel visual izquierdo --}}
  <div class="auth-visual">
    <div class="auth-visual-orb1"></div>
    <div class="auth-visual-orb2"></div>
    <div class="auth-visual-content">
      <img src="{{ asset('images/logo.jpg') }}" alt="Tierra y Taza" class="auth-visual-logo">
      <div class="auth-visual-title">Tierra <em>y</em> Taza</div>
      <p class="auth-visual-sub">Cafetería artesanal peruana</p>
      <div class="auth-visual-pills">
        <span><i class="bi bi-cup-hot-fill me-1"></i>Café de origen</span>
        <span><i class="bi bi-geo-alt-fill me-1"></i>Lima, Perú</span>
        <span><i class="bi bi-star-fill me-1"></i>Especialidad</span>
      </div>
    </div>
  </div>

  {{-- Formulario --}}
  <div class="auth-form-wrap">
    <div class="auth-box">
      {{-- Logo pequeño en móvil --}}
      <div class="auth-box-logo d-lg-none">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-logo-sm">
      </div>

      <div class="auth-title">Bienvenido de vuelta</div>
      <p class="auth-subtitle">Ingresa tus credenciales para continuar</p>

      @if($errors->any())
        <div class="auth-alert-error">
          <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="auth-form">
        @csrf
        <div class="auth-field">
          <label class="tt-label">Correo electrónico</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-envelope tt-input-icon"></i>
            <input type="email" name="email" class="tt-input tt-input-padded"
                   value="{{ old('email') }}" placeholder="tu@email.com" required autofocus>
          </div>
        </div>

        <div class="auth-field">
          <div class="d-flex justify-content-between mb-1">
            <label class="tt-label mb-0">Contraseña</label>
            <a href="#" style="color:var(--c-gold);font-size:0.75rem">¿Olvidaste tu contraseña?</a>
          </div>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-lock tt-input-icon"></i>
            <input type="password" name="password" class="tt-input tt-input-padded"
                   placeholder="••••••••" required id="loginPass">
            <button type="button" class="tt-pass-toggle" onclick="togglePass('loginPass',this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 mb-4">
          <input type="checkbox" name="remember" id="remember" style="accent-color:var(--c-gold);width:16px;height:16px">
          <label for="remember" style="font-size:0.85rem;color:var(--c-muted);cursor:pointer">Mantener sesión iniciada</label>
        </div>

        <button type="submit" class="auth-btn-submit">
          <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
        </button>
      </form>

      <div class="divider-gold my-4"></div>
      <p style="text-align:center;color:var(--c-muted);font-size:0.875rem">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" class="auth-link">Regístrate gratis</a>
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