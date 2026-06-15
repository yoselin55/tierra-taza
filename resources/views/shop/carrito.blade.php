@extends('layouts.app')
@section('title', 'Mi Carrito')

@section('content')
<div class="container py-5">

  <!-- Encabezado de página -->
  <div class="carrito-page-header reveal">
    <div class="page-header-icon">
      <i class="bi bi-bag-fill"></i>
    </div>
    <div>
      <h1 class="carrito-page-title">Mi Carrito</h1>
      @if(!empty($carrito))
        <span class="carrito-page-count">{{ array_sum(array_column($carrito,'cantidad')) }} producto(s)</span>
      @endif
    </div>
  </div>

  @if(empty($carrito))
    <div class="carrito-empty reveal">
      <div class="carrito-empty-icon">
        <i class="bi bi-cart-x"></i>
      </div>
      <h3>Tu carrito está vacío</h3>
      <p style="color:var(--c-muted);margin-bottom:2rem">Agrega productos de nuestro catálogo para empezar</p>
      <a href="{{ route('catalogo') }}" class="btn-primary-tt">
        <i class="bi bi-grid-fill"></i> Explorar Catálogo
      </a>
    </div>

  @else
    <div class="row g-4">

      <!-- Lista de productos -->
      <div class="col-lg-8">
        @foreach($carrito as $id => $item)
          <div class="carrito-item" data-id="{{ $id }}">
            <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}"
                 onerror="this.src='https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=100'">
            <div class="flex-grow-1">
              <div style="font-weight:700;font-size:0.95rem;margin-bottom:0.2rem">{{ $item['nombre'] }}</div>
              <div style="color:var(--c-muted);font-size:0.8rem">S/ {{ number_format($item['precio'], 2) }} c/u</div>
            </div>
            <div class="qty-control">
              <button class="qty-btn qty-btn-minus" data-id="{{ $id }}" data-accion="menos">
                <i class="bi bi-dash"></i>
              </button>
              <input type="number" class="qty-input" value="{{ $item['cantidad'] }}" data-id="{{ $id }}" min="0" readonly>
              <button class="qty-btn qty-btn-plus" data-id="{{ $id }}" data-accion="mas">
                <i class="bi bi-plus"></i>
              </button>
            </div>
            <div class="text-end" style="min-width:90px">
              <div class="precio-tag subtotal" data-id="{{ $id }}">
                S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}
              </div>
            </div>
            <button class="btn-danger-tt btn-eliminar-item" data-id="{{ $id }}" title="Eliminar">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        @endforeach

        <!-- Acciones del carrito -->
        <div class="carrito-acciones mt-2">
          <a href="{{ route('catalogo') }}" class="btn-neutral-tt">
            <i class="bi bi-arrow-left"></i> Seguir Comprando
          </a>
          <form action="{{ route('carrito.vaciar') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger-tt"
                    onclick="return confirm('¿Deseas vaciar el carrito?')">
              <i class="bi bi-trash"></i> Vaciar Carrito
            </button>
          </form>
        </div>
      </div>

      <!-- Resumen del pedido -->
      <div class="col-lg-4">
        <div class="carrito-resumen reveal">
          <div class="carrito-resumen-title">
            <i class="bi bi-receipt me-2" style="color:var(--c-gold)"></i>Resumen del Pedido
          </div>

          <div class="carrito-resumen-row">
            <span class="label">Subtotal</span>
            <span class="valor carrito-total">S/ {{ number_format($total, 2) }}</span>
          </div>
          <div class="carrito-resumen-row">
            <span class="label">Envío</span>
            <span class="valor" style="color:var(--c-green)">
              <i class="bi bi-check-circle-fill me-1" style="font-size:0.75rem"></i>Gratis
            </span>
          </div>

          <div class="carrito-resumen-total">
            <span class="label">Total</span>
            <span class="valor carrito-total">S/ {{ number_format($total, 2) }}</span>
          </div>

          <div class="mt-4">
            @auth
              <a href="{{ route('pedidos.checkout') }}" class="btn-primary-tt w-100 justify-content-center"
                 style="border-radius:var(--radius-sm);padding:1rem">
                <i class="bi bi-bag-check-fill"></i> Confirmar Pedido
              </a>
            @else
              <a href="{{ route('login') }}" class="btn-primary-tt w-100 justify-content-center"
                 style="border-radius:var(--radius-sm);padding:1rem">
                <i class="bi bi-person-fill"></i> Inicia Sesión para Pagar
              </a>
            @endauth
          </div>

          <div class="text-center mt-3">
            <span style="font-size:0.775rem;color:var(--c-muted)">
              <i class="bi bi-shield-check me-1" style="color:var(--c-green)"></i>
              Pago 100% seguro y protegido
            </span>
          </div>
        </div>
      </div>

    </div>
  @endif
</div>

{{-- app.js maneja qty-btn y btn-eliminar-item vía event delegation --}}
@endsection
