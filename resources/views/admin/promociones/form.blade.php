@extends('layouts.admin')
@section('title', isset($promocion->id) ? 'Editar Promoción' : 'Nueva Promoción')
@section('content')

<div class="adm-page-header">
  <div>
    <h1 class="adm-page-title">{{ isset($promocion->id) ? 'Editar Promoción' : 'Nueva Promoción' }}</h1>
    <p class="adm-page-sub">Las promociones activas aparecen en la página de inicio</p>
  </div>
  <a href="{{ route('admin.promociones.index') }}" class="btn-ghost-tt">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</div>

<div class="adm-panel" style="max-width:640px">
  <div class="admin-panel-body p-4">
    <form action="{{ isset($promocion->id) ? route('admin.promociones.update', $promocion) : route('admin.promociones.store') }}"
          method="POST">
      @csrf
      @if(isset($promocion->id)) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Nombre de la promoción <span style="color:var(--c-red)">*</span></label>
          <input type="text" name="nombre" class="form-control" required maxlength="120"
                 value="{{ old('nombre', $promocion->nombre) }}"
                 placeholder="Ej: Oferta del Día, Promoción Navidad...">
        </div>

        <div class="col-12">
          <label class="form-label">Descripción (opcional)</label>
          <input type="text" name="descripcion" class="form-control" maxlength="255"
                 value="{{ old('descripcion', $promocion->descripcion) }}"
                 placeholder="Breve descripción que verán los clientes">
        </div>

        <div class="col-sm-6">
          <label class="form-label">Color del badge</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" name="color" class="form-control form-control-color"
                   value="{{ old('color', $promocion->color ?? '#D4A84B') }}"
                   style="width:56px;height:42px;padding:4px;cursor:pointer">
            <span style="font-size:0.8rem;color:var(--c-muted)">Color del badge en las cards</span>
          </div>
        </div>

        <div class="col-sm-6">
          <label class="form-label">Estado</label>
          <div class="d-flex align-items-center gap-2 mt-1">
            <input type="checkbox" name="activa" id="activa" class="form-check-input"
                   {{ old('activa', $promocion->activa) ? 'checked' : '' }}>
            <label for="activa" style="font-size:0.875rem">Activa (visible en el inicio)</label>
          </div>
        </div>

        <div class="col-sm-6">
          <label class="form-label">Fecha de inicio (opcional)</label>
          <input type="date" name="fecha_inicio" class="form-control"
                 value="{{ old('fecha_inicio', $promocion->fecha_inicio?->format('Y-m-d')) }}">
        </div>

        <div class="col-sm-6">
          <label class="form-label">Fecha de fin (opcional)</label>
          <input type="date" name="fecha_fin" class="form-control"
                 min="{{ date('Y-m-d') }}"
                 value="{{ old('fecha_fin', $promocion->fecha_fin?->format('Y-m-d')) }}">
        </div>

        <div class="col-12 pt-2">
          <div style="background:rgba(212,168,75,0.08);border:1px solid rgba(212,168,75,0.2);border-radius:var(--radius-sm);padding:0.75rem 1rem;font-size:0.8rem;color:var(--c-muted)">
            <i class="bi bi-info-circle-fill me-2" style="color:var(--c-gold)"></i>
            Después de crear la promoción, ve a <strong>Productos</strong> → edita cada producto → selecciona esta promoción y activa la oferta con su precio rebajado.
          </div>
        </div>

        <div class="col-12 d-flex gap-2 pt-2">
          <button type="submit" class="btn-primary-tt">
            <i class="bi bi-check-lg"></i> {{ isset($promocion->id) ? 'Guardar cambios' : 'Crear promoción' }}
          </button>
          <a href="{{ route('admin.promociones.index') }}" class="btn-ghost-tt">Cancelar</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
