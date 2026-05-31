@extends('layouts.admin')
@section('title','Productos')
@section('page-title','Gestión de Productos')
@section('page-sub','CRUD completo de la carta')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div></div>
  <a href="{{ route('admin.productos.create') }}" class="btn-primary-tt">
    <i class="bi bi-plus-lg"></i> Nuevo Producto
  </a>
</div>

<!-- Filtros -->
<form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
  <input type="text" name="busqueda" class="tt-input" style="max-width:240px"
         placeholder="Buscar producto..." value="{{ request('busqueda') }}">
  <select name="categoria" class="tt-input" style="max-width:200px" onchange="this.form.submit()">
    <option value="">Todas las categorías</option>
    @foreach(['calientes'=>'Bebidas Calientes','frias'=>'Bebidas Frías','postres'=>'Postres','cafe_grano'=>'Café en Grano'] as $k=>$v)
      <option value="{{ $k }}" {{ request('categoria')===$k?'selected':'' }}>{{ $v }}</option>
    @endforeach
  </select>
  <button type="submit" class="btn-primary-tt" style="padding:0.5rem 1.2rem">
    <i class="bi bi-search"></i>
  </button>
</form>

<div class="adm-table-wrap reveal">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Rating</th><th>Estado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($productos as $p)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="{{ $p->imagen_url }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover">
              <span style="font-weight:600">{{ $p->nombre }}</span>
            </div>
          </td>
          <td><span class="badge-tt badge-warning">{{ $p->categoria_label }}</span></td>
          <td style="color:var(--c-gold);font-weight:700">S/ {{ number_format($p->precio,2) }}</td>
          <td>
            <span class="badge-tt {{ $p->stock<5?'badge-danger':($p->stock<15?'badge-warning':'badge-success') }}">
              {{ $p->stock }} und.
            </span>
          </td>
          <td style="color:var(--c-gold)">
            <i class="bi bi-star-fill"></i> {{ number_format($p->rating,1) }}
          </td>
          <td>
            <span class="badge-tt {{ $p->estado?'badge-success':'badge-danger' }}">
              {{ $p->estado?'Activo':'Inactivo' }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('admin.productos.edit',$p) }}" class="btn-add" style="font-size:0.75rem">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.productos.destroy',$p) }}" method="POST"
                    onsubmit="return confirm('¿Desactivar producto?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-add" style="font-size:0.75rem;color:var(--c-red);border-color:rgba(255,61,113,0.3)">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center py-4" style="color:var(--c-muted)">No hay productos</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">
  {{ $productos->links('pagination::bootstrap-5') }}
</div>
@endsection
