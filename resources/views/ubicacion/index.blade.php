@extends('layouts.app')
@section('title','Ubicación')
@section('content')
<div class="container section">
  <div class="text-center mb-5 reveal">
    <div class="section-label">¿Dónde estamos?</div>
    <h1 class="section-title">Encuéntranos en <em>Miraflores</em></h1>
    <p style="color:var(--c-muted)">Ven a visitarnos. Te esperamos con el mejor café.</p>
  </div>

  <div class="row g-5 align-items-center">
    <div class="col-lg-7 reveal">
      <!-- Google Maps iframe (placeholder con la dirección) -->
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.902!2d-77.0380!3d-12.1211!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8b8c7b3b3b3%3A0x3b3b3b3b3b3b3b3b!2sAv.%20Larco%201234%2C%20Miraflores!5e0!3m2!1ses!2spe!4v1620000000000!5m2!1ses!2spe"
        class="map-frame"
        allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Ubicación Tierra y Taza">
      </iframe>
    </div>

    <div class="col-lg-5 reveal">
      <div class="d-flex flex-column gap-4">
        @foreach([
          ['bi-geo-alt-fill','text-gold','Dirección','Av. Larco 1234, Miraflores, Lima 15074'],
          ['bi-telephone-fill','text-gold','Teléfono','+51 987 654 321'],
          ['bi-envelope-fill','text-gold','Email','hola@tierraytaza.pe'],
          ['bi-clock-fill','text-gold','Horario','Lunes a Domingo: 7:00 am – 10:00 pm'],
        ] as [$ico,$cls,$lbl,$val])
          <div class="d-flex gap-4 align-items-start">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(200,150,60,0.12);border:1px solid rgba(200,150,60,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <i class="bi {{ $ico }} {{ $cls }}"></i>
            </div>
            <div>
              <div style="font-weight:700;margin-bottom:0.2rem">{{ $lbl }}</div>
              <div style="color:var(--c-muted)">{{ $val }}</div>
            </div>
          </div>
        @endforeach

        <div class="mt-2">
          <a href="https://www.google.com/maps/dir/?api=1&destination=Av+Larco+1234+Miraflores+Lima"
             target="_blank" class="btn-primary-tt w-100 justify-content-center">
            <i class="bi bi-navigation-fill"></i> Cómo llegar
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
