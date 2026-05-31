@extends('layouts.admin')
@section('title','Recursos')
@section('page-title','Gestión de Mesas y Coworking')
@section('page-sub','Administra el estado de cada espacio')

@section('content')

@if(session('success'))
  <div class="alert-tt-success mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
  </div>
@endif

{{-- Leyenda --}}
<div class="d-flex gap-3 mb-4 flex-wrap">
  <span class="badge-tt badge-success"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Disponible</span>
  <span class="badge-tt badge-danger"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Ocupado</span>
  <span class="badge-tt badge-warning"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Mantenimiento</span>
</div>

{{-- MESAS --}}
<h6 class="mb-3" style="font-weight:700;color:var(--c-gold);letter-spacing:0.05em;text-transform:uppercase;font-size:0.8rem">
  <i class="bi bi-cup-hot me-2"></i>Café — Mesas
</h6>
<div class="row g-3 mb-5">
  @forelse($mesas as $r)
    @php
      $color = match($r->estado) { 'disponible' => '#22c55e', 'ocupado' => '#ef4444', default => '#f59e0b' };
      $badgeClass = match($r->estado) { 'disponible' => 'badge-success', 'ocupado' => 'badge-danger', default => 'badge-warning' };
    @endphp
    <div class="col-6 col-sm-4 col-md-3 col-lg-2 reveal">
      <div class="recurso-admin-card" style="border-color:{{ $color }}22">
        <div class="recurso-admin-icon" style="color:{{ $color }};background:{{ $color }}15">
          <i class="bi bi-cup-hot-fill"></i>
        </div>
        <div style="font-weight:700;font-size:1rem">Mesa #{{ $r->numero }}</div>
        <div style="font-size:0.75rem;color:var(--c-muted);margin-bottom:0.75rem">
          <i class="bi bi-people me-1"></i>{{ $r->capacidad }} personas
        </div>
        <span class="badge-tt {{ $badgeClass }} mb-2">{{ ucfirst($r->estado) }}</span>
        <form action="{{ route('admin.recursos.estado', $r) }}" method="POST" class="mt-2">
          @csrf @method('PATCH')
          <select name="estado" class="tt-input mb-2" style="font-size:0.75rem;padding:0.3rem 0.5rem" onchange="this.form.submit()">
            @foreach(['disponible' => 'Disponible', 'ocupado' => 'Ocupado', 'mantenimiento' => 'Mantenimiento'] as $st => $stLabel)
              <option value="{{ $st }}" {{ $r->estado === $st ? 'selected' : '' }}>{{ $stLabel }}</option>
            @endforeach
          </select>
        </form>
      </div>
    </div>
  @empty
    <div class="col-12"><p style="color:var(--c-muted)">No hay mesas registradas.</p></div>
  @endforelse
</div>

{{-- COWORKING --}}
<h6 class="mb-3" style="font-weight:700;color:var(--c-gold);letter-spacing:0.05em;text-transform:uppercase;font-size:0.8rem">
  <i class="bi bi-laptop me-2"></i>Zona Coworking
</h6>
<div class="row g-3">
  @forelse($coworkings as $r)
    @php
      $color = match($r->estado) { 'disponible' => '#22c55e', 'ocupado' => '#ef4444', default => '#f59e0b' };
      $badgeClass = match($r->estado) { 'disponible' => 'badge-success', 'ocupado' => 'badge-danger', default => 'badge-warning' };
    @endphp
    <div class="col-6 col-sm-4 col-md-3 col-lg-2 reveal">
      <div class="recurso-admin-card" style="border-color:{{ $color }}22">
        <div class="recurso-admin-icon" style="color:{{ $color }};background:{{ $color }}15">
          <i class="bi bi-laptop"></i>
        </div>
        <div style="font-weight:700;font-size:1rem">CW #{{ $r->numero }}</div>
        <div style="font-size:0.75rem;color:var(--c-muted);margin-bottom:0.75rem">
          <i class="bi bi-people me-1"></i>{{ $r->capacidad }} personas
        </div>
        <span class="badge-tt {{ $badgeClass }} mb-2">{{ ucfirst($r->estado) }}</span>
        <form action="{{ route('admin.recursos.estado', $r) }}" method="POST" class="mt-2">
          @csrf @method('PATCH')
          <select name="estado" class="tt-input mb-2" style="font-size:0.75rem;padding:0.3rem 0.5rem" onchange="this.form.submit()">
            @foreach(['disponible' => 'Disponible', 'ocupado' => 'Ocupado', 'mantenimiento' => 'Mantenimiento'] as $st => $stLabel)
              <option value="{{ $st }}" {{ $r->estado === $st ? 'selected' : '' }}>{{ $stLabel }}</option>
            @endforeach
          </select>
        </form>
      </div>
    </div>
  @empty
    <div class="col-12"><p style="color:var(--c-muted)">No hay cubículos de coworking registrados.</p></div>
  @endforelse
</div>
@endsection