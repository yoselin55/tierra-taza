@extends('layouts.app')
@section('title', 'Mi Carrito')

@section('content')
<div class="container py-5">
  <h1 class="fw-bold mb-4"><i class="bi bi-cart3 me-2" style="color:var(--c-gold)"></i>Mi Carrito</h1>

  @if(empty($carrito))
    <div class="text-center py-5">
      <div style="font-size:3rem;color:var(--c-muted)"><i class="bi bi-cart-x"></i></div>
      <h3 class="mt-3 fw-bold">Tu carrito está vacío</h3>
      <p class="text-muted">Agrega productos de nuestro catálogo</p>
      <a href="{{ route('catalogo') }}" class="btn-cafe btn btn-lg mt-3">
        <i class="bi bi-grid me-2"></i>Ver Catálogo
      </a>
    </div>
  @else
    <div class="row g-4">
      <!-- Lista items -->
      <div class="col-lg-8">
        @foreach($carrito as $id => $item)
          <div class="carrito-item" data-id="{{ $id }}">
            <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}"
                 onerror="this.src='https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=100'">
            <div class="flex-grow-1">
              <h6 class="fw-bold mb-0">{{ $item['nombre'] }}</h6>
              <small class="text-muted">S/ {{ number_format($item['precio'], 2) }} c/u</small>
            </div>
            <div class="qty-control">
              <button class="qty-btn qty-btn-minus" data-id="{{ $id }}" data-accion="menos">−</button>
              <input type="number" class="qty-input" value="{{ $item['cantidad'] }}" data-id="{{ $id }}" min="0" readonly>
              <button class="qty-btn qty-btn-plus" data-id="{{ $id }}" data-accion="mas">+</button>
            </div>
            <div class="text-end" style="min-width:90px">
              <div class="precio-tag subtotal" data-id="{{ $id }}">
                S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}
              </div>
            </div>
            <button class="btn btn-sm btn-outline-danger btn-eliminar-item" data-id="{{ $id }}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        @endforeach

        <div class="d-flex justify-content-between mt-3">
          <a href="{{ route('catalogo') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Seguir Comprando
          </a>
          <form action="{{ route('carrito.vaciar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger"
                    onclick="return confirm('¿Vaciar carrito?')">
              <i class="bi bi-trash me-2"></i>Vaciar Carrito
            </button>
          </form>
        </div>
      </div>

      <!-- Resumen -->
      <div class="col-lg-4">
        <div class="p-4 rounded-3 sticky-top" style="top:80px;background:var(--c-surface);border:1px solid var(--c-border)">
          <h5 class="fw-bold mb-4">Resumen del Pedido</h5>

          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal</span>
            <span class="carrito-total fw-bold">S/ {{ number_format($total, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Delivery</span>
            <span class="text-success fw-bold">Gratis</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-4">
            <span class="fw-bold fs-5">Total</span>
            <span class="precio-tag carrito-total">S/ {{ number_format($total, 2) }}</span>
          </div>

          @auth
            <a href="{{ route('pedidos.checkout') }}" class="btn-cafe btn w-100 btn-lg">
              <i class="bi bi-bag-check-fill me-2"></i>Confirmar Pedido
            </a>
          @else
            <a href="{{ route('login') }}" class="btn-cafe btn w-100 btn-lg">
              <i class="bi bi-person me-2"></i>Inicia Sesión para Pagar
            </a>
          @endauth

          <div class="mt-3 text-center">
            <small class="text-muted">
              <i class="bi bi-shield-check text-success me-1"></i>
              Pago 100% seguro
            </small>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

@push('scripts')
<script>
  // Conectar los botones +/- del carrito (ya definidos en app.js pero reafirmamos el binding)
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const itemId  = this.dataset.id;
      const accion  = this.dataset.accion;
      const input   = document.querySelector(`.qty-input[data-id="${itemId}"]`);
      let qty = parseInt(input.value);
      if (accion === 'mas') qty++;
      if (accion === 'menos') qty = Math.max(0, qty - 1);
      input.value = qty;

      const resp = await fetch(`/carrito/actualizar/${itemId}`, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cantidad: qty })
      });
      const data = await resp.json();
      if (data.success) {
        document.querySelectorAll('.carrito-total').forEach(el => el.textContent = `S/ ${data.total}`);
        const sub = document.querySelector(`.subtotal[data-id="${itemId}"]`);
        if (sub) sub.textContent = `S/ ${data.subtotal}`;
        const badge = document.querySelector('.badge-count');
        if (badge) badge.textContent = data.count;
        if (qty === 0) {
          const row = document.querySelector(`.carrito-item[data-id="${itemId}"]`);
          if (row) { row.style.opacity = '0'; setTimeout(() => { row.remove(); if (data.count === 0) location.reload(); }, 300); }
        }
      }
    });
  });

  document.querySelectorAll('.btn-eliminar-item').forEach(btn => {
    btn.addEventListener('click', async function() {
      const itemId = this.dataset.id;
      const resp = await fetch(`/carrito/eliminar/${itemId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
      });
      const data = await resp.json();
      if (data.success) {
        const row = document.querySelector(`.carrito-item[data-id="${itemId}"]`);
        if (row) { row.style.opacity = '0'; setTimeout(() => { row.remove(); if (data.count === 0) location.reload(); }, 300); }
        document.querySelectorAll('.carrito-total').forEach(el => el.textContent = `S/ ${data.total}`);
        const badge = document.querySelector('.badge-count');
        if (badge) badge.textContent = data.count;
      }
    });
  });
</script>
@endpush
@endsection
