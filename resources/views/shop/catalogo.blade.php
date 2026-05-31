@extends('layouts.app')
@section('title', 'Catálogo')

@section('content')

<!-- HERO CATÁLOGO -->
<div class="catalogo-hero">
  @php
    $heroCatUrl = file_exists(public_path('images/coworking.jpg'))
      ? asset('images/catalogo.jpg')
      : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1920&q=80';
  @endphp
  <img src="{{ $heroCatUrl }}" alt="Catálogo" class="catalogo-hero-bg">
  <div class="catalogo-hero-overlay"></div>
  <div class="container catalogo-hero-content">
    <div class="section-label reveal" style="color:var(--c-gold)">Tierra y Taza</div>
    <h1 class="hero-title reveal" style="font-size:clamp(2.5rem,6vw,4.5rem);margin-bottom:0.75rem">
      Nuestra <em>Carta</em>
    </h1>
    <p class="reveal" style="color:rgba(255,255,255,0.65);max-width:480px;margin:0 auto;line-height:1.8;font-size:1.05rem">
      Descubre sabores únicos del Perú cafetalero
    </p>
  </div>
</div>

<div class="container" style="padding-bottom:5rem">

  <!-- FILTROS CON IMÁGENES -->
  @php
    $cats_data = [
      'todos'      => ['label'=>'Todos',            'archivo'=>null,            'fallback'=>null,                                                                                    'icon'=>'bi-grid-fill'],
      'calientes'  => ['label'=>'Bebidas Calientes', 'archivo'=>'calientes.jpg', 'fallback'=>'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=120&h=80&fit=crop',  'icon'=>'bi-cup-hot-fill'],
      'frias'      => ['label'=>'Bebidas Frías',     'archivo'=>'frias.jpg',     'fallback'=>'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=120&h=80&fit=crop',  'icon'=>'bi-droplet-fill'],
      'postres'    => ['label'=>'Postres',            'archivo'=>'postres.jpg',   'fallback'=>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=120&h=80&fit=crop',    'icon'=>'bi-cake2-fill'],
      'cafe_grano' => ['label'=>'Café en Grano',     'archivo'=>'cafe_grano.jpg','fallback'=>'https://images.unsplash.com/photo-1611854779393-1b2da9d400fe?w=120&h=80&fit=crop', 'icon'=>'bi-bag-fill'],
    ];
  @endphp

  <div class="cat-filtros-wrap reveal">
    @foreach($cats_data as $key => $cat)
      @php
        $imgUrl = null;
        if ($cat['archivo']) {
          $imgUrl = file_exists(public_path("images/categorias/{$cat['archivo']}"))
            ? asset("images/categorias/{$cat['archivo']}")
            : $cat['fallback'];
        }
      @endphp
      <a href="{{ route('catalogo', array_merge(request()->except('page'), ['categoria' => $key])) }}"
         class="cat-filtro-card {{ $categoria === $key ? 'activo' : '' }}">
        @if($imgUrl)
          <div class="cat-filtro-img">
            <img src="{{ $imgUrl }}" alt="{{ $cat['label'] }}">
            <div class="cat-filtro-img-overlay"></div>
          </div>
        @else
          <div class="cat-filtro-todos">
            <i class="bi {{ $cat['icon'] }}"></i>
          </div>
        @endif
        <span class="cat-filtro-label">{{ $cat['label'] }}</span>
      </a>
    @endforeach
  </div>

  <!-- BUSCADOR Y ORDEN -->
  <form action="{{ route('catalogo') }}" method="GET" class="catalogo-search reveal">
    <input type="hidden" name="categoria" value="{{ $categoria }}">
    <div class="search-input-wrap">
      <i class="bi bi-search search-icon"></i>
      <input type="text" name="busqueda" class="tt-input search-input"
             placeholder="Buscar producto..." value="{{ $busqueda }}">
    </div>
    <select name="orden" class="tt-input orden-select" onchange="this.form.submit()">
      <option value="rating"      {{ $orden==='rating'      ?'selected':'' }}>Mejor valorados</option>
      <option value="precio_asc"  {{ $orden==='precio_asc'  ?'selected':'' }}>Precio: menor a mayor</option>
      <option value="precio_desc" {{ $orden==='precio_desc' ?'selected':'' }}>Precio: mayor a menor</option>
      <option value="nombre"      {{ $orden==='nombre'      ?'selected':'' }}>Nombre A-Z</option>
    </select>
    <button type="submit" class="btn-primary-tt" style="padding:0.75rem 1.5rem;white-space:nowrap">
      <i class="bi bi-search"></i> Buscar
    </button>
  </form>

  <!-- INFO RESULTADOS -->
  <div class="d-flex justify-content-between align-items-center mb-4 reveal">
    <span style="color:var(--c-muted);font-size:0.85rem">
      <i class="bi bi-box-seam me-1"></i>
      {{ $productos->total() }} producto(s) encontrado(s)
    </span>
    @if($busqueda || $categoria !== 'todos')
      <a href="{{ route('catalogo') }}" class="btn-ghost-tt" style="padding:0.4rem 1rem;font-size:0.8rem">
        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
      </a>
    @endif
  </div>

  <!-- GRID PRODUCTOS -->
  @if($productos->isEmpty())
    <div class="text-center py-5 reveal">
      <div style="width:80px;height:80px;border-radius:20px;background:var(--glass);border:1px solid var(--c-border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2rem;color:var(--c-gold)">
        <i class="bi bi-cup-hot"></i>
      </div>
      <h4 style="font-weight:700;margin-bottom:0.5rem">No hay productos disponibles</h4>
      <p style="color:var(--c-muted)">Intenta con otros filtros</p>
      <a href="{{ route('catalogo') }}" class="btn-primary-tt mt-3 d-inline-flex">
        <i class="bi bi-grid-fill me-1"></i> Ver todos
      </a>
    </div>
  @else
    <div class="row g-4">
      @foreach($productos as $producto)
        <div class="col-sm-6 col-lg-4 col-xl-3 reveal">
          <div class="prod-card h-100">
            <div class="prod-img-wrap">
              <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
              <span class="prod-cat-badge">{{ $producto->categoria_label }}</span>
              @if(!$producto->hayStock())
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center">
                  <span class="badge-tt badge-danger" style="font-size:0.8rem;padding:0.4rem 1rem">
                    <i class="bi bi-x-circle me-1"></i> Agotado
                  </span>
                </div>
              @endif
            </div>
            <div class="prod-body">
              <div class="prod-name">{{ $producto->nombre }}</div>
              <div class="prod-desc">{{ mb_substr($producto->descripcion, 0, 70) }}</div>
              <div class="prod-footer">
                <div>
                  <div class="prod-price">S/ {{ number_format($producto->precio, 2) }}</div>
                  <div class="prod-rating">
                    <i class="bi bi-star-fill"></i> {{ number_format($producto->rating, 1) }}
                  </div>
                </div>
                {{-- BOTONES VER + AGREGAR AL CARRITO --}}
                <div class="d-flex gap-1">
                  <a href="{{ route('catalogo.show', $producto) }}" class="btn-add" style="font-size:0.75rem">
                    <i class="bi bi-eye"></i> Ver
                  </a>
                  @if($producto->hayStock())
                    <button class="btn-add js-add-cart"
                            data-url="{{ route('carrito.agregar', $producto) }}"
                            style="font-size:0.75rem;background:rgba(200,150,60,0.2);border-color:rgba(200,150,60,0.5)">
                      <i class="bi bi-bag-plus"></i> Agregar
                    </button>
                  @else
                    <span class="badge-tt badge-danger" style="font-size:0.7rem">Sin stock</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- PAGINACIÓN -->
    <div class="catalogo-paginacion reveal">
      {{ $productos->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
