@extends('layouts.app')
@section('title', 'Aviso Legal')

@section('content')
<div class="container py-5" style="max-width:860px">

  <div class="text-center mb-5 reveal">
    <span style="font-size:0.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);font-weight:600">
      Legal · {{ date('Y') }}
    </span>
    <h1 class="display-5 fw-bold mt-2">Aviso Legal</h1>
    <p style="color:var(--c-muted)">Información legal sobre Tierra y Taza Cafetería Artesanal</p>
  </div>

  @php
  $secciones = [
    ['Datos del titular', '<strong>Denominación:</strong> Tierra y Taza Cafetería Artesanal S.A.C.<br><strong>RUC:</strong> 20xxxxxxxxx<br><strong>Domicilio:</strong> Av. Larco 1234, Miraflores, Lima, Perú<br><strong>Teléfono:</strong> +51 987 654 321<br><strong>Email:</strong> hola@tierraytaza.pe<br><strong>Actividad:</strong> Servicios de cafetería, coworking y venta de productos artesanales'],
    ['Objeto', 'El presente Aviso Legal regula el acceso y uso del sitio web <em>tierraytaza.pe</em> y sus subdominios, así como la plataforma digital de pedidos y reservas de Tierra y Taza. El acceso al sitio implica la aceptación plena y sin reservas de este aviso.'],
    ['Propiedad intelectual', 'Todos los contenidos del sitio web (textos, imágenes, vídeos, logotipos, diseño gráfico, código fuente y cualquier otro elemento) son titularidad de Tierra y Taza o cuentan con la debida licencia. Queda expresamente prohibida la reproducción, distribución, comunicación pública, transformación o cualquier otra forma de explotación, total o parcial, sin autorización escrita del titular.'],
    ['Protección de datos personales', 'De conformidad con la Ley N.° 29733 — Ley de Protección de Datos Personales del Perú y su Reglamento, los datos personales que usted nos proporcione serán tratados con la finalidad de gestionar su relación como cliente, procesar pedidos y reservas, mejorar nuestros servicios y, con su consentimiento expreso, enviarle comunicaciones comerciales.<br><br>Usted tiene derecho a acceder, rectificar, cancelar y oponerse al tratamiento de sus datos personales, ejerciendo tales derechos mediante solicitud escrita a hola@tierraytaza.pe.'],
    ['Cookies', 'Este sitio web puede utilizar cookies técnicas propias necesarias para el correcto funcionamiento del sitio, así como cookies de análisis de tráfico (en forma anónima y agregada). No utilizamos cookies de publicidad de terceros. Al continuar navegando, acepta el uso de cookies técnicas esenciales.'],
    ['Exención de responsabilidad', 'Tierra y Taza no garantiza la disponibilidad o continuidad ininterrumpida del sitio web y no será responsable por daños derivados de la interrupción del servicio, errores en el contenido o accesos no autorizados por parte de terceros. El sitio puede contener enlaces a páginas de terceros sobre las cuales no tenemos control ni responsabilidad.'],
    ['Legislación aplicable', 'Este aviso legal se rige por la legislación vigente en la República del Perú. Para cualquier controversia derivada del acceso o uso del sitio web, las partes se someten a los juzgados y tribunales de Lima, renunciando expresamente a cualquier otro fuero que pudiera corresponderles.'],
    ['Contacto legal', 'Para cualquier consulta o comunicación de carácter legal, diríjase a:<br><strong>Email:</strong> legal@tierraytaza.pe<br><strong>Dirección postal:</strong> Av. Larco 1234, Miraflores, Lima 15074, Perú<br><br>Daremos respuesta en un plazo máximo de 5 días hábiles.'],
  ];
  @endphp

  <div class="d-flex flex-column gap-4">
    @foreach($secciones as $i => [$titulo, $texto])
      <div class="glass-card p-4 reveal" style="transition-delay:{{ $i * 0.04 }}s">
        <h5 style="font-weight:700;color:var(--c-gold);margin-bottom:0.75rem;font-size:1rem">{{ $titulo }}</h5>
        <p style="color:var(--c-muted);line-height:1.8;margin:0;font-size:0.9rem">{!! $texto !!}</p>
      </div>
    @endforeach
  </div>

  <div class="text-center mt-5 reveal">
    <a href="{{ route('terminos') }}" class="btn-ghost-tt me-3">
      <i class="bi bi-file-text me-2"></i>Términos y Condiciones
    </a>
    <a href="{{ route('home') }}" class="btn-cafe btn">
      <i class="bi bi-house me-2"></i>Volver al inicio
    </a>
  </div>
</div>
@endsection
