@extends('layouts.app')
@section('title','Inicio')
@section('content')

@push('splash')
<div id="splash" style="position:fixed;inset:0;z-index:99999;background:#0D0D0D;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity 0.8s ease,transform 0.8s ease">
  <div class="splash-ring">
    <img src="{{ asset('images/logo.jpg') }}"
         style="width:160px;height:160px;object-fit:contain;border-radius:32px;animation:splashPulse 1.8s ease infinite">
  </div>
  <div class="splash-dots">
    <span></span><span></span><span></span>
  </div>
</div>
<style>
.splash-ring {
  position:relative;
  padding:12px;
  border-radius:44px;
  background:rgba(200,150,60,0.08);
  border:1px solid rgba(200,150,60,0.2);
  margin-bottom:2rem;
  box-shadow:0 0 60px rgba(200,150,60,0.15);
}
.splash-dots {
  display:flex;gap:10px;
}
.splash-dots span {
  width:10px;height:10px;border-radius:50%;
  background:var(--c-gold,#C8963C);
}
.splash-dots span:nth-child(1){animation:splashDot 1.2s ease infinite 0s}
.splash-dots span:nth-child(2){animation:splashDot 1.2s ease infinite 0.2s}
.splash-dots span:nth-child(3){animation:splashDot 1.2s ease infinite 0.4s}
@keyframes splashPulse {
  0%,100%{transform:scale(1);}
  50%{transform:scale(1.05);}
}
@keyframes splashDot {
  0%,100%{opacity:0.3;transform:translateY(0);}
  50%{opacity:1;transform:translateY(-8px);}
}
</style>
<script>
window.addEventListener('load',()=>{
  setTimeout(()=>{
    const s=document.getElementById('splash');
    s.style.opacity='0';s.style.transform='scale(1.04)';
    setTimeout(()=>s.remove(),600);
  },1100);
});
</script>
@endpush

<!-- HERO -->
<section class="hero">
  @if(file_exists(public_path('videos/hero.mp4')))
    <video autoplay muted loop playsinline
           style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;filter:brightness(0.3)">
      <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
    </video>
  @else
    <div class="hero-bg hero-bg-zoom"></div>
  @endif
  <div class="hero-glow"></div>
  <div class="container" style="position:relative;z-index:2">
    <div class="hero-content">

      <div class="hero-animate" style="animation-delay:0.05s">
        <div class="hero-eyebrow">
          <i class="bi bi-award-fill"></i>
          Cafetería Artesanal Peruana · Desde 2018
        </div>
      </div>

      <div class="hero-animate" style="animation-delay:0.2s">
        <h1 class="hero-title">
          El alma del Perú<br>en cada <em>sorbo</em>
        </h1>
      </div>

      <div class="hero-animate" style="animation-delay:0.38s">
        <p class="hero-sub">
          Granos seleccionados de Cajamarca, San Martín y Chanchamayo.<br>
          Tostado artesanal. Preparado con pasión. Servido con amor.
        </p>
      </div>

      <div class="hero-animate" style="animation-delay:0.52s">
        <div class="hero-ctas">
          <a href="{{ route('catalogo') }}" class="btn-primary-tt">
            <i class="bi bi-grid-fill"></i> Explorar Carta
          </a>
          <a href="{{ route('reservas.index') }}" class="btn-ghost-tt">
            <i class="bi bi-calendar3"></i> Reservar Mesa
          </a>
        </div>
      </div>

      <div class="hero-animate" style="animation-delay:0.68s">
        <div class="hero-stats">
          <div class="text-center">
            <div class="hero-stat-num" data-count="15" data-suffix="+">15+</div>
            <div class="hero-stat-lbl">Variedades</div>
          </div>
          <div class="text-center">
            <div class="hero-stat-num" data-count="4.9" data-suffix="">4.9</div>
            <div class="hero-stat-lbl">Calificación</div>
          </div>
          <div class="text-center">
            <div class="hero-stat-num" data-count="2" data-suffix="k+">2k+</div>
            <div class="hero-stat-lbl">Clientes</div>
          </div>
          <div class="text-center">
            <div class="hero-stat-num" data-count="6" data-suffix="">6</div>
            <div class="hero-stat-lbl">Años</div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="scroll-ind">
    <i class="bi bi-chevron-double-down"></i>
    <span>Descubrir</span>
  </div>
</section>

<!-- PROMOCIONES -->
@if($promociones->isNotEmpty())
<section class="section" style="background:var(--c-surface)">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label"><i class="bi bi-tag-fill me-1" style="color:var(--c-gold)"></i>Ofertas Especiales</div>
      <h2 class="section-title">Promociones <em>Activas</em></h2>
    </div>

    <div class="row g-3 justify-content-center">
      @foreach($promociones as $promo)
        @php
          $colClass = $promociones->count() === 1 ? 'col-12 col-md-8 col-lg-6'
                    : ($promociones->count() === 2 ? 'col-12 col-sm-6'
                    : 'col-12 col-sm-6 col-lg-4');
          $primerProducto = $promo->productos->first();
        @endphp
        <div class="{{ $colClass }} reveal">
          <a href="{{ route('promociones.show', $promo) }}" class="promo-mk-card" style="--promo-color:{{ $promo->color }}">
            <!-- Header -->
            <div class="promo-mk-header">
              <span class="promo-mk-title">{{ $promo->nombre }}</span>
              <span class="promo-mk-link">Ver más <i class="bi bi-chevron-right"></i></span>
            </div>
            <!-- Imagen -->
            <div class="promo-mk-img-wrap">
              @if($primerProducto)
                <img src="{{ $primerProducto->imagen_url }}" alt="{{ $promo->nombre }}" class="promo-mk-img">
              @else
                <div class="promo-mk-img-placeholder"><i class="bi bi-tag-fill"></i></div>
              @endif
              <div class="promo-mk-img-grad"></div>
            </div>
            <!-- Footer info -->
            <div class="promo-mk-footer">
              <div class="promo-mk-count">
                <i class="bi bi-bag-fill"></i>
                {{ $promo->productos_activos_count }} producto{{ $promo->productos_activos_count !== 1 ? 's' : '' }} en oferta
              </div>
              @if($promo->fecha_fin)
                <div class="promo-mk-date">
                  <i class="bi bi-clock-fill"></i> Hasta {{ $promo->fecha_fin->format('d/m') }}
                </div>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CATEGORÍAS CON SCROLL HORIZONTAL -->
<section class="section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">Nuestros Productos</div>
      <h2 class="section-title">Explora la <em>Carta</em></h2>
    </div>

    {{-- SCROLL HORIZONTAL EN MOBILE, GRID EN DESKTOP --}}
    <div class="cat-scroll-wrap mb-5">
      @php
        $categorias_imgs = [
          ['calientes',  'calientes.jpg',  'Bebidas Calientes', 'Espresso, cappuccino, latte y más',  'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&h=350&fit=crop'],
          ['frias',      'frias.jpg',      'Bebidas Frías',     'Cold brew, frappés, matcha',         'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&h=350&fit=crop'],
          ['postres',    'postres.jpg',    'Postres',           'Brownies, cheesecakes, alfajores',   'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&h=350&fit=crop'],
          ['cafe_grano', 'cafe_grano.jpg', 'Café en Grano',    'Orígenes peruanos premium',          'https://images.unsplash.com/photo-1611854779393-1b2da9d400fe?w=600&h=350&fit=crop'],
        ];
      @endphp

      {{-- MÓVIL: carrusel una card a la vez, deslizable --}}
      <div id="catCarousel" class="carousel slide d-lg-none pb-5"
           data-bs-ride="carousel" data-bs-interval="3200" data-bs-touch="true">
        <div class="carousel-inner">
          @foreach($categorias_imgs as $i => [$key, $archivo, $name, $desc, $fallback])
            @php
              $imgUrl = file_exists(public_path("images/categorias/{$archivo}"))
                ? asset("images/categorias/{$archivo}")
                : $fallback;
            @endphp
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <a href="{{ route('catalogo',['categoria'=>$key]) }}" class="cat-card d-block">
                <div class="cat-img">
                  <img src="{{ $imgUrl }}" alt="{{ $name }}">
                  <div class="cat-overlay"></div>
                </div>
                <div class="cat-body">
                  <h5 class="cat-name">{{ $name }}</h5>
                  <p class="cat-desc">{{ $desc }}</p>
                  <span class="cat-link">Ver productos <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
              </a>
            </div>
          @endforeach
        </div>
        {{-- Dots de navegación --}}
        <div class="carousel-indicators" style="bottom:-2rem">
          @foreach($categorias_imgs as $i => $cat)
            <button type="button"
                    data-bs-target="#catCarousel"
                    data-bs-slide-to="{{ $i }}"
                    {{ $i === 0 ? 'class=active aria-current=true' : '' }}
                    aria-label="{{ $cat[2] }}"></button>
          @endforeach
        </div>
      </div>

      {{-- ESCRITORIO: grid de 4 columnas --}}
      <div class="cat-scroll d-none d-lg-block" id="catGrid">
        @foreach($categorias_imgs as [$key, $archivo, $name, $desc, $fallback])
          @php
            $imgUrl = file_exists(public_path("images/categorias/{$archivo}"))
              ? asset("images/categorias/{$archivo}")
              : $fallback;
          @endphp
          <a href="{{ route('catalogo',['categoria'=>$key]) }}" class="cat-card">
            <div class="cat-img">
              <img src="{{ $imgUrl }}" alt="{{ $name }}">
              <div class="cat-overlay"></div>
            </div>
            <div class="cat-body">
              <h5 class="cat-name">{{ $name }}</h5>
              <p class="cat-desc">{{ $desc }}</p>
              <span class="cat-link">Ver productos <i class="bi bi-arrow-right ms-1"></i></span>
            </div>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="text-center mb-4 reveal">
      <div class="section-label">Lo más pedido</div>
      <h3 class="section-title">Más <em>Vendidos</em></h3>
    </div>
    <div class="row g-4">
      @foreach($destacados as $p)
        <div class="col-sm-6 col-lg-4">
          <div class="prod-card h-100">
            <div class="prod-img-wrap">
              <img src="{{ $p->imagen_url }}" alt="{{ $p->nombre }}">
              <span class="prod-cat-badge">{{ $p->categoria_label }}</span>
            </div>
            <div class="prod-body">
              <div class="prod-name">{{ $p->nombre }}</div>
              <div class="prod-desc">{{ mb_substr($p->descripcion, 0, 80) }}</div>
              <div class="prod-footer">
                <div>
                  <div class="prod-price">S/ {{ number_format($p->precio,2) }}</div>
                  <div class="prod-rating">
                    <i class="bi bi-star-fill"></i> {{ number_format($p->rating,1) }}
                  </div>
                </div>
                @if($p->hayStock())
                  <button class="btn-add js-add-cart" data-url="{{ route('carrito.agregar',$p) }}">
                    <i class="bi bi-bag-plus"></i> Agregar
                  </button>
                @else
                  <span class="badge-tt badge-danger">Agotado</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-5 reveal">
      <a href="{{ route('catalogo') }}" class="btn-primary-tt">
        Ver Carta Completa <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- PROCESO -->
<section class="section" style="background:var(--c-surface)">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">Nuestro Proceso</div>
      <h2 class="section-title">Del Grano a tu <em>Taza</em></h2>
    </div>
    <div class="row g-4">
      @php
        $proceso = [
          ['seleccion.jpg', '01', 'Selección',  'Elegimos los mejores granos de altura de las regiones cafetaleras del Perú.',      'https://images.unsplash.com/photo-1611854779393-1b2da9d400fe?w=400&h=400&fit=crop', 'bi-search-heart'],
          ['tostado.jpg',   '02', 'Tostado',    'Tostado artesanal en lotes pequeños para resaltar las notas de cada origen.',      'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&h=400&fit=crop', 'bi-fire'],
          ['moliendo.jpg',  '03', 'Molienda',   'Molemos al momento según el método elegido para máxima frescura y aroma.',         'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=400&fit=crop', 'bi-gear-fill'],
          ['servicio.jpg',  '04', 'Servicio',   'Cada taza preparada por nuestros baristas con técnica y agua a temperatura exacta.','https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=400&fit=crop', 'bi-cup-hot-fill'],
        ];
      @endphp
      @foreach($proceso as $i => [$archivo, $num, $tit, $desc, $fallback, $icon])
        @php
          $imgUrl = file_exists(public_path("images/proceso/{$archivo}"))
            ? asset("images/proceso/{$archivo}")
            : $fallback;
        @endphp
        <div class="col-sm-6 col-lg-3 reveal">
          <div class="proceso-card">
            <div class="proceso-num">{{ $num }}</div>
            <div class="proceso-img-wrap">
              <img src="{{ $imgUrl }}" alt="{{ $tit }}">
              <div class="proceso-icon-badge">
                <i class="bi {{ $icon }}"></i>
              </div>
              <div class="proceso-overlay"><p>{{ $desc }}</p></div>
            </div>
            <h5 class="proceso-titulo">{{ $tit }}</h5>
            <p class="proceso-desc">{{ $desc }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- COWORKING -->
<section class="section">
  <div class="container">
    <div class="reveal coworking-banner">
      @php
        $coworkingUrl = file_exists(public_path('images/coworking.jpg'))
          ? asset('images/coworking.jpg')
          : 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=1400&h=500&fit=crop';
      @endphp
      <img src="{{ $coworkingUrl }}" alt="Coworking" class="coworking-bg">
      <div class="coworking-content">
        <div class="section-label" style="color:var(--c-gold)">
          <i class="bi bi-laptop me-1"></i> Espacio de Trabajo
        </div>
        <h2 class="section-title mb-3" style="color:white">Zona <em>Coworking</em></h2>
        <p style="color:rgba(255,255,255,0.75);max-width:500px;margin:0 auto 2rem;line-height:1.8">
          Trabaja con el mejor café de fondo. WiFi de alta velocidad, enchufes en cada puesto.
        </p>
        <a href="{{ route('reservas.index') }}" class="btn-primary-tt">
          <i class="bi bi-calendar3"></i> Reservar Ahora
        </a>
      </div>
    </div>
  </div>
</section>


@push('scripts')
<script>
// Contador animado para stats del hero
(function() {
  const counters = document.querySelectorAll('.hero-stat-num[data-count]');
  if (!counters.length) return;
  const run = (el) => {
    const target  = parseFloat(el.dataset.count);
    const suffix  = el.dataset.suffix || '';
    const isFloat = target % 1 !== 0;
    const dur     = 1400;
    let start = null;
    const step = (ts) => {
      if (!start) start = ts;
      const p = Math.min((ts - start) / dur, 1);
      const ease = 1 - Math.pow(1 - p, 3);
      const val  = ease * target;
      el.textContent = (isFloat ? val.toFixed(1) : Math.floor(val)) + suffix;
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  // Arrancar cuando el bloque de stats es visible
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { run(e.target); obs.unobserve(e.target); } });
  }, { threshold: 0.5 });
  counters.forEach(el => obs.observe(el));
})();

document.addEventListener('DOMContentLoaded', () => {

  // Color-active cycling solo en escritorio (grid #catGrid)
  const grid = document.getElementById('catGrid');
  if (!grid) return;

  const cards = Array.from(grid.querySelectorAll('.cat-card'));
  if (!cards.length) return;

  let current = 0;

  function activateCard(idx) {
    cards.forEach(c => c.classList.remove('color-active'));
    cards[idx].classList.add('color-active');
    current = idx;
  }

  let autoTimer = setInterval(() => {
    activateCard((current + 1) % cards.length);
  }, 2500);

  cards.forEach((card, i) => {
    card.addEventListener('click', function(e) {
      e.preventDefault();
      clearInterval(autoTimer);
      activateCard(i);
      const href = this.href;
      setTimeout(() => { window.location.href = href; }, 380);
    });
  });

  activateCard(0);
});
</script>
@endpush

@endsection
