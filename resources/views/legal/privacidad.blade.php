@extends('layouts.app')
@section('title', 'Política de Privacidad')

@section('content')
<div class="container py-5" style="max-width:860px">

  <div class="text-center mb-5 reveal">
    <span style="font-size:0.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);font-weight:600">
      Legal · 2026
    </span>
    <h1 class="display-5 fw-bold mt-2">Política de Privacidad</h1>
    <p style="color:var(--c-muted)">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>
  </div>

  @php
  $secciones = [
    ['1. Responsable del tratamiento', '<strong>Denominación:</strong> Tierra y Taza Cafetería Artesanal S.A.C.<br><strong>Domicilio:</strong> Av. Larco 1234, Miraflores, Lima, Perú<br><strong>Email:</strong> hola@tierraytaza.pe<br><br>Tierra y Taza Cafetería Artesanal S.A.C. es la entidad responsable del tratamiento de los datos personales que usted nos facilite a través de nuestro sitio web, plataforma de pedidos y sistema de reservas.'],
    ['2. Datos que recopilamos', 'En función de los servicios que utilice, podemos recopilar los siguientes datos personales:<br><br>• <strong>Datos de identificación:</strong> nombre completo, DNI o documento de identidad equivalente.<br>• <strong>Datos de contacto:</strong> dirección de correo electrónico, número de teléfono, dirección de entrega.<br>• <strong>Datos de pedido:</strong> historial de pedidos, preferencias alimenticias y anotaciones especiales.<br>• <strong>Datos de pago:</strong> método de pago utilizado y últimos cuatro dígitos de tarjeta cuando aplique. <strong>No almacenamos números de tarjeta completos, CVV ni datos bancarios sensibles</strong>; las transacciones con tarjeta se procesan íntegramente a través de pasarelas de pago certificadas PCI-DSS.<br>• <strong>Datos de navegación:</strong> dirección IP (anonimizada), tipo de navegador, páginas visitadas y tiempo de sesión, con fines estadísticos.'],
    ['3. Finalidad del tratamiento', 'Tratamos sus datos personales con las siguientes finalidades:<br><br>• <strong>Gestión de pedidos:</strong> procesar, confirmar y entregar los pedidos que realice a través de nuestra plataforma.<br>• <strong>Gestión de reservas:</strong> tramitar y confirmar reservas de mesas o espacios de coworking.<br>• <strong>Atención al cliente:</strong> responder a sus consultas, reclamaciones y solicitudes de soporte.<br>• <strong>Comunicaciones comerciales:</strong> enviarle ofertas, promociones y novedades relacionadas con nuestros servicios, <em>únicamente con su consentimiento expreso</em>.<br>• <strong>Mejora del servicio:</strong> análisis estadístico anónimo del uso del sitio web para optimizar la experiencia de navegación.'],
    ['4. Base jurídica del tratamiento', 'El tratamiento de sus datos se sustenta en las siguientes bases jurídicas reconocidas por la <strong>Ley N.° 29733 — Ley de Protección de Datos Personales del Perú</strong> y su Reglamento (D.S. 003-2013-JUS):<br><br>• <strong>Ejecución del contrato:</strong> el tratamiento es necesario para gestionar su pedido o reserva y cumplir la relación contractual.<br>• <strong>Consentimiento:</strong> para el envío de comunicaciones comerciales y el uso de cookies no esenciales, solicitamos su consentimiento expreso, que puede retirar en cualquier momento.<br>• <strong>Interés legítimo:</strong> para la prevención del fraude y la seguridad de la plataforma.<br>• <strong>Obligación legal:</strong> para el cumplimiento de obligaciones tributarias y mercantiles exigidas por la legislación peruana.'],
    ['5. Conservación de datos', 'Sus datos personales se conservarán durante el tiempo estrictamente necesario para cumplir las finalidades para las que fueron recogidos:<br><br>• <strong>Datos de cuenta activa:</strong> mientras mantenga su cuenta registrada en nuestra plataforma.<br>• <strong>Datos de pedidos y facturación:</strong> un mínimo de <strong>5 años</strong> desde la fecha de la última transacción, en cumplimiento de las obligaciones legales y tributarias vigentes en el Perú.<br>• <strong>Comunicaciones comerciales:</strong> hasta que retire su consentimiento.<br><br>Una vez transcurridos estos plazos, los datos serán eliminados o anonimizados de forma irreversible.'],
    ['6. Sus derechos ARCO', 'De conformidad con la Ley N.° 29733, usted tiene derecho a:<br><br>• <strong>Acceso:</strong> conocer qué datos personales suyos tratamos, su origen y los usos que hacemos de ellos.<br>• <strong>Rectificación:</strong> solicitar la corrección de datos inexactos o incompletos.<br>• <strong>Cancelación:</strong> solicitar la eliminación de sus datos cuando ya no sean necesarios para la finalidad con que fueron recogidos.<br>• <strong>Oposición:</strong> oponerse al tratamiento de sus datos en determinadas circunstancias, en particular para fines de comunicaciones comerciales.<br><br>Para ejercer cualquiera de estos derechos, envíe una solicitud escrita a <strong>hola@tierraytaza.pe</strong> indicando su nombre completo, el derecho que desea ejercer y, si lo considera oportuno, la documentación que acredite su identidad. Daremos respuesta en un plazo máximo de 20 días hábiles.'],
    ['7. Seguridad de los datos', 'Aplicamos medidas técnicas y organizativas adecuadas para proteger sus datos personales frente a accesos no autorizados, pérdida, destrucción o divulgación accidental:<br><br>• <strong>HTTPS:</strong> todas las comunicaciones entre su navegador y nuestros servidores se realizan mediante cifrado TLS.<br>• <strong>Contraseñas cifradas:</strong> las contraseñas se almacenan exclusivamente en forma de hash irreversible (bcrypt); ningún empleado puede acceder a su contraseña en texto plano.<br>• <strong>Datos de pago:</strong> no almacenamos números de tarjeta completos ni datos de verificación (CVV/CVC). Los pagos con tarjeta son procesados por pasarelas certificadas PCI-DSS.<br>• <strong>Acceso restringido:</strong> el acceso a los datos personales está limitado al personal que lo necesite para el desempeño de sus funciones.'],
    ['8. Transferencias internacionales', 'Con carácter general, sus datos personales no se transfieren fuera del territorio peruano. Sin embargo, algunos servicios técnicos que utilizamos implican el uso de servidores en el extranjero:<br><br>• <strong>Bootstrap CDN y Google Fonts:</strong> para la carga de recursos estáticos (hojas de estilo y tipografías), nuestro sitio hace solicitudes a servidores de terceros ubicados en la Unión Europea o Estados Unidos. Estos proveedores cuentan con las salvaguardas adecuadas (cláusulas contractuales tipo u otros mecanismos reconocidos) para garantizar la protección de los datos.<br><br>No realizamos transferencias de datos personales de clientes a terceros con fines comerciales.'],
    ['9. Cambios en la política', 'Nos reservamos el derecho de actualizar esta Política de Privacidad para adaptarla a cambios legislativos, jurisprudenciales o a modificaciones en nuestros servicios. Cuando los cambios sean significativos, se lo notificaremos a través del correo electrónico asociado a su cuenta o mediante un aviso destacado en el sitio web con antelación suficiente.<br><br>Le recomendamos revisar esta política periódicamente. La fecha de "Última actualización" que figura en la cabecera siempre reflejará la versión vigente.'],
    ['10. Contacto — Delegado de Protección de Datos', 'Si tiene dudas, sugerencias o desea ejercer sus derechos en materia de protección de datos, puede contactar con nuestro responsable de privacidad:<br><br>• <strong>Email:</strong> hola@tierraytaza.pe<br>• <strong>Dirección postal:</strong> Av. Larco 1234, Miraflores, Lima 15074, Perú<br><br>Asimismo, si considera que el tratamiento de sus datos no se ajusta a la normativa vigente, puede presentar una reclamación ante la <strong>Autoridad Nacional de Protección de Datos Personales (ANPDP)</strong> del Ministerio de Justicia y Derechos Humanos del Perú.'],
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
    <a href="{{ route('cookies') }}" class="btn-ghost-tt me-3">
      <i class="bi bi-shield-check me-2"></i>Política de Cookies
    </a>
    <a href="{{ route('home') }}" class="btn-cafe btn">
      <i class="bi bi-house me-2"></i>Volver al inicio
    </a>
  </div>
</div>
@endsection
