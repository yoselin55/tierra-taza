@extends('layouts.app')
@section('title', 'Términos y Condiciones')

@section('content')
<div class="container py-5" style="max-width:860px">

  <div class="text-center mb-5 reveal">
    <span style="font-size:0.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);font-weight:600">
      Legal · {{ date('Y') }}
    </span>
    <h1 class="display-5 fw-bold mt-2">Términos y Condiciones</h1>
    <p style="color:var(--c-muted)">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>
  </div>

  @php
  $secciones = [
    ['1. Aceptación de los términos', 'Al acceder y utilizar los servicios de <strong>Tierra y Taza Cafetería Artesanal</strong> — incluyendo nuestro sitio web, sistema de reservas y plataforma de pedidos — usted acepta cumplir y estar sujeto a los presentes Términos y Condiciones. Si no está de acuerdo con alguna parte de estos términos, le pedimos que no utilice nuestros servicios.'],
    ['2. Descripción del servicio', 'Tierra y Taza ofrece servicios de cafetería artesanal, incluyendo:<br>• Venta de productos de cafetería (bebidas, alimentos y artículos relacionados).<br>• Sistema de reservas de mesas y espacios de coworking.<br>• Pedidos en línea con opción de recogida o entrega a domicilio (delivery).<br>• Área de coworking equipada para profesionales y estudiantes.'],
    ['3. Cuenta de usuario', 'Para realizar pedidos o reservas necesitará crear una cuenta. Usted es responsable de:<br>• Mantener la confidencialidad de sus credenciales de acceso.<br>• Todas las actividades que se realicen bajo su cuenta.<br>• Notificarnos inmediatamente ante cualquier uso no autorizado.<br><br>Nos reservamos el derecho de desactivar cuentas que violen estos términos.'],
    ['4. Pedidos y pagos', 'Los pedidos realizados a través de nuestra plataforma están sujetos a disponibilidad de productos y confirmación de pago. Aceptamos los siguientes métodos de pago: Yape, Plin, tarjeta de crédito/débito, efectivo y transferencia bancaria. Todos los precios incluyen IGV (18%). Una vez confirmado el pedido, solo se aceptan cancelaciones dentro de los 5 minutos siguientes.'],
    ['5. Reservas', 'Las reservas de mesas y espacios de coworking son nominales y no transferibles. Una reserva confirmada que no sea utilizada sin previo aviso (no-show) puede resultar en restricciones para futuras reservas. Podemos cancelar o modificar una reserva en casos de fuerza mayor, notificando al usuario con la mayor anticipación posible.'],
    ['6. Entrega a domicilio (Delivery)', 'El servicio de delivery está disponible en las zonas indicadas en nuestra plataforma. Los tiempos de entrega son estimados y pueden variar por factores externos. Tierra y Taza no se responsabiliza por demoras causadas por condiciones de tráfico, clima u otros factores fuera de nuestro control. En caso de productos dañados en tránsito, contáctenos dentro de las 2 horas posteriores a la entrega.'],
    ['7. Política de devoluciones', 'Dado que comercializamos productos alimenticios perecederos, no aceptamos devoluciones una vez que el pedido ha sido preparado o entregado, salvo en casos de error en el pedido o producto defectuoso. En tales casos, contáctenos en las siguientes 2 horas con evidencia fotográfica y gestionaremos un reemplazo o reembolso.'],
    ['8. Propiedad intelectual', 'Todo el contenido del sitio web de Tierra y Taza — incluyendo textos, imágenes, logotipos, diseños y código — es propiedad exclusiva de Tierra y Taza Cafetería Artesanal y está protegido por las leyes de propiedad intelectual vigentes en el Perú. Queda prohibida su reproducción, distribución o uso sin autorización expresa.'],
    ['9. Limitación de responsabilidad', 'Tierra y Taza no será responsable por daños indirectos, incidentales o consecuentes derivados del uso de nuestros servicios. Nuestra responsabilidad máxima se limita al valor del pedido o servicio objeto de la reclamación.'],
    ['10. Modificaciones', 'Nos reservamos el derecho de modificar estos Términos y Condiciones en cualquier momento. Los cambios entrarán en vigor inmediatamente después de su publicación en el sitio web. El uso continuado de nuestros servicios implica la aceptación de los términos actualizados.'],
    ['11. Ley aplicable', 'Estos términos se rigen por las leyes de la República del Perú. Cualquier controversia será sometida a los juzgados y tribunales competentes de la ciudad de Lima, Perú.'],
    ['12. Contacto', 'Para consultas sobre estos términos, puede contactarnos en:<br>• Email: hola@tierraytaza.pe<br>• Teléfono: +51 987 654 321<br>• Dirección: Av. Larco 1234, Miraflores, Lima, Perú'],
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
