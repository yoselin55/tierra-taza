@extends('layouts.app')
@section('title', 'Boleta #' . $pedido->id)

@push('styles')
<style>
  /* ── Fondo de página ── */
  .boleta-page {
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2.5rem 1rem 3.5rem;
    background: transparent;
  }

  /* ── Wrapper del ticket (drop-shadow incluye el zigzag) ── */
  .ticket-wrap {
    width: 100%;
    max-width: 380px;
    filter:
      drop-shadow(0 2px 0 rgba(0,0,0,0.12))
      drop-shadow(0 12px 32px rgba(0,0,0,0.18));
    transition: filter 0.3s;
  }
  [data-theme="dark"] .ticket-wrap {
    filter:
      drop-shadow(0 0 0 rgba(200,150,60,0.25))
      drop-shadow(0 16px 48px rgba(0,0,0,0.55))
      drop-shadow(0 0 28px rgba(200,150,60,0.08));
  }

  /* Zigzag SVG */
  .zz-svg { display: block; width: 100%; height: 16px; }
  .zz-bottom { transform: scaleY(-1); }

  /* ── Papel del ticket ── */
  .ticket-paper {
    background: #fdfcfa;
    border-left: 1px solid rgba(0,0,0,0.06);
    border-right: 1px solid rgba(0,0,0,0.06);
    padding: 0 1.5rem;
    /* Líneas de papel térmico */
    background-image: repeating-linear-gradient(
      0deg, transparent, transparent 26px, rgba(0,0,0,0.022) 27px
    );
    font-family: 'Share Tech Mono', 'Courier New', monospace;
    color: #1a1208;
  }

  /* ── Cabecera del ticket ── */
  .tk-head {
    padding: 1.4rem 0 1rem;
    text-align: center;
  }
  .tk-logo {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    object-fit: contain;
    background: #fff;
    display: block;
    margin: 0 auto 0.7rem;
    padding: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.12);
  }
  .tk-shop {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #1a1208;
  }
  .tk-shop-sub {
    font-size: 0.68rem;
    color: #8a7a6a;
    margin-top: 0.2rem;
    line-height: 1.65;
  }

  /* Separador asteriscos */
  .tk-stars {
    text-align: center;
    font-size: 0.7rem;
    color: #c8beae;
    letter-spacing: 0.08em;
    padding: 0.55rem 0;
    line-height: 1;
    user-select: none;
  }

  /* Separador guiones */
  .tk-dash { border: none; border-top: 1px dashed #d1ccc4; margin: 0.5rem 0; }

  /* Título del documento */
  .tk-doc-title {
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #1a1208;
    padding: 0.1rem 0 0.45rem;
  }
  .tk-doc-meta {
    text-align: center;
    font-size: 0.68rem;
    color: #8a7a6a;
    line-height: 1.75;
  }
  .tk-doc-meta b { color: #9a6c10; font-weight: 700; }

  /* Filas de datos */
  .tk-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 0.74rem;
    padding: 0.22rem 0;
    color: #1a1208;
    gap: 0.5rem;
  }
  .tk-lbl { color: #8a7a6a; flex-shrink: 0; }
  .tk-val { font-weight: 600; text-align: right; word-break: break-all; }
  .tk-val.gold  { color: #9a6c10; }
  .tk-val.green { color: #15803d; font-family: 'Inter', sans-serif; font-weight: 700; }

  /* Cabecera de tabla */
  .tk-items-head {
    display: flex;
    justify-content: space-between;
    font-size: 0.68rem;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: #a89880;
    padding: 0.35rem 0 0.3rem;
  }

  /* Ítem */
  .tk-item {
    padding: 0.35rem 0;
    border-bottom: 1px dotted #d1ccc4;
    font-size: 0.75rem;
  }
  .tk-item:last-child { border-bottom: none; }
  .tk-item-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
  }
  .tk-item-name { color: #1a1208; font-weight: 600; line-height: 1.3; flex: 1; }
  .tk-item-total { font-weight: 700; color: #1a1208; white-space: nowrap; }
  .tk-item-qty { font-size: 0.67rem; color: #9a6c10; font-weight: 700; margin-top: 0.1rem; }

  /* Total */
  .tk-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 0.5rem 0 0.3rem;
  }
  .tk-total-lbl {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #1a1208;
  }
  .tk-total-amt {
    font-family: 'Inter', sans-serif;
    font-size: 1.15rem;
    font-weight: 900;
    color: #9a6c10;
    letter-spacing: -0.3px;
  }

  /* Estado pago */
  .tk-estado {
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.3rem 0;
  }

  /* Gracias */
  .tk-thanks {
    text-align: center;
    padding: 0.65rem 0 0.5rem;
  }
  .tk-thanks-title {
    font-family: 'Inter', sans-serif;
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #1a1208;
    margin-bottom: 0.28rem;
  }
  .tk-thanks-sub {
    font-size: 0.64rem;
    color: #8a7a6a;
    line-height: 1.75;
  }

  /* Código de barras */
  .tk-barcode {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 1px;
    height: 40px;
    padding-top: 0.55rem;
    opacity: 0.8;
  }
  .tk-barcode span { display: block; background: #1a1208; border-radius: 0.5px; }
  .tk-barcode-ref {
    text-align: center;
    font-size: 0.56rem;
    letter-spacing: 0.14em;
    color: #b0a090;
    padding-bottom: 0.65rem;
    margin-top: 0.25rem;
  }

  /* ── Botones de acción ── */
  .boleta-actions {
    display: flex;
    gap: 0.6rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 1.5rem;
  }

  /* Impresión */
  @media print {
    .boleta-actions, nav, header, .adm-topbar { display: none !important; }
    .boleta-page { padding: 0; }
    .ticket-wrap { filter: none; max-width: 100%; }
    .ticket-paper { background-image: none; border: none; }
  }
</style>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="boleta-page">

  {{-- ── TICKET ── --}}
  <div class="ticket-wrap">

    {{-- Zigzag superior --}}
    @php
      $zz = 'M0,16 ';
      for ($i = 0; $i <= 380; $i += 12) {
          $zz .= 'L'.$i.','.($i % 24 === 0 ? 16 : 0).' ';
      }
      $zz .= 'L380,16 Z';
    @endphp
    <svg class="zz-svg" viewBox="0 0 380 16" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="{{ $zz }}" fill="#fdfcfa"/>
    </svg>

    <div class="ticket-paper">

      {{-- Cabecera --}}
      <div class="tk-head">
        <img src="{{ asset('images/logo.jpg') }}" alt="Tierra y Taza" class="tk-logo">
        <div class="tk-shop">Tierra &amp; Taza</div>
        <div class="tk-shop-sub">
          Av. Larco 1234, Miraflores, Lima<br>
          RUC: 20612345678 · www.tierraytaza.pe
        </div>
      </div>

      <div class="tk-stars">* * * * * * * * * * * * * * * * *</div>

      <div class="tk-doc-title">Boleta de Venta</div>
      <div class="tk-doc-meta">
        N° <b>{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</b>
      </div>

      <div class="tk-stars">* * * * * * * * * * * * * * * * *</div>

      {{-- Datos --}}
      <div class="tk-row">
        <span class="tk-lbl">Fecha</span>
        <span class="tk-val">{{ $pedido->fecha->format('d/m/Y H:i') }}</span>
      </div>
      <div class="tk-row">
        <span class="tk-lbl">Cliente</span>
        <span class="tk-val">{{ $pedido->nombre_cliente }}</span>
      </div>
      <div class="tk-row">
        <span class="tk-lbl">DNI</span>
        <span class="tk-val">{{ $pedido->dni_cliente }}</span>
      </div>
      @if($pedido->pago)
        <div class="tk-row">
          <span class="tk-lbl">Referencia</span>
          <span class="tk-val gold" style="font-size:0.68rem">{{ $pedido->pago->referencia }}</span>
        </div>
      @endif

      <hr class="tk-dash">

      {{-- Cabecera ítems --}}
      <div class="tk-items-head">
        <span>Descripción</span>
        <span>Precio</span>
      </div>
      <hr class="tk-dash" style="margin-top:0">

      {{-- Ítems --}}
      @foreach($pedido->detalles as $detalle)
        <div class="tk-item">
          <div class="tk-item-top">
            <span class="tk-item-name">{{ $detalle->producto->nombre }}</span>
            <span class="tk-item-total">S/ {{ number_format($detalle->subtotal, 2) }}</span>
          </div>
          <div class="tk-item-qty">{{ $detalle->cantidad }} × S/ {{ number_format($detalle->precio, 2) }}</div>
        </div>
      @endforeach

      <hr class="tk-dash">

      {{-- Subtotales --}}
      <div class="tk-row">
        <span class="tk-lbl">Subtotal</span>
        <span class="tk-val">S/ {{ number_format($pedido->total / 1.18, 2) }}</span>
      </div>
      <div class="tk-row">
        <span class="tk-lbl">IGV (18%)</span>
        <span class="tk-val">S/ {{ number_format($pedido->total - ($pedido->total / 1.18), 2) }}</span>
      </div>
      <div class="tk-row">
        <span class="tk-lbl">Delivery</span>
        <span class="tk-val green">Gratis</span>
      </div>

      <hr class="tk-dash">

      {{-- Total --}}
      <div class="tk-total">
        <span class="tk-total-lbl">Total</span>
        <span class="tk-total-amt">S/ {{ number_format($pedido->total, 2) }}</span>
      </div>

      {{-- Estado del pago --}}
      @if($pedido->pago)
        <div class="tk-estado" style="color:{{ $pedido->pago->estado === 'completado' ? '#15803d' : ($pedido->pago->estado === 'rechazado' ? '#b91c1c' : '#b45309') }}">
          @if($pedido->pago->estado === 'completado')
            ✓ Pago Aprobado
          @elseif($pedido->pago->estado === 'rechazado')
            ✗ Pago Rechazado
          @else
            ⏳ Pendiente de validación
          @endif
        </div>
      @endif

      <div class="tk-stars">* * * * * * * * * * * * * * * * *</div>

      {{-- Método de pago --}}
      <div class="tk-row" style="justify-content:center;gap:0.3rem">
        <span class="tk-lbl">Método de pago:</span>
        <span class="tk-val">{{ $pedido->metodo_pago_label }}</span>
      </div>

      {{-- Gracias --}}
      <div class="tk-thanks">
        <div class="tk-thanks-title">¡Gracias por tu preferencia!</div>
        <div class="tk-thanks-sub">
          Guarda este comprobante como referencia.<br>
          Tierra &amp; Taza · {{ now()->format('Y') }} · Lima, Perú
        </div>
      </div>

      {{-- Código de barras --}}
      <div class="tk-barcode" aria-hidden="true">
        @php
          $bars = [2,1,3,1,2,2,1,3,1,1,2,3,1,2,1,3,2,1,1,3,2,1,2,1,3,1,2,3,1,1,2,1,3,2,1,3,1,2,1,2,3,1,2,1,1,3,2,1];
          foreach ($bars as $i => $w):
            $h = match($i % 5) { 0 => 40, 1 => 26, 2 => 34, 3 => 20, default => 30 };
        @endphp
          <span style="width:{{ $w }}px;height:{{ $h }}px"></span>
        @php endforeach; @endphp
      </div>
      @if($pedido->pago)
        <div class="tk-barcode-ref">{{ $pedido->pago->referencia }}</div>
      @else
        <div class="tk-barcode-ref">PEDIDO #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</div>
      @endif

    </div>{{-- /ticket-paper --}}

    {{-- Zigzag inferior --}}
    @php
      $zz2 = 'M0,0 ';
      for ($i = 0; $i <= 380; $i += 12) {
          $zz2 .= 'L'.$i.','.($i % 24 === 0 ? 0 : 16).' ';
      }
      $zz2 .= 'L380,0 Z';
    @endphp
    <svg class="zz-svg zz-bottom" viewBox="0 0 380 16" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="{{ $zz2 }}" fill="#fdfcfa"/>
    </svg>

  </div>{{-- /ticket-wrap --}}

  {{-- Botones --}}
  <div class="boleta-actions no-print">
    <button onclick="window.print()" class="btn-ghost-tt" style="gap:0.4rem;padding:0.55rem 1.1rem">
      <i class="bi bi-printer"></i> Imprimir
    </button>
    @if($pedido->pago && $pedido->pago->estado === 'completado')
      <a href="{{ route('pedidos.comprobante', $pedido) }}" target="_blank"
         class="btn-ghost-tt" style="gap:0.4rem;padding:0.55rem 1.1rem;color:var(--c-green)">
        <i class="bi bi-file-earmark-check"></i> Comprobante oficial
      </a>
    @endif
    <a href="{{ route('pedidos.mis_pedidos') }}" class="btn-add" style="gap:0.4rem">
      <i class="bi bi-bag"></i> Mis Pedidos
    </a>
    <a href="{{ route('catalogo') }}" class="btn-ghost-tt" style="gap:0.4rem;padding:0.55rem 1.1rem">
      <i class="bi bi-grid"></i> Seguir Comprando
    </a>
  </div>

</div>
@endsection
