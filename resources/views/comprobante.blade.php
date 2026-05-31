<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comprobante #{{ $pago->pedido_id }} · Tierra y Taza</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
  <style>
    :root {
      --page-bg:      #ddd8cf;
      --page-bg2:     #c8c2b8;
      --paper:        #fdfcfa;
      --paper-border: rgba(0,0,0,0.07);
      --ink:          #1a1208;
      --ink-muted:    #6b5e4e;
      --ink-light:    #9c8e80;
      --divider:      #c8beae;
      --gold:         #9a6c10;
      --gold-bg:      rgba(154,108,16,0.08);
      --green:        #166534;
      --shadow:       0 8px 40px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.10);
      --btn-dark-bg:  #1a0e05;
      --btn-dark-fg:  #c8963c;
    }

    /* Dark mode — respeta el data-theme del sitio y también el sistema */
    html[data-theme="dark"],
    @media (prefers-color-scheme: dark) { html:not([data-theme="light"]) {
      --page-bg:      #120d07;
      --page-bg2:     #1e1509;
      --paper:        #fdfcfa;
      --paper-border: rgba(200,150,60,0.18);
      --ink:          #1a1208;
      --ink-muted:    #5a4e40;
      --shadow:       0 8px 60px rgba(0,0,0,0.55), 0 0 0 1px rgba(200,150,60,0.15), 0 0 40px -10px rgba(200,150,60,0.12);
    }}

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: var(--page-bg);
      background-image: radial-gradient(ellipse at 30% 0%, var(--page-bg2) 0%, transparent 60%);
      font-family: 'Share Tech Mono', 'Courier New', monospace;
      color: var(--ink);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.75rem 1rem 3rem;
    }

    /* ─── Botones de acción ─── */
    .action-bar {
      display: flex;
      gap: 0.6rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .btn-ac {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.55rem 1.1rem;
      border-radius: 8px;
      font-size: 0.82rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      border: none;
      font-family: 'Inter', sans-serif;
      transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-ac:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.22); }
    .btn-dark  { background: #1a0e05; color: #c8963c; }
    .btn-light { background: #fff;    color: #374151; border: 1px solid #d1d5db; }

    /* ─── Wrapper del ticket ─── */
    .ticket-wrap {
      width: 100%;
      max-width: 360px;
      position: relative;
      filter: drop-shadow(var(--shadow, 0 8px 40px rgba(0,0,0,0.2)));
    }

    /* Borde zigzag superior */
    .zz-top {
      display: block;
      width: 100%;
      height: 18px;
    }
    .zz-bottom {
      display: block;
      width: 100%;
      height: 18px;
      transform: rotate(180deg);
    }

    /* Cuerpo del ticket */
    .ticket {
      background: var(--paper);
      padding: 0 1.5rem;
      border-left: 1px solid var(--paper-border);
      border-right: 1px solid var(--paper-border);
      /* Textura sutil de papel */
      background-image:
        repeating-linear-gradient(
          0deg,
          transparent,
          transparent 27px,
          rgba(0,0,0,0.018) 28px
        );
    }

    /* ─── Secciones internas ─── */
    .t-head {
      padding: 1.25rem 0 1rem;
      text-align: center;
    }
    .t-logo {
      width: 72px;
      height: 72px;
      border-radius: 14px;
      object-fit: contain;
      background: #fff;
      display: block;
      margin: 0 auto 0.65rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .t-shop-name {
      font-family: 'Inter', sans-serif;
      font-size: 1.05rem;
      font-weight: 900;
      color: var(--ink);
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }
    .t-shop-sub {
      font-size: 0.7rem;
      color: var(--ink-muted);
      margin-top: 0.2rem;
      line-height: 1.6;
    }

    /* Separador de asteriscos */
    .t-stars {
      font-size: 0.72rem;
      color: var(--divider);
      letter-spacing: 0.1em;
      text-align: center;
      line-height: 1;
      padding: 0.6rem 0;
      user-select: none;
    }

    /* Separador de guiones */
    .t-dashes {
      border: none;
      border-top: 1px dashed var(--divider);
      margin: 0.55rem 0;
    }

    /* Título del documento */
    .t-doc-title {
      text-align: center;
      font-family: 'Inter', sans-serif;
      font-size: 0.78rem;
      font-weight: 800;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--ink);
      padding: 0.1rem 0 0.5rem;
    }
    .t-doc-ref {
      text-align: center;
      font-size: 0.68rem;
      color: var(--ink-muted);
      line-height: 1.7;
    }
    .t-doc-ref span { color: var(--gold); font-weight: 700; }

    /* Filas de datos */
    .t-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      font-size: 0.74rem;
      padding: 0.22rem 0;
      color: var(--ink);
      line-height: 1.3;
    }
    .t-row-lbl { color: var(--ink-muted); }
    .t-row-val { font-weight: 600; text-align: right; }
    .t-row-val.gold  { color: var(--gold); }
    .t-row-val.green {
      color: var(--green);
      font-family: 'Inter', sans-serif;
      font-weight: 700;
    }

    /* Cabecera de tabla de ítems */
    .t-items-head {
      display: flex;
      justify-content: space-between;
      font-size: 0.7rem;
      font-weight: 700;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: var(--ink-light);
      padding: 0.4rem 0 0.3rem;
    }

    /* Ítems */
    .t-item {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      font-size: 0.75rem;
      padding: 0.3rem 0;
      border-bottom: 1px dotted var(--divider);
      gap: 0.5rem;
    }
    .t-item:last-child { border-bottom: none; }
    .t-item-left { flex: 1; }
    .t-item-name { color: var(--ink); line-height: 1.3; }
    .t-item-qty  { font-size: 0.68rem; color: var(--gold); font-weight: 700; }
    .t-item-price {
      font-weight: 700;
      color: var(--ink);
      white-space: nowrap;
      text-align: right;
    }

    /* Total */
    .t-total-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      padding: 0.5rem 0 0.35rem;
    }
    .t-total-lbl {
      font-family: 'Inter', sans-serif;
      font-size: 0.95rem;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--ink);
    }
    .t-total-amt {
      font-family: 'Inter', sans-serif;
      font-size: 1.15rem;
      font-weight: 900;
      color: var(--gold);
      letter-spacing: -0.3px;
    }

    /* Sección de gracias */
    .t-thanks {
      text-align: center;
      font-family: 'Inter', sans-serif;
      padding: 0.75rem 0 0.5rem;
    }
    .t-thanks-title {
      font-size: 0.82rem;
      font-weight: 800;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--ink);
      margin-bottom: 0.3rem;
    }
    .t-thanks-sub {
      font-size: 0.64rem;
      color: var(--ink-muted);
      line-height: 1.7;
    }

    /* Código de barras */
    .t-barcode {
      display: flex;
      align-items: flex-end;
      justify-content: center;
      gap: 1px;
      height: 44px;
      padding: 0.6rem 0 0;
    }
    .t-barcode span {
      display: block;
      background: var(--ink);
      border-radius: 0.5px;
      opacity: 0.85;
    }
    .t-barcode-ref {
      text-align: center;
      font-size: 0.58rem;
      letter-spacing: 0.16em;
      color: var(--ink-light);
      padding-bottom: 0.6rem;
      margin-top: 0.3rem;
    }

    /* ─── Impresión ─── */
    @media print {
      .action-bar { display: none !important; }
      body { background: #f5f0ea !important; padding: 0.5cm; }
      .ticket-wrap { filter: none; }
      .ticket { background-image: none; }
    }
  </style>
</head>
<body>

{{-- Botones --}}
<div class="action-bar no-print">
  <button onclick="window.print()" class="btn-ac btn-dark">
    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
      <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
      <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
    </svg>
    Imprimir / Guardar PDF
  </button>
  <a href="{{ url()->previous() }}" class="btn-ac btn-light">
    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
    </svg>
    Volver
  </a>
</div>

<div class="ticket-wrap">

  {{-- Zigzag superior --}}
  @php
    $zzTop = 'M0,18 ';
    for ($i = 0; $i <= 360; $i += 12) {
        $zzTop .= 'L'.$i.','.($i % 24 === 0 ? 18 : 0).' ';
    }
    $zzTop .= 'L360,18 Z';
  @endphp
  <svg class="zz-top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 360 18" preserveAspectRatio="none">
    <path d="{{ $zzTop }}" fill="#fdfcfa"/>
  </svg>

  <div class="ticket">

    {{-- Cabecera --}}
    <div class="t-head">
      <img src="{{ asset('images/logo.jpg') }}" alt="Tierra y Taza" class="t-logo">
      <div class="t-shop-name">Tierra &amp; Taza</div>
      <div class="t-shop-sub">
        Café · Coworking · Experiencia<br>
        Lima, Perú
      </div>
    </div>

    <div class="t-stars">* * * * * * * * * * * * * * * * * *</div>

    <div class="t-doc-title">Comprobante de Pago</div>
    <div class="t-doc-ref">
      Ref: <span>{{ $pago->referencia }}</span><br>
      Fecha: {{ ($pago->aprobado_en ?? $pago->fecha)->format('d/m/Y') }}
      &nbsp;&nbsp; Hora: {{ ($pago->aprobado_en ?? $pago->fecha)->format('H:i') }}
    </div>

    <div class="t-stars">* * * * * * * * * * * * * * * * * *</div>

    {{-- Datos del cliente --}}
    <div class="t-row">
      <span class="t-row-lbl">Cliente</span>
      <span class="t-row-val">{{ $pago->pedido->nombre_cliente }}</span>
    </div>
    <div class="t-row">
      <span class="t-row-lbl">DNI</span>
      <span class="t-row-val">{{ $pago->pedido->dni_cliente }}</span>
    </div>
    <div class="t-row">
      <span class="t-row-lbl">N° Pedido</span>
      <span class="t-row-val gold">#{{ str_pad($pago->pedido_id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="t-row">
      <span class="t-row-lbl">Método pago</span>
      <span class="t-row-val">{{ $pago->pedido->metodo_pago_label }}</span>
    </div>

    <hr class="t-dashes">

    {{-- Cabecera de ítems --}}
    <div class="t-items-head">
      <span>Descripción</span>
      <span>Precio</span>
    </div>
    <hr class="t-dashes">

    {{-- Ítems --}}
    @foreach($pago->pedido->detalles as $d)
      <div class="t-item">
        <div class="t-item-left">
          <div class="t-item-name">{{ $d->producto->nombre }}</div>
          <div class="t-item-qty">{{ $d->cantidad }} unid. × S/ {{ number_format($d->precio, 2) }}</div>
        </div>
        <div class="t-item-price">S/ {{ number_format($d->precio * $d->cantidad, 2) }}</div>
      </div>
    @endforeach

    <hr class="t-dashes" style="margin-top:0.25rem">

    {{-- Subtotales --}}
    <div class="t-row">
      <span class="t-row-lbl">Subtotal</span>
      <span class="t-row-val">S/ {{ number_format($pago->pedido->total, 2) }}</span>
    </div>
    <div class="t-row">
      <span class="t-row-lbl">Delivery</span>
      <span class="t-row-val green">Gratis</span>
    </div>

    {{-- Total --}}
    <hr class="t-dashes">
    <div class="t-total-row">
      <span class="t-total-lbl">Total</span>
      <span class="t-total-amt">S/ {{ number_format($pago->pedido->total, 2) }}</span>
    </div>

    <div class="t-row" style="padding-bottom:0.1rem">
      <span class="t-row-lbl">Estado</span>
      <span class="t-row-val green">&#10003; APROBADO</span>
    </div>

    @if($pago->notas_cajero)
      <div class="t-row" style="flex-direction:column;gap:0.1rem;padding-top:0.4rem">
        <span class="t-row-lbl">Nota del cajero:</span>
        <span style="font-size:0.72rem;color:var(--ink)">{{ $pago->notas_cajero }}</span>
      </div>
    @endif

    <div class="t-stars" style="margin-top:0.5rem">* * * * * * * * * * * * * * * * * *</div>

    {{-- Gracias --}}
    <div class="t-thanks">
      <div class="t-thanks-title">¡Gracias por tu preferencia!</div>
      <div class="t-thanks-sub">
        Este comprobante es válido como<br>
        prueba de pago oficial.<br>
        Tierra &amp; Taza · {{ now()->format('Y') }}
      </div>
    </div>

    {{-- Código de barras --}}
    <div class="t-barcode" aria-hidden="true">
      @php
        $bars = [2,1,3,1,2,2,1,3,1,1,2,3,1,2,1,3,2,1,1,3,2,1,2,1,3,1,2,3,1,1,2,1,3,2,1,3,1,2,1,2,3,1,2,1,1,3,2,1];
        foreach($bars as $i => $w):
          $h = ($i%5===0)?44:(($i%5===1)?28:(($i%5===2)?38:(($i%5===3)?22:34)));
      @endphp
        <span style="width:{{ $w }}px;height:{{ $h }}px"></span>
      @php endforeach; @endphp
    </div>
    <div class="t-barcode-ref">{{ $pago->referencia }}</div>

  </div>{{-- /ticket --}}

  {{-- Zigzag inferior --}}
  @php
    $pts2 = 'M0,0 ';
    for($i=0;$i<=360;$i+=12){ $pts2 .= 'L'.$i.','.($i%24==0?0:18).' '; }
    $pts2 .= 'L360,0 Z';
  @endphp
  <svg class="zz-bottom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 360 18" preserveAspectRatio="none">
    <path d="{{ $pts2 }}" fill="#fdfcfa"/>
  </svg>

</div>{{-- /ticket-wrap --}}

<script>
  // Sincronizar el data-theme del sitio si existe en localStorage
  (function() {
    const saved = localStorage.getItem('tt-theme');
    if (saved) document.getElementById('html-root').setAttribute('data-theme', saved);
  })();
</script>
</body>
</html>
