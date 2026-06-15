@extends('layouts.app')
@section('title','Sobre Nosotros')
@section('content')

{{-- ══ HERO NOSOTROS ═══════════════════════════════════════════ --}}
<section class="about-hero-section reveal">
  <div class="about-hero-img-wrap">
    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=1200&q=85" alt="Tierra y Taza" class="about-hero-img">
    <div class="about-hero-overlay"></div>
  </div>
  <div class="container about-hero-content-wrap">
    <div class="about-hero-content">
      <div class="section-label mb-3">Nuestra Historia</div>
      <h1 class="section-title mb-4" style="font-size:clamp(2.4rem,5vw,4rem)">Café con <em>alma</em><br>peruana</h1>
      <div style="width:48px;height:3px;background:linear-gradient(90deg,var(--c-gold),var(--c-amber));border-radius:2px;margin-bottom:1.75rem"></div>
      <p style="color:rgba(255,255,255,0.8);line-height:1.9;margin-bottom:1.25rem;max-width:520px;font-size:1rem">
        Tierra y Taza nació en 2018 de la pasión de dos jóvenes baristas peruanos que viajaron por los valles cafetaleros de Cajamarca, San Martín y Chanchamayo buscando los mejores granos del país.
      </p>
      <p style="color:rgba(255,255,255,0.65);line-height:1.9;max-width:520px;font-size:0.95rem;margin-bottom:2.5rem">
        Hoy somos un referente de la cultura cafetera en Lima, ofreciendo no solo café excepcional sino también un espacio de trabajo, estudio y conversación.
      </p>
      <div class="about-stats-grid">
        @foreach([['2018','Año de fundación'],['15+','Variedades de café'],['2k+','Clientes satisfechos'],['100%','Origen peruano']] as [$n,$l])
          <div class="about-stat-item">
            <div class="about-stat-num">{{ $n }}</div>
            <div class="about-stat-label">{{ $l }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ══ VALORES ════════════════════════════════════════════════ --}}
<section class="section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">Nuestros Valores</div>
      <h2 class="section-title">Lo que nos hace <em>únicos</em></h2>
    </div>
    <div class="row g-4 reveal-stagger">
      @foreach([
        ['tree-fill','Sostenibilidad','Trabajamos directamente con pequeños agricultores peruanos bajo comercio justo y precios dignos.'],
        ['droplet-half','Calidad','Cada lote es analizado en nuestra sala de catación antes de llegar a tu taza.'],
        ['mortarboard-fill','Formación','Capacitamos a nuestro equipo en técnicas de barismo de clase mundial.'],
        ['heart-fill','Comunidad','Somos un espacio de encuentro, trabajo y cultura cafetera en Lima.'],
      ] as [$ico,$tit,$desc])
        <div class="col-sm-6 col-lg-3">
          <div class="about-valor-card">
            <div class="about-valor-ico"><i class="bi bi-{{ $ico }}"></i></div>
            <h5 class="about-valor-title">{{ $tit }}</h5>
            <p class="about-valor-desc">{{ $desc }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ EQUIPO / PROCESO ════════════════════════════════════════ --}}
<section class="section-sm" style="background:var(--c-surface);border-top:1px solid var(--c-border);border-bottom:1px solid var(--c-border)">
  <div class="container">
    <div class="about-proceso-grid reveal-stagger">
      @foreach([
        ['geo-alt-fill','Origen','Seleccionamos granos de los mejores valles cafetaleros del Perú.'],
        ['sun-fill','Tostado','Tostamos en pequeños lotes para preservar cada nota aromática.'],
        ['cup-hot-fill','Preparación','Nuestros baristas preparan cada bebida con técnica y pasión.'],
        ['star-fill','Experiencia','Te ofrecemos un momento único en cada visita.'],
      ] as [$ico,$tit,$desc])
        <div class="about-proceso-item">
          <div class="about-proceso-ico"><i class="bi bi-{{ $ico }}"></i></div>
          <div class="about-proceso-line"></div>
          <h6 class="about-proceso-title">{{ $tit }}</h6>
          <p class="about-proceso-desc">{{ $desc }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
