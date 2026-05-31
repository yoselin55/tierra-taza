@extends('layouts.app')
@section('title', $producto->nombre)

@section('content')
<div class="container py-5">
  <!-- Breadcrumb -->
  <nav class="mb-4">
    <ol class="breadcrumb" style="background:transparent;padding:0">
      <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--c-gold)">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('catalogo') }}" style="color:var(--c-gold)">Catálogo</a></li>
      <li class="breadcrumb-item active" style="color:var(--c-muted)">{{ $producto->nombre }}</li>
    </ol>
  </nav>

  <div class="row g-5 mb-5">
    <!-- Imagen -->
    <div class="col-lg-5">
      <div class="rounded-4 overflow-hidden" style="height:420px;border:1px solid var(--c-border)">
        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}"
             class="w-100 h-100" style="object-fit:cover">
      </div>
    </div>

    <!-- Info -->
    <div class="col-lg-7">
      <span class="badge-tt badge-info mb-3">{{ $producto->categoria_label }}</span>
      <h1 style="font-family:'Playfair Display',serif;font-weight:700;margin-bottom:0.5rem">
        {{ $producto->nombre }}
      </h1>

      <!-- Rating -->
      <div class="d-flex align-items-center gap-2 mb-3">
        <div style="color:var(--c-gold)">
          @for($i=1;$i<=5;$i++)
            <i class="bi bi-star{{ $i<=$producto->rating?'-fill':'' }}"></i>
          @endfor
        </div>
        <span style="font-weight:700;color:var(--c-gold)">{{ number_format($producto->rating,1) }}</span>
        <span style="color:var(--c-muted);font-size:0.85rem">({{ $resenas->count() }} reseñas)</span>
      </div>

      <p style="color:var(--c-muted);line-height:1.8;margin-bottom:1.5rem">{{ $producto->descripcion }}</p>

      <div class="d-flex align-items-center gap-4 mb-4">
        <span style="font-size:2.5rem;font-weight:900;color:var(--c-gold)">
          S/ {{ number_format($producto->precio,2) }}
        </span>
        @if($producto->hayStock())
          <span class="badge-tt badge-success">
            <i class="bi bi-check-circle me-1"></i>Disponible ({{ $producto->stock }} und.)
          </span>
        @else
          <span class="badge-tt badge-danger">Sin stock</span>
        @endif
      </div>

      @if($producto->hayStock())
        <div class="d-flex gap-3 flex-wrap">
          {{-- CORRECTO: usa js-add-cart igual que home y catálogo --}}
          <button class="btn-primary-tt js-add-cart"
                  data-url="{{ route('carrito.agregar', $producto) }}">
            <i class="bi bi-bag-plus me-1"></i>Agregar al Carrito
          </button>
          <a href="{{ route('carrito') }}" class="btn-ghost-tt">
            <i class="bi bi-bag me-1"></i>Ir al Carrito
          </a>
        </div>
      @endif
    </div>
  </div>

  <!-- RESEÑAS -->
  <div class="row">
    <div class="col-lg-8">
      <h3 style="font-family:'Playfair Display',serif;font-weight:700;margin-bottom:1.5rem">
        Reseñas <span style="color:var(--c-gold)">({{ $resenas->count() }})</span>
      </h3>

      @auth
        @if($yaCompro && !$yaPusoResena)
          <div class="glass-card p-4 mb-4">
            <h5 style="font-weight:700;margin-bottom:1rem">Deja tu reseña</h5>
            <form action="{{ route('catalogo.resena', $producto) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="tt-label">Calificación</label>
                <div class="d-flex gap-2 fs-3" id="starRating">
                  @for($i=1;$i<=5;$i++)
                    <span class="star-input" data-val="{{ $i }}"
                          style="cursor:pointer;color:var(--c-border)">
                      <i class="bi bi-star-fill"></i>
                    </span>
                  @endfor
                </div>
                <input type="hidden" name="calificacion" id="calificacion" required>
              </div>
              <div class="mb-3">
                <label class="tt-label">Comentario</label>
                <textarea name="comentario" class="tt-input" rows="3"
                          placeholder="¿Cómo fue tu experiencia?"></textarea>
              </div>
              <button type="submit" class="btn-primary-tt">
                <i class="bi bi-send me-1"></i>Publicar Reseña
              </button>
            </form>
          </div>
        @elseif($yaPusoResena)
          <div class="glass-card p-3 mb-4" style="border-color:rgba(0,214,143,0.3)">
            <i class="bi bi-check-circle text-success me-2"></i>Ya dejaste una reseña para este producto.
          </div>
        @elseif(!$yaCompro)
          <div class="glass-card p-3 mb-4" style="border-color:rgba(255,149,0,0.3)">
            <i class="bi bi-lock me-2" style="color:var(--c-amber)"></i>Solo puedes reseñar productos que hayas comprado y recibido.
          </div>
        @endif
      @else
        <div class="glass-card p-3 mb-4">
          <a href="{{ route('login') }}" class="fw-bold" style="color:var(--c-gold)">Inicia sesión</a>
          <span style="color:var(--c-muted)"> para dejar tu reseña.</span>
        </div>
      @endauth

      @forelse($resenas as $resena)
        <div class="glass-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span style="font-weight:700;color:var(--c-text)">{{ $resena->user->nombre }}</span>
              <span style="color:var(--c-gold);margin-left:0.5rem">
                @for($i=1;$i<=5;$i++)
                  <i class="bi bi-star{{ $i<=$resena->calificacion?'-fill':'' }}" style="font-size:0.8rem"></i>
                @endfor
              </span>
            </div>
            <small style="color:var(--c-muted)">{{ $resena->fecha->diffForHumans() }}</small>
          </div>
          @if($resena->comentario)
            <p style="color:var(--c-muted);margin:0;font-size:0.9rem">{{ $resena->comentario }}</p>
          @endif
        </div>
      @empty
        <p style="color:var(--c-muted)">Aún no hay reseñas. ¡Sé el primero!</p>
      @endforelse
    </div>

    <!-- Relacionados -->
    <div class="col-lg-4">
      <h5 style="font-weight:700;margin-bottom:1rem">También te puede gustar</h5>
      @foreach($relacionados as $rel)
        <a href="{{ route('catalogo.show', $rel) }}" style="text-decoration:none">
          <div class="glass-card d-flex gap-3 p-2 mb-2" style="align-items:center">
            <img src="{{ $rel->imagen_url }}"
                 style="width:60px;height:60px;object-fit:cover;border-radius:10px;flex-shrink:0">
            <div>
              <div style="font-weight:700;font-size:0.875rem;color:var(--c-text)">{{ $rel->nombre }}</div>
              <div style="color:var(--c-gold);font-weight:800">S/ {{ number_format($rel->precio,2) }}</div>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</div>

@push('scripts')
<script>
  const stars = document.querySelectorAll('.star-input');
  const input = document.getElementById('calificacion');
  if (stars.length && input) {
    stars.forEach(star => {
      star.addEventListener('mouseover', function() {
        const val = +this.dataset.val;
        stars.forEach((s,i) => s.style.color = i < val ? 'var(--c-gold)' : 'var(--c-border)');
      });
      star.addEventListener('click', function() {
        input.value = this.dataset.val;
        stars.forEach((s,i) => s.style.color = i < +input.value ? 'var(--c-gold)' : 'var(--c-border)');
      });
    });
    document.getElementById('starRating')?.addEventListener('mouseleave', () => {
      stars.forEach((s,i) => s.style.color = i < (+input.value||0) ? 'var(--c-gold)' : 'var(--c-border)');
    });
  }
</script>
@endpush
@endsection