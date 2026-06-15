/* Tierra y Taza — app.js  v3.2 */
document.documentElement.classList.add('js');

function getCsrf() {
  const m = document.querySelector('meta[name="csrf-token"]');
  return m ? m.content : '';
}
function csrfHeaders() {
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrf(),
  };
}

/* ── BADGE ── el span tiene display:none inline, hay que poner 'flex' */
function updateCartBadge(count) {
  document.querySelectorAll('.cart-count, .badge-count').forEach(function(el) {
    el.textContent = count;
    el.style.display = count > 0 ? 'flex' : 'none';
  });
}

/* ── TOAST ─────────────────────────────────────────────────── */
window.toast = function(msg, type) {
  type = type || 'ok';
  let box = document.getElementById('tt-toast-box');
  if (!box) {
    box = document.createElement('div');
    box.id = 'tt-toast-box';
    Object.assign(box.style, {
      position:'fixed', bottom:'1.5rem', right:'1.5rem',
      zIndex:'99999', display:'flex', flexDirection:'column',
      gap:'0.5rem', pointerEvents:'none',
    });
    document.body.appendChild(box);
  }
  const t = document.createElement('div');
  const ok   = type === 'ok';
  const info = type === 'info';
  const bg    = ok ? 'rgba(20,40,20,0.97)'   : info ? 'rgba(15,25,45,0.97)'   : 'rgba(40,15,15,0.97)';
  const bdr   = ok ? '#22c55e'               : info ? '#60a5fa'               : '#ef4444';
  const clr   = ok ? '#86efac'               : info ? '#bfdbfe'               : '#fca5a5';
  const icon  = ok ? 'bi-check-circle-fill'  : info ? 'bi-bell-fill'          : 'bi-x-circle-fill';
  Object.assign(t.style, {
    background: bg, border: '1px solid ' + bdr, color: clr,
    padding: '0.75rem 1.25rem', borderRadius: '12px',
    fontSize: '0.875rem', fontWeight: '500',
    display: 'flex', alignItems: 'center', gap: '0.5rem',
    boxShadow: '0 4px 24px rgba(0,0,0,0.5)',
    transition: 'opacity 0.35s, transform 0.35s',
    opacity: '0', transform: 'translateX(20px)',
  });
  t.innerHTML = '<i class="bi ' + icon + '"></i><span>' + msg + '</span>';
  box.appendChild(t);
  requestAnimationFrame(function() {
    t.style.opacity = '1'; t.style.transform = 'translateX(0)';
  });
  setTimeout(function() {
    t.style.opacity = '0'; t.style.transform = 'translateX(20px)';
    setTimeout(function() { t.remove(); }, 350);
  }, 3800);
};

/* ── AGREGAR AL CARRITO ─────────────────────────────────────── */
async function addToCart(btn) {
  const url = btn.dataset.url;
  if (!url) return;
  const origHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
  try {
    const resp = await fetch(url, { method: 'POST', headers: csrfHeaders() });
    const ct = resp.headers.get('content-type') || '';
    if (!ct.includes('application/json')) {
      toast(resp.status === 419 ? 'Sesión expirada. Recarga.' : 'Error ' + resp.status, 'err');
      btn.innerHTML = origHtml; btn.disabled = false;
      return;
    }
    const data = await resp.json();
    if (data.success) {
      updateCartBadge(data.count);
      btn.innerHTML = '<i class="bi bi-check-lg"></i> Listo';
      btn.style.background = 'rgba(34,197,94,0.25)';
      btn.style.borderColor = '#22c55e';
      btn.style.color = '#86efac';
      toast(data.message || 'Producto agregado', 'ok');
      setTimeout(function() {
        btn.innerHTML = origHtml;
        btn.style.cssText = '';
        btn.disabled = false;
      }, 2200);
    } else {
      toast(data.error || 'No se pudo agregar', 'err');
      btn.innerHTML = origHtml; btn.disabled = false;
    }
  } catch(err) {
    toast('Error de conexión', 'err');
    btn.innerHTML = origHtml; btn.disabled = false;
  }
}

/* Event delegation en fase de captura */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.js-add-cart');
  if (!btn) return;
  e.preventDefault();
  e.stopPropagation();
  addToCart(btn);
}, true);

/* ── DOM READY ──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {

  /* Dark / Light theme */
  const root = document.documentElement;
  const saved = localStorage.getItem('tt-theme') || 'dark';
  root.setAttribute('data-theme', saved);
  setThemeIcon(saved);
  document.querySelectorAll('[data-theme-toggle]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      localStorage.setItem('tt-theme', next);
      setThemeIcon(next);
    });
  });
  function setThemeIcon(theme) {
    document.querySelectorAll('[data-theme-toggle]').forEach(function(btn) {
      btn.innerHTML = theme === 'dark'
        ? '<i class="bi bi-sun-fill"></i> Claro'
        : '<i class="bi bi-moon-fill"></i> Oscuro';
    });
  }

  /* Scroll reveal */
  const io = new IntersectionObserver(function(entries) {
    entries.forEach(function(e, idx) {
      if (e.isIntersecting) {
        setTimeout(function() { e.target.classList.add('visible'); }, idx * 80);
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.reveal').forEach(function(el) { io.observe(el); });

  /* Parallax hero */
  const heroBg = document.querySelector('.hero-bg');
  if (heroBg) {
    window.addEventListener('scroll', function() {
      heroBg.style.transform = 'scale(1.05) translateY(' + (window.scrollY * 0.2) + 'px)';
    }, { passive: true });
  }

  /* Navbar scroll — add .scrolled class */
  const navbar = document.querySelector('.navbar-tierra');
  if (navbar) {
    window.addEventListener('scroll', function() {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });
  }

  /* Smooth page exit on navigation */
  (function () {
    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href]');
      if (!a || e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey) return;
      const href = a.getAttribute('href');
      if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript:')) return;
      if (a.target === '_blank' || a.download) return;
      if (a.hasAttribute('data-bs-toggle') || a.hasAttribute('data-bs-dismiss') || a.closest('.dropdown-menu')) return;
      if (a.closest('form')) return;
      e.preventDefault();
      window.location.href = href;
    });

    /* Resetear opacidad antes de que bfcache guarde la pagina */
    window.addEventListener('pagehide', function () {
      document.body.style.opacity = '';
      document.body.style.transition = '';
    });

    /* Restaurar si bfcache devuelve una pagina con estado viejo */
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) {
        document.body.style.opacity = '';
        document.body.style.transition = '';
        document.querySelectorAll('.reveal').forEach(function (el) {
          el.classList.add('visible');
        });
      }
    });

  })();

  /* Cerrar menu movil al pasar a desktop */
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 992) {
      var mobileNav = document.getElementById('mobileNav');
      if (mobileNav && mobileNav.classList.contains('show')) {
        var bsCollapse = bootstrap.Collapse.getInstance(mobileNav);
        if (bsCollapse) bsCollapse.hide();
        else mobileNav.classList.remove('show');
      }
    }
  }, { passive: true });

  /* Carrusel categorías home */
  const cards = Array.from(document.querySelectorAll('.cat-card'));
  if (cards.length) {
    let current = 0;
    function activateCard(idx) {
      cards.forEach(function(c) { c.classList.remove('color-active'); });
      cards[idx].classList.add('color-active');
      current = idx;
    }
    let autoTimer = setInterval(function() {
      activateCard((current + 1) % cards.length);
    }, 2500);
    cards.forEach(function(card, i) {
      card.addEventListener('click', function(e) {
        e.preventDefault();
        clearInterval(autoTimer);
        activateCard(i);
        setTimeout(function() { window.location.href = card.href; }, 380);
      });
    });
    activateCard(0);
  }

  /* Carrito — cantidad */
  document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.qty-btn-minus, .qty-btn-plus');
    if (!btn) return;
    const itemId = btn.dataset.id;
    const input = document.querySelector('.qty-input[data-id="' + itemId + '"]');
    if (!input) return;
    let qty = parseInt(input.value) || 0;
    if (btn.classList.contains('qty-btn-plus'))  qty++;
    if (btn.classList.contains('qty-btn-minus')) qty = Math.max(0, qty - 1);
    input.value = qty;
    try {
      const r = await fetch('/carrito/actualizar/' + itemId, {
        method: 'PATCH', headers: csrfHeaders(), body: JSON.stringify({ cantidad: qty })
      });
      const d = await r.json();
      if (d.success) {
        updateCartBadge(d.count);
        const sub = document.querySelector('.subtotal[data-id="' + itemId + '"]');
        if (sub) sub.textContent = 'S/ ' + d.subtotal;
        document.querySelectorAll('.carrito-total').forEach(function(el) {
          el.textContent = 'S/ ' + d.total;
        });
        if (qty === 0) {
          const row = document.querySelector('.carrito-item[data-id="' + itemId + '"]');
          if (row) {
            row.style.transition = 'opacity 0.3s'; row.style.opacity = '0';
            setTimeout(function() { row.remove(); if (d.count === 0) location.reload(); }, 320);
          }
        }
      }
    } catch(err) { console.error(err); }
  });

  /* Carrito — eliminar */
  document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.btn-eliminar-item');
    if (!btn) return;
    const itemId = btn.dataset.id;
    try {
      const r = await fetch('/carrito/eliminar/' + itemId, {
        method: 'DELETE', headers: csrfHeaders()
      });
      const d = await r.json();
      if (d.success) {
        const row = document.querySelector('.carrito-item[data-id="' + itemId + '"]');
        if (row) {
          row.style.transition = 'opacity 0.3s'; row.style.opacity = '0';
          setTimeout(function() { row.remove(); if (d.count === 0) location.reload(); }, 320);
        }
        updateCartBadge(d.count);
        document.querySelectorAll('.carrito-total').forEach(function(el) {
          el.textContent = 'S/ ' + d.total;
        });
      }
    } catch(err) { console.error(err); }
  });

  /* Checkout pago */
  document.querySelectorAll('.pago-card').forEach(function(card) {
    card.addEventListener('click', function() {
      document.querySelectorAll('.pago-card').forEach(function(c) { c.classList.remove('active'); });
      card.classList.add('active');
      const radio = card.querySelector('input[type=radio]');
      if (radio) radio.checked = true;
    });
  });

  /* DNI */
  const dniEl = document.getElementById('dni_cliente');
  const dniSt = document.getElementById('dni_status');
  if (dniEl && dniSt) {
    dniEl.addEventListener('input', function() {
      if (dniEl.value.length === 8 && /^\d+$/.test(dniEl.value)) {
        dniSt.innerHTML = '<span style="color:var(--c-green)"><i class="bi bi-check-circle-fill"></i> Verificado</span>';
      } else {
        dniSt.innerHTML = dniEl.value.length
          ? '<span style="color:var(--c-red)"><i class="bi bi-x-circle-fill"></i> DNI inválido</span>' : '';
      }
    });
  }

  /* Admin estado pedido */
  document.querySelectorAll('.js-estado-btn').forEach(function(btn) {
    btn.addEventListener('click', async function() {
      try {
        const r = await fetch('/admin/pedidos/' + btn.dataset.pedido + '/estado', {
          method: 'PATCH', headers: csrfHeaders(), body: JSON.stringify({ estado: btn.dataset.estado })
        });
        const d = await r.json();
        if (d.success) {
          const badge = document.querySelector('#badge-' + btn.dataset.pedido);
          if (badge) { badge.className = 'badge-tt ' + d.badgeClass; badge.textContent = d.label; }
          toast('Pedido actualizado: ' + d.label, 'ok');
        }
      } catch(err) { console.error(err); }
    });
  });

  /* ── Ripple en botones ───────────────────────────────────── */
  document.querySelectorAll('.btn-primary-tt, .btn-ghost-tt').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      const ripple = document.createElement('span');
      ripple.className = 'btn-ripple';
      const rect = btn.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      ripple.style.cssText = [
        'width:' + size + 'px',
        'height:' + size + 'px',
        'left:' + (e.clientX - rect.left - size / 2) + 'px',
        'top:' + (e.clientY - rect.top - size / 2) + 'px',
        'position:absolute',
        'border-radius:50%',
        'pointer-events:none',
      ].join(';');
      btn.appendChild(ripple);
      ripple.addEventListener('animationend', function() { ripple.remove(); });
    });
  });

  /* ── Reveal stagger: iniciar IntersectionObserver ────────── */
  document.querySelectorAll('.reveal-stagger').forEach(function(el) {
    const obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    obs.observe(el);
  });

  /* ── Section title underline reveal ────────────────────────── */
  document.querySelectorAll('.section-title').forEach(function(el) {
    const obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });
    obs.observe(el);
  });

  /* ── Cart shake quando el carrito está vacío ──────────────── */
  const cartFab = document.querySelector('.cart-fab, .js-cart-fab');
  if (cartFab) {
    cartFab.addEventListener('click', function() {
      const badge = document.querySelector('.cart-count, .badge-count');
      const count = badge ? parseInt(badge.textContent || '0') : 0;
      if (count === 0) {
        cartFab.classList.add('cart-shake');
        cartFab.addEventListener('animationend', function() {
          cartFab.classList.remove('cart-shake');
        }, { once: true });
      }
    });
  }

  /* ── User notif panel toggle ─────────────────────────────── */
  const notifBtn   = document.getElementById('userNotifBtn');
  const notifPanel = document.getElementById('userNotifPanel');
  if (notifBtn && notifPanel) {
    notifBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      const open = notifPanel.style.display !== 'none';
      notifPanel.style.display = open ? 'none' : 'block';
    });
    document.addEventListener('click', function(e) {
      if (!notifBtn.contains(e.target) && !notifPanel.contains(e.target)) {
        notifPanel.style.display = 'none';
      }
    });
  }

}); /* fin DOMContentLoaded */
