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
