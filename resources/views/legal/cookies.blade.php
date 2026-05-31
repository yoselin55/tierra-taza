@extends('layouts.app')
@section('title', 'Política de Cookies')

@section('content')
<div class="container py-5" style="max-width:860px">

  <div class="text-center mb-5 reveal">
    <span style="font-size:0.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);font-weight:600">
      Legal · 2026
    </span>
    <h1 class="display-5 fw-bold mt-2">Política de Cookies</h1>
    <p style="color:var(--c-muted)">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>
  </div>

  @php
  $secciones = [
    ['¿Qué son las cookies?', 'Las cookies son pequeños archivos de texto que los sitios web almacenan en su dispositivo (ordenador, tablet o móvil) cuando los visita. Sirven para que el sitio recuerde sus acciones y preferencias durante un período de tiempo, de manera que no tenga que volver a introducirlas cada vez que regrese al sitio o navegue de una página a otra.<br><br>Las cookies no dañan su dispositivo ni contienen virus. Son archivos de texto que su navegador puede leer y que se eliminan automáticamente cuando caduca su período de validez o cuando usted las borra manualmente.'],
    ['Cookies que utilizamos', '<strong style="color:var(--c-gold)">Cookies técnicas esenciales</strong><br>Estas cookies son necesarias para el correcto funcionamiento del sitio y no pueden desactivarse. Incluyen:<br>• <strong>Cookie de sesión:</strong> mantiene su sesión activa mientras navega por el sitio.<br>• <strong>Cookie CSRF:</strong> protege los formularios contra ataques de falsificación de solicitudes entre sitios.<br><br><strong style="color:var(--c-gold)">Cookies de preferencias</strong><br>Estas cookies recuerdan sus elecciones para ofrecerle una experiencia personalizada:<br>• <strong>Tema oscuro/claro:</strong> guarda su preferencia de apariencia visual.<br>• <strong>Idioma:</strong> recuerda el idioma seleccionado en su última visita.<br><br><strong style="color:var(--c-gold)">Cookies analíticas</strong><br>Utilizamos cookies de análisis para entender cómo los visitantes interactúan con nuestro sitio. Toda la información recopilada es anónima y agregada — nunca se vincula a datos personales identificables. Nos ayudan a mejorar la experiencia de navegación y el rendimiento del sitio.'],
    ['Cookies de terceros', 'Algunos de los servicios externos que utilizamos pueden instalar sus propias cookies:<br><br>• <strong>Bootstrap CDN:</strong> utilizamos la red de distribución de contenido de Bootstrap para cargar hojas de estilo y scripts. Su uso está sujeto a sus propias políticas de privacidad.<br>• <strong>Google Fonts:</strong> cargamos tipografías desde los servidores de Google. Google puede recopilar información sobre las solicitudes de fuentes de acuerdo con su política de privacidad.<br><br>No utilizamos cookies de publicidad ni de seguimiento publicitario de terceros. No compartimos datos de navegación con redes publicitarias.'],
    ['Cómo gestionar las cookies', 'Puede configurar su navegador para aceptar, rechazar o eliminar cookies en cualquier momento. Tenga en cuenta que deshabilitar las cookies técnicas esenciales puede afectar al funcionamiento del sitio.<br><br>• <strong>Google Chrome:</strong> Configuración → Privacidad y seguridad → Cookies y otros datos de sitios.<br>• <strong>Mozilla Firefox:</strong> Opciones → Privacidad y seguridad → Cookies y datos del sitio.<br>• <strong>Safari:</strong> Preferencias → Privacidad → Gestión de datos de sitios web.<br>• <strong>Microsoft Edge:</strong> Configuración → Privacidad, búsqueda y servicios → Cookies.<br><br>También puede visitar <a href="https://www.aboutcookies.org" target="_blank" rel="noopener noreferrer" style="color:var(--c-gold)">aboutcookies.org</a> para obtener instrucciones detalladas sobre la gestión de cookies en distintos navegadores y dispositivos.'],
    ['Actualizaciones de esta política', 'Nos reservamos el derecho de actualizar esta Política de Cookies en cualquier momento para reflejar cambios en nuestras prácticas o en la normativa aplicable. Cuando realicemos cambios significativos, actualizaremos la fecha de "Última actualización" que figura en la cabecera de esta página.<br><br>Le recomendamos revisar esta política periódicamente para mantenerse informado sobre cómo utilizamos las cookies. El uso continuado del sitio tras la publicación de cambios implica la aceptación de la política actualizada.'],
    ['Contacto', 'Si tiene alguna pregunta sobre nuestra Política de Cookies o sobre el tratamiento de sus datos, puede ponerse en contacto con nosotros:<br><br>• <strong>Email:</strong> hola@tierraytaza.pe<br>• <strong>Dirección:</strong> Av. Larco 1234, Miraflores, Lima, Perú<br><br>Daremos respuesta en un plazo máximo de 5 días hábiles.'],
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
    <a href="{{ route('home') }}" class="btn-cafe btn">
      <i class="bi bi-house me-2"></i>Volver al inicio
    </a>
  </div>
</div>
@endsection
