@extends('layouts.admin')
@section('title','Inventario')
@section('page-title','Control de Inventario')
@section('page-sub','Gestión de stock y alertas')

@section('content')
<div class="adm-table-wrap reveal">
  <table class="adm-table">
    <thead>
      <tr><th>Producto</th><th>Categoría</th><th>Stock Actual</th><th>Estado</th><th>Actualizar Stock</th></tr>
    </thead>
    <tbody>
      @foreach($productos as $p)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="{{ $p->imagen_url }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
              <span style="font-weight:600">{{ $p->nombre }}</span>
            </div>
          </td>
          <td><span class="badge-tt badge-warning">{{ $p->categoria_label }}</span></td>
          <td>
            <span style="font-size:1.25rem;font-weight:800;color:{{ $p->stock<5?'var(--c-red)':($p->stock<15?'var(--c-amber)':'var(--c-green)') }}">
              {{ $p->stock }}
            </span>
            <span style="color:var(--c-muted);font-size:0.8rem"> und.</span>
          </td>
          <td>
            @if($p->stock === 0)
              <span class="badge-tt badge-danger"><i class="bi bi-x-circle-fill me-1"></i>Agotado</span>
            @elseif($p->stock < 5)
              <span class="badge-tt badge-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Stock crítico</span>
            @elseif($p->stock < 15)
              <span class="badge-tt badge-warning"><i class="bi bi-dash-circle-fill me-1"></i>Stock bajo</span>
            @else
              <span class="badge-tt badge-success"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>
            @endif
          </td>
          <td>
            <form action="{{ route('admin.inventario.stock',$p) }}" method="POST" class="d-flex gap-2">
              @csrf
              <input type="number" name="stock" class="tt-input" style="max-width:90px;padding:0.4rem 0.6rem"
                     value="{{ $p->stock }}" min="0">
              <button type="submit" class="btn-add" style="font-size:0.8rem">
                <i class="bi bi-arrow-repeat"></i> Update
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $productos->links('pagination::bootstrap-5') }}</div>
@endsection
