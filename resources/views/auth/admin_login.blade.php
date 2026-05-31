@extends('layouts.app')
@section('title','Admin Login')
@section('content')

@php
$rolConfig = [
  'barista'              => ['color'=>'#3b82f6','ico'=>'bi bi-fire',           'title'=>'Barista / Cocinero'],
  'cajero'               => ['color'=>'#22c55e','ico'=>'bi bi-receipt',         'title'=>'Cajero'],
  'coordinador_delivery' => ['color'=>'#f59e0b','ico'=>'bi bi-truck',           'title'=>'Coord. Delivery'],
  'admin_sistema'        => ['color'=>'#a855f7','ico'=>'bi bi-hdd-stack-fill',  'title'=>'Admin del Sistema'],
  'admin_general'        => ['color'=>'#ef4444','ico'=>'bi bi-shield-fill',     'title'=>'Admin General'],
];
$rc = $rolConfig[$rol] ?? $rolConfig['admin_general'];
@endphp

<div class="auth-page">
  {{-- Panel visual --}}
  <div class="auth-visual" style="background:linear-gradient(135deg,rgba(13,13,13,0.85),rgba(13,13,13,0.5)),url('{{ asset('images/logo.jpg') }}') center/cover">
    <div class="auth-visual-content">
      <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-visual-logo" style="border-color:{{ $rc['color'] }}">
      <div class="auth-visual-title">Tierra &amp; Taza</div>
      <p class="auth-visual-sub">Panel de Administración</p>
      <div style="margin-top:1.5rem;padding:1rem 1.5rem;background:{{ $rc['color'] }}22;border:1px solid {{ $rc['color'] }}44;border-radius:1rem;display:inline-flex;align-items:center;gap:0.75rem">
        <i class="{{ $rc['ico'] }}" style="font-size:1.5rem;color:{{ $rc['color'] }}"></i>
        <span style="color:#fff;font-weight:600">{{ $rc['title'] }}</span>
      </div>
    </div>
  </div>

  {{-- Formulario --}}
  <div class="auth-form-wrap">
    <div class="auth-box">
      <div class="auth-box-logo d-lg-none">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="auth-logo-sm">
      </div>

      {{-- Icono de rol --}}
      <div class="auth-rol-badge" style="background:{{ $rc['color'] }}18;border:1px solid {{ $rc['color'] }}44;color:{{ $rc['color'] }}">
        <i class="{{ $rc['ico'] }}" style="font-size:1.6rem"></i>
      </div>

      <div class="auth-title">{{ $rc['title'] }}</div>
      <p class="auth-subtitle">Ingresa tus credenciales de administrador.</p>

      @if(session('error'))
        <div class="auth-alert-warn">
          <i class="bi bi-clock-history me-2"></i>{{ session('error') }}
        </div>
      @endif
      @if($errors->any())
        <div class="auth-alert-error">
          <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('admin.login.post') }}" method="POST" class="auth-form">
        @csrf
        <input type="hidden" name="rol" value="{{ $rol }}">

        <div class="auth-field">
          <label class="tt-label">Correo electrónico</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-envelope tt-input-icon"></i>
            <input type="email" name="email" class="tt-input tt-input-padded"
                   value="{{ old('email') }}" placeholder="admin@tierraytaza.pe" required autofocus>
          </div>
        </div>

        <div class="auth-field">
          <label class="tt-label">Contraseña</label>
          <div class="tt-input-icon-wrap">
            <i class="bi bi-lock tt-input-icon"></i>
            <input type="password" name="password" class="tt-input tt-input-padded"
                   placeholder="••••••••" required id="adminPass">
            <button type="button" class="tt-pass-toggle" onclick="togglePass('adminPass',this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="auth-btn-submit"
                style="background:linear-gradient(135deg,{{ $rc['color'] }},{{ $rc['color'] }}bb)">
          <i class="{{ $rc['ico'] }} me-2"></i>Ingresar como {{ $rc['title'] }}
        </button>
      </form>

      <div class="divider-gold my-4"></div>
      <a href="{{ route('admin.select_rol') }}"
         style="color:var(--c-muted);font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;text-decoration:none;transition:color 0.2s"
         onmouseover="this.style.color='var(--c-gold)'" onmouseout="this.style.color='var(--c-muted)'">
        <i class="bi bi-arrow-left"></i> Cambiar tipo de acceso
      </a>
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