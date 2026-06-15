@extends('layouts.admin')
@section('title', isset($producto->id) ? 'Editar Producto' : 'Nuevo Producto')
@section('page-title', isset($producto->id) ? 'Editar Producto' : 'Nuevo Producto')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-panel admin-panel-body">
      <form action="{{ isset($producto->id) ? route('admin.productos.update',$producto) : route('admin.productos.store') }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($producto->id)) @method('PUT') @endif

        <div class="row g-3">
          <div class="col-md-8">
            <label class="tt-label">Nombre del producto</label>
            <input type="text" name="nombre" class="tt-input"
                   value="{{ old('nombre',$producto->nombre) }}" required>
          </div>
          <div class="col-md-4">
            <label class="tt-label">Categoría</label>
            <select name="categoria" class="tt-input" required>
              @foreach(['calientes'=>'Bebidas Calientes','frias'=>'Bebidas Frías','postres'=>'Postres','cafe_grano'=>'Café en Grano'] as $k=>$v)
                <option value="{{ $k }}" {{ old('categoria',$producto->categoria)===$k?'selected':'' }}>{{ $v }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12">
            <label class="tt-label">Descripción</label>
            <textarea name="descripcion" class="tt-input" rows="3"
                      style="resize:vertical">{{ old('descripcion',$producto->descripcion) }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="tt-label">Precio (S/)</label>
            <input type="number" name="precio" class="tt-input" step="0.01" min="0"
                   value="{{ old('precio',$producto->precio) }}" required>
          </div>
          <div class="col-md-4">
            <label class="tt-label">Stock</label>
            <input type="number" name="stock" class="tt-input" min="0"
                   value="{{ old('stock',$producto->stock ?? 0) }}" required>
          </div>
          <div class="col-md-4">
            <label class="tt-label">Estado</label>
            <select name="estado" class="tt-input">
              <option value="1" {{ old('estado',$producto->estado??1)?'selected':'' }}>Activo</option>
              <option value="0" {{ old('estado',$producto->estado??1)?'':'selected' }}>Inactivo</option>
            </select>
          </div>
          <div class="col-12">
            <label class="tt-label">Imagen del producto</label>
            @if(isset($producto->id) && $producto->imagen)
              <div class="mb-2">
                <img src="{{ $producto->imagen_url }}" style="height:80px;border-radius:8px;object-fit:cover">
              </div>
            @endif
            <input type="file" name="imagen" class="tt-input" accept="image/*">
            <small style="color:var(--c-muted);font-size:0.75rem">
              Si no subes imagen, se usará una de Unsplash según la categoría.
            </small>
          </div>
        </div>

          <!-- OFERTA -->
          <div class="col-12">
            <hr style="border-color:var(--c-border);margin:0.5rem 0 1rem">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" name="oferta_activa" id="oferta_activa" value="1"
                       role="switch"
                       {{ old('oferta_activa', $producto->oferta_activa ?? false) ? 'checked' : '' }}
                       onchange="document.getElementById('campos_oferta').style.display=this.checked?'block':'none'">
              </div>
              <label class="tt-label m-0" for="oferta_activa" style="cursor:pointer;font-size:0.95rem">
                <i class="bi bi-tag-fill" style="color:var(--c-gold)"></i> Activar Oferta
              </label>
            </div>

            <div id="campos_oferta" style="display:{{ old('oferta_activa', $producto->oferta_activa ?? false) ? 'block' : 'none' }}">
              <div class="row g-3">
                <div class="col-md-5">
                  <label class="tt-label">Nombre de la oferta</label>
                  <input type="text" name="nombre_oferta" class="tt-input"
                         placeholder="Ej: Promo Invierno, Oferta Especial…"
                         value="{{ old('nombre_oferta', $producto->nombre_oferta) }}">
                </div>
                <div class="col-md-3">
                  <label class="tt-label">Precio oferta (S/)</label>
                  <input type="number" name="precio_oferta" class="tt-input" step="0.01" min="0"
                         placeholder="0.00"
                         value="{{ old('precio_oferta', $producto->precio_oferta) }}">
                  <small style="color:var(--c-muted);font-size:0.72rem">Precio rebajado que verá el cliente</small>
                </div>
                <div class="col-md-4">
                  <label class="tt-label">Válida hasta</label>
                  <input type="date" name="oferta_hasta" class="tt-input"
                         min="{{ date('Y-m-d') }}"
                         value="{{ old('oferta_hasta', isset($producto->oferta_hasta) ? $producto->oferta_hasta->format('Y-m-d') : '') }}">
                  <small style="color:var(--c-muted);font-size:0.72rem">Dejar vacío si no tiene fecha límite</small>
                </div>
              </div>
            </div>
          </div>

        <div class="d-flex gap-3 mt-4">
          <button type="submit" class="btn-primary-tt">
            <i class="bi bi-{{ isset($producto->id)?'check-lg':'plus-lg' }}"></i>
            {{ isset($producto->id) ? 'Actualizar' : 'Crear' }} Producto
          </button>
          <a href="{{ route('admin.productos.index') }}" class="btn-ghost-tt">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
