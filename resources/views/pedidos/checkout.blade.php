@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <h1 class="fw-bold mb-4"><i class="bi bi-bag-check-fill me-2" style="color:var(--c-gold)"></i>Confirmar Pedido</h1>

      @if($errors->any())
        <div class="auth-alert-error mb-4">
          @foreach($errors->all() as $e)
            <div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>
          @endforeach
        </div>
      @endif

      <div class="row g-4">
        <!-- Formulario -->
        <div class="col-lg-7">
          <form action="{{ route('pedidos.store') }}" method="POST" id="checkoutForm">
            @csrf

            <!-- Datos personales -->
            <div class="checkout-section mb-4">
              <div class="checkout-section-title">
                <i class="bi bi-person-fill"></i> Datos del Comprador
              </div>
              <div class="mb-3">
                <label class="tt-label">Nombre completo</label>
                <div class="tt-input-icon-wrap">
                  <i class="bi bi-person tt-input-icon"></i>
                  <input type="text" name="nombre_cliente" class="tt-input tt-input-padded"
                         value="{{ old('nombre_cliente', auth()->user()->nombre) }}" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="tt-label">DNI (8 dígitos)</label>
                <div class="tt-input-icon-wrap">
                  <i class="bi bi-credit-card tt-input-icon"></i>
                  <input type="text" name="dni_cliente" class="tt-input tt-input-padded"
                         value="{{ old('dni_cliente', auth()->user()->dni) }}" maxlength="8" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="tt-label">Notas adicionales <span style="color:var(--c-muted)">(opcional)</span></label>
                <textarea name="notas" class="tt-input" rows="2"
                          placeholder="Sin azúcar, con hielo extra...">{{ old('notas') }}</textarea>
              </div>
            </div>

            <!-- Dirección y fecha de entrega -->
            <div class="checkout-section mb-4">
              <div class="checkout-section-title">
                <i class="bi bi-geo-alt-fill"></i> Entrega a Domicilio
              </div>
              <div class="mb-3">
                <label class="tt-label">Dirección de envío <span style="color:#f87171">*</span></label>
                <div class="tt-input-icon-wrap">
                  <i class="bi bi-house-door tt-input-icon"></i>
                  <input type="text" name="direccion_envio" class="tt-input tt-input-padded"
                         placeholder="Av. Principal 123, Urb. Los Pinos..."
                         value="{{ old('direccion_envio') }}" required>
                </div>
                @error('direccion_envio')
                  <div style="color:#f87171;font-size:0.8rem;margin-top:0.35rem">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="tt-label">Referencia <span style="color:var(--c-muted)">(opcional)</span></label>
                <div class="tt-input-icon-wrap">
                  <i class="bi bi-signpost tt-input-icon"></i>
                  <input type="text" name="referencia_envio" class="tt-input tt-input-padded"
                         placeholder="Casa color amarillo, frente al parque..."
                         value="{{ old('referencia_envio') }}">
                </div>
              </div>
              <div class="mb-3">
                <label class="tt-label">Fecha de entrega deseada <span style="color:#f87171">*</span></label>
                <div class="tt-input-icon-wrap">
                  <i class="bi bi-calendar-check tt-input-icon"></i>
                  <input type="date" name="fecha_entrega" class="tt-input tt-input-padded"
                         min="{{ now()->toDateString() }}"
                         value="{{ old('fecha_entrega', now()->toDateString()) }}" required>
                </div>
                @error('fecha_entrega')
                  <div style="color:#f87171;font-size:0.8rem;margin-top:0.35rem">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- Método de pago -->
            <div class="checkout-section mb-4">
              <div class="checkout-section-title">
                <i class="bi bi-wallet2"></i> Método de Pago
              </div>

              <div class="row g-2 mb-4">
                @foreach([
                  ['yape',         'yape.svg',         'Yape',          '#8B5CF6'],
                  ['plin',         'plin.svg',         'Plin',          '#38BDF8'],
                  ['tarjeta',      'tarjeta.svg',      'Tarjeta',       '#22c55e'],
                  ['efectivo',     'efectivo.svg',     'Efectivo',      '#f59e0b'],
                  ['transferencia','transferencia.svg','Transferencia', '#A855F7'],
                ] as [$val, $logo, $nombre, $color])
                  <div class="col-6 col-sm-4 col-md-4">
                    <label class="pago-option-new {{ old('metodo_pago') === $val ? 'selected' : '' }}"
                           style="--pago-color:{{ $color }}">
                      <input type="radio" name="metodo_pago" value="{{ $val }}" class="pago-radio"
                             {{ old('metodo_pago') === $val ? 'checked' : '' }} required>
                      <img src="{{ asset('images/pagos/' . $logo) }}"
                           alt="{{ $nombre }}"
                           class="pago-logo-img"
                           draggable="false">
                      <span>{{ $nombre }}</span>
                      <div class="pago-dot"></div>
                    </label>
                  </div>
                @endforeach
              </div>

              {{-- Campos dinámicos por método --}}
              <div id="pago-detalles">

                {{-- Yape --}}
                <div class="pago-detalle-panel" data-metodo="yape">
                  <p class="pago-instruccion">
                    <i class="bi bi-info-circle text-gold me-1"></i>
                    Realiza la transferencia al número Yape registrado. El cajero validará tu pago.
                  </p>
                  <div class="mb-3">
                    <label class="tt-label">Tu número de celular Yape</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-phone tt-input-icon"></i>
                      <input type="text" name="datos_pago[numero_celular]" class="tt-input tt-input-padded"
                             placeholder="9XX XXX XXX" maxlength="9" value="{{ old('datos_pago.numero_celular') }}">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="tt-label">Nombre del titular de Yape</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-person tt-input-icon"></i>
                      <input type="text" name="datos_pago[titular]" class="tt-input tt-input-padded"
                             placeholder="Nombre como aparece en Yape" value="{{ old('datos_pago.titular') }}">
                    </div>
                  </div>
                </div>

                {{-- Plin --}}
                <div class="pago-detalle-panel" data-metodo="plin">
                  <p class="pago-instruccion">
                    <i class="bi bi-info-circle text-gold me-1"></i>
                    Realiza la transferencia al número Plin registrado. El cajero validará tu pago.
                  </p>
                  <div class="mb-3">
                    <label class="tt-label">Tu número de celular Plin</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-phone tt-input-icon"></i>
                      <input type="text" name="datos_pago[numero_celular]" class="tt-input tt-input-padded"
                             placeholder="9XX XXX XXX" maxlength="9" value="{{ old('datos_pago.numero_celular') }}">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="tt-label">Nombre del titular de Plin</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-person tt-input-icon"></i>
                      <input type="text" name="datos_pago[titular]" class="tt-input tt-input-padded"
                             placeholder="Nombre como aparece en Plin" value="{{ old('datos_pago.titular') }}">
                    </div>
                  </div>
                </div>

                {{-- Tarjeta --}}
                <div class="pago-detalle-panel" data-metodo="tarjeta">
                  <p class="pago-instruccion">
                    <i class="bi bi-shield-check text-gold me-1"></i>
                    Solo ingresa los últimos 4 dígitos. Nunca compartimos tu información completa.
                  </p>
                  <div class="row g-3">
                    <div class="col-12">
                      <label class="tt-label">Nombre en la tarjeta</label>
                      <div class="tt-input-icon-wrap">
                        <i class="bi bi-person tt-input-icon"></i>
                        <input type="text" name="datos_pago[titular]" class="tt-input tt-input-padded"
                               placeholder="NOMBRE APELLIDO" style="text-transform:uppercase"
                               value="{{ old('datos_pago.titular') }}">
                      </div>
                    </div>
                    <div class="col-8">
                      <label class="tt-label">Últimos 4 dígitos</label>
                      <div class="tt-input-icon-wrap">
                        <i class="bi bi-credit-card tt-input-icon"></i>
                        <input type="text" name="datos_pago[ultimos4]" class="tt-input tt-input-padded"
                               placeholder="•••• •••• •••• XXXX" maxlength="4"
                               value="{{ old('datos_pago.ultimos4') }}">
                      </div>
                    </div>
                    <div class="col-4">
                      <label class="tt-label">Vencimiento</label>
                      <input type="text" name="datos_pago[vencimiento]" class="tt-input"
                             placeholder="MM/AA" maxlength="5" value="{{ old('datos_pago.vencimiento') }}">
                    </div>
                  </div>
                </div>

                {{-- Efectivo --}}
                <div class="pago-detalle-panel" data-metodo="efectivo">
                  <div class="pago-instruccion" style="border-color:rgba(245,158,11,0.3);background:rgba(245,158,11,0.08)">
                    <i class="bi bi-cash-stack me-2" style="color:#f59e0b;font-size:1.2rem"></i>
                    <div>
                      <div style="font-weight:700;color:#fbbf24">Pago en efectivo al recibir</div>
                      <div style="font-size:0.82rem;color:var(--c-muted)">Ten lista la cantidad exacta. El repartidor confirmará el cobro.</div>
                    </div>
                  </div>
                </div>

                {{-- Transferencia --}}
                <div class="pago-detalle-panel" data-metodo="transferencia">
                  <p class="pago-instruccion">
                    <i class="bi bi-bank2 text-gold me-1"></i>
                    Realiza la transferencia a nuestra cuenta. El cajero validará tu comprobante.
                  </p>
                  <div class="mb-3">
                    <label class="tt-label">Banco</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-building tt-input-icon"></i>
                      <select name="datos_pago[banco]" class="tt-input tt-input-padded">
                        <option value="">Selecciona tu banco</option>
                        @foreach(['BCP','BBVA','Interbank','Scotiabank','BanBif','Pichincha','Nación'] as $b)
                          <option value="{{ $b }}" {{ old('datos_pago.banco') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="tt-label">Número de cuenta / CCI</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-hash tt-input-icon"></i>
                      <input type="text" name="datos_pago[numero_cuenta]" class="tt-input tt-input-padded"
                             placeholder="Número de cuenta de origen"
                             value="{{ old('datos_pago.numero_cuenta') }}">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="tt-label">Titular de la cuenta</label>
                    <div class="tt-input-icon-wrap">
                      <i class="bi bi-person tt-input-icon"></i>
                      <input type="text" name="datos_pago[titular]" class="tt-input tt-input-padded"
                             placeholder="Nombre del titular"
                             value="{{ old('datos_pago.titular') }}">
                    </div>
                  </div>
                </div>

              </div>{{-- /pago-detalles --}}
              @error('metodo_pago')
                <div style="color:#f87171;font-size:0.8rem;margin-top:0.5rem">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="checkout-submit-btn" id="btnPagar">
              <span class="checkout-submit-inner">
                <i class="bi bi-lock-fill"></i>
                <span>Realizar Pago del Pedido</span>
                <span class="checkout-submit-total">S/ {{ number_format($total, 2) }}</span>
              </span>
              <span class="checkout-submit-shine"></span>
            </button>
          </form>
        </div>

        <!-- Resumen del pedido -->
        <div class="col-lg-5">
          <div class="checkout-resumen sticky-top" style="top:90px">
            <div class="checkout-section-title mb-3">
              <i class="bi bi-bag-check-fill"></i> Tu Pedido
            </div>
            @foreach($carrito as $item)
              <div class="d-flex justify-content-between align-items-center mb-2 pb-2"
                   style="border-bottom:1px solid var(--c-border)">
                <div>
                  <div class="fw-bold" style="font-size:0.875rem">{{ $item['nombre'] }}</div>
                  <small style="color:var(--c-muted)">×{{ $item['cantidad'] }} — S/ {{ number_format($item['precio'], 2) }}</small>
                </div>
                <span class="fw-bold" style="color:var(--c-gold)">S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
              </div>
            @endforeach
            <div class="d-flex justify-content-between mt-3">
              <span class="fw-bold fs-5">Total</span>
              <span class="precio-tag">S/ {{ number_format($total, 2) }}</span>
            </div>
            <div class="mt-3 p-3 rounded-2 text-center"
                 style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2)">
              <small style="color:#86efac;font-weight:600">
                <i class="bi bi-shield-check me-1"></i>Transacción 100% segura
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const radios   = document.querySelectorAll('.pago-radio');
  const panels   = document.querySelectorAll('.pago-detalle-panel');
  const labels   = document.querySelectorAll('.pago-option-new');

  function mostrarPanel(metodo) {
    panels.forEach(p => {
      p.classList.toggle('activo', p.dataset.metodo === metodo);
      // Activar/desactivar required en los inputs del panel
      p.querySelectorAll('input,select').forEach(el => {
        if (p.dataset.metodo === metodo) {
          el.removeAttribute('disabled');
        } else {
          el.setAttribute('disabled', 'disabled');
        }
      });
    });
  }

  // Inicializar con el valor ya seleccionado (por old())
  const checked = document.querySelector('.pago-radio:checked');
  if (checked) mostrarPanel(checked.value);

  radios.forEach(radio => {
    radio.addEventListener('change', function () {
      labels.forEach(l => l.classList.remove('selected'));
      this.closest('.pago-option-new').classList.add('selected');
      mostrarPanel(this.value);
    });
  });
})();

// Formatear vencimiento MM/AA
const venc = document.querySelector('input[name="datos_pago[vencimiento]"]');
if (venc) {
  venc.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2, 4);
    this.value = v;
  });
}
</script>
@endpush
@endsection