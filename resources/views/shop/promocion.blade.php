@extends('layouts.app')
@section('title', $promocion->nombre)
@section('content')

<section class="section-sm">
  <div class="container">
    <!-- Header de la promoción -->
    <div class="text-center mb-5 reveal">
      <div class="section-label" style="color:{{ $promocion->color }}">Promoción Especial</div>
      <h1 class="section-title">{{ $promocion->nombre }}</h1>
      @if($promocion->descripcion)
        <p style="color:var(--c-muted);max-width:480px;margin:0.75rem auto 0">{{ $promocion->descripcion }}</p>
      @endif
      @if($promocion->fecha_fin)
        <div style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:1rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);padding:0.4rem 1rem;border-radius:99px;font-size:0.8rem;color:var(--c-muted)">
          <i class="bi bi-clock-fill" style="color:{{ $promocion->color }}"></i>
          Válida hasta el {{ $promocion->fecha_fin->format('d \d\e F \d\e Y') }}
        </div>
      @endif
    </div>

    <!-- Grid de productos -->
    @if($productos->isEmpty())
      <div class="text-center py-5" style="color:var(--c-muted)">
        <i class="bi bi-bag-x" style="font-size:3rem;opacity:0.4"></i>
        <p class="mt-3">No hay productos disponibles en esta promoción.</p>
      </div>
    @else
      <div class="row g-4 justify-content-center">
        @foreach($productos as $producto)
          <div class="col-sm-6 col-md-4 col-lg-3 d-flex">
            <div class="prod-card w-100" style="position:relative">
              @php $pct = $producto->precio > 0 ? round((1 - $producto->precio_oferta / $producto->precio) * 100) : 0; @endphp
              @if($pct > 0)
                <div class="oferta-badge">-{{ $pct }}% OFF</div>
              @endif
              <div class="prod-img-wrap">
                <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="prod-img">
              </div>
              <div class="prod-body">
                <h3 class="prod-name">{{ $producto->nombre }}</h3>
                <div class="prod-price-wrap">
                  <span class="prod-price" style="color:{{ $promocion->color }}">S/ {{ number_format($producto->precio_oferta, 2) }}</span>
                  <span class="precio-tachado">S/ {{ number_format($producto->precio, 2) }}</span>
                </div>
                <form action="{{ route('carrito.agregar', $producto) }}" method="POST" class="mt-2">
                  @csrf
                  <input type="hidden" name="cantidad" value="1">
                  <button type="submit" class="btn-primary-tt w-100" style="padding:0.55rem 1rem;font-size:0.85rem">
                    <i class="bi bi-bag-plus-fill"></i> Agregar
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="text-center mt-5">
      <a href="{{ route('home') }}" class="btn-ghost-tt">
        <i class="bi bi-arrow-left"></i> Volver al inicio
      </a>
    </div>
  </div>
</section>
@endsection
