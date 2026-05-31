@extends('layouts.app')
@section('title','Sobre Nosotros')
@section('content')
<div class="container section">
  <!-- Hero about -->
  <div class="about-hero mb-5 reveal">
    <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=800&q=80" alt="Tierra y Taza" class="about-img">
    <div class="about-content">
      <div class="section-label">Nuestra Historia</div>
      <h1 class="section-title mb-4">Café con <em>alma</em> peruana</h1>
      <p style="color:var(--c-muted);line-height:1.9;margin-bottom:1.5rem">
        Tierra y Taza nació en 2018 de la pasión de dos jóvenes baristas peruanos que viajaron por los valles cafetaleros de Cajamarca, San Martín y Chanchamayo buscando los mejores granos del país.
      </p>
      <p style="color:var(--c-muted);line-height:1.9;margin-bottom:2rem">
        Hoy somos un referente de la cultura cafetera en Lima, ofreciendo no solo café excepcional sino también un espacio de trabajo, estudio y conversación.
      </p>
      <div class="row g-3">
        @foreach([['2018','Año de fundación'],['15+','Variedades de café'],['2k+','Clientes satisfechos'],['100%','Origen peruano']] as [$n,$l])
          <div class="col-6">
            <div style="font-size:1.8rem;font-weight:900;color:var(--c-gold)">{{ $n }}</div>
            <div style="color:var(--c-muted);font-size:0.8rem">{{ $l }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Valores -->
  <div class="text-center mb-5 reveal">
    <div class="section-label">Nuestros Valores</div>
    <h2 class="section-title">Lo que nos hace <em>únicos</em></h2>
  </div>
  <div class="row g-4">
    @foreach([
      ['bi bi-tree-fill','Sostenibilidad','Trabajamos directamente con pequeños agricultores peruanos bajo comercio justo.'],
      ['bi bi-droplet-half','Calidad','Cada lote es analizado en nuestra sala de catación antes de llegar a tu taza.'],
      ['bi bi-mortarboard-fill','Formación','Capacitamos a nuestro equipo en técnicas de barismo de clase mundial.'],
      ['bi bi-heart-fill','Comunidad','Somos un espacio de encuentro, trabajo y cultura cafetera en Lima.'],
    ] as [$ico,$tit,$desc])
      <div class="col-sm-6 col-lg-3 reveal">
        <div class="glass-card p-4 h-100">
          <div style="font-size:2rem;color:var(--c-gold);margin-bottom:1rem"><i class="{{ $ico }}"></i></div>
          <h5 style="font-weight:700;margin-bottom:0.5rem">{{ $tit }}</h5>
          <p style="color:var(--c-muted);font-size:0.875rem;line-height:1.7">{{ $desc }}</p>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
