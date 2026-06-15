<div align="center">

# Tierra y Taza

### Plataforma Web para Cafetería Artesanal Peruana

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)

[![Demo en vivo](https://img.shields.io/badge/DEMO%20EN%20VIVO-pink--pig--204029.hostingersite.com-D4A84B?style=for-the-badge)](https://pink-pig-204029.hostingersite.com)

</div>

---

Sistema web **full-stack** construido en Laravel 11 para la gestión completa de una cafetería artesanal: tienda online, panel administrativo multi-rol, reservas, pedidos en tiempo real y reportes. Desarrollado como proyecto de portafolio para demostrar dominio de arquitectura MVC, diseño premium con CSS puro y despliegue en producción.

---

## Acceso a la Demo

| Rol | Email | Contraseña |
|---|---|---|
| Admin General | `general@tierraytaza.pe` | `General1234` |
| Cajero | `cajero@tierraytaza.pe` | `Cajero1234` |
| Barista | `barista@tierraytaza.pe` | `Barista1234` |
| Coord. Delivery | `delivery@tierraytaza.pe` | `Delivery1234` |
| Cliente | `cliente@demo.pe` | `Cliente1234` |

---

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 · PHP 8.2 · Eloquent ORM |
| Base de datos | MySQL 8 — 10 migraciones, seeders completos |
| Frontend | Bootstrap 5.3 · Bootstrap Icons · CSS custom (~5100 líneas) |
| Autenticación | Laravel Auth · Middleware `CheckRole` por rol |
| Deploy | Hostinger Business · SSH · Git pull workflow |
| Sin build tools | Sin Node.js · Sin Webpack · Sin Vite — solo CDN + archivos estáticos |

---

## Funcionalidades

### Vista Cliente

- Catálogo con filtros por categoría y paginación
- Detalle de producto con galería y reseñas con calificación
- **Ofertas del Día** con precio tachado y badge de descuento
- Carrito gestionado por AJAX (sin recargar la página)
- Checkout con opción delivery o recojo en tienda
- **Seguimiento de pedido en tiempo real** — la barra de progreso y el estado se actualizan automáticamente cada 7 segundos sin refrescar
- **Campana de notificaciones** en el navbar que muestra el estado activo de cada pedido
- Historial de pedidos con descarga de boleta en formato imprimible
- Reporte de incidencias sobre pedidos
- Reservas de mesas y espacios coworking desde mapa interactivo
- Perfil de usuario con foto editable
- Dark mode / Light mode con persistencia en `localStorage`

### Panel Administrativo (según rol)

- Dashboard con métricas del día: ventas, pedidos, pagos pendientes
- CRUD completo de productos con imagen, stock, categoría y **sección de ofertas** (nombre, precio rebajado, fecha límite)
- **Notificaciones por función**: cada rol recibe alertas solo de lo que le corresponde
  - Cajero → nuevo pedido llega
  - Barista → solo cuando el cajero valida el pago
  - Coord. Delivery → solo cuando el barista marca el pedido listo
  - Admin General → recibe todas las fases
- Gestión de pedidos: cambiar estado con flujo definido
- Validación de pagos con visualización del comprobante subido
- Control de reservas y liberación de espacios vencidos
- Gestión de incidencias con respuesta al cliente
- Reportes de ventas (diario, mensual, anual)
- Control de inventario con alertas de stock bajo
- Gestión de usuarios y asignación de roles

---

## Roles y Flujo de Trabajo

```
Cliente hace pedido
      │
      ▼
  [CAJERO] ─── valida pago ──────────────────────────────► Admin General
      │                                                    (ve todo)
      ▼
  [BARISTA] ─── prepara y marca "listo" ─────────────────►
      │
      ▼
  [COORD. DELIVERY] ─── despacha y entrega ──────────────►
      │
      ▼
  Cliente recibe actualización automática en "Mis Pedidos"
```

| Rol | Acceso |
|---|---|
| `cliente` | Catálogo · Carrito · Pedidos · Reservas · Reseñas · Perfil |
| `cajero` | Pagos · Incidencias · Reportes · Reservas |
| `barista` | Pedidos → En preparación / Listo |
| `coordinador_delivery` | Pedidos → En camino / Entregado |
| `admin_sistema` | CRUD Productos · Inventario · Usuarios · Operaciones |
| `admin_general` | Control total — todos los módulos |

---

## Diseño — Design System

El frontend fue construido completamente a mano sin frameworks de diseño. El archivo `public/css/app.css` (~5100 líneas) implementa un sistema de diseño propio:

```css
--c-gold:        #D4A84B   /* Dorado principal */
--c-amber:       #C8963C   /* Dorado oscuro / gradientes */
--c-surface:     #1C1A16   /* Fondo de cards */
--c-dark:        #0D0C0A   /* Fondo base */
--radius:        14px
--ease:          cubic-bezier(0.22, 1, 0.36, 1)
```

**Técnicas implementadas:**
- Dark / Light mode con `data-theme` en `<html>` y variables CSS
- Glassmorphism (`backdrop-filter: blur`) en cards, modales y notificaciones
- Animaciones con `IntersectionObserver`: reveal, stagger, fade-up
- Efecto shimmer animado en gradiente dorado para títulos
- Ripple effect en botones (CSS + JS puro)
- Parallax en hero section
- Grain texture overlay para profundidad

---

## Seguridad

| Protección | Implementación |
|---|---|
| CSRF | Token automático en todos los formularios (Laravel) |
| XSS | Escape automático con `{{ }}` en Blade |
| SQL Injection | Eloquent ORM con queries parametrizadas |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` |
| MIME Sniffing | `X-Content-Type-Options: nosniff` |
| HSTS | `Strict-Transport-Security` activo en HTTPS |
| BFCache | `Cache-Control: no-store` para evitar pantalla negra al volver |
| Control de acceso | Middleware `CheckRole` — cada ruta admin valida el rol |
| Contraseñas | Hashing bcrypt vía Laravel Auth |
| Sesiones 419 | Redirige al login con mensaje claro en vez de pantalla blanca |

---

## Instalación Local

```bash
# 1. Clonar
git clone https://github.com/yoselin55/tierra-taza.git
cd tierra-taza

# 2. Dependencias PHP
composer install

# 3. Variables de entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env
# DB_DATABASE=tierra_taza
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Crear tablas y datos de prueba
php artisan migrate --seed

# 6. Storage para imágenes
php artisan storage:link

# 7. Iniciar
php artisan serve
```

Abrir **http://localhost:8000**

> No se requiere Node.js. No hay paso de build. El frontend carga directamente desde `public/css/app.css` y `public/js/app.js`.

---

## Estructura del Proyecto

```
tierra-taza/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/               ← 8 controllers (productos, pedidos, pagos, reportes...)
│   │   │   ├── Auth/                ← Login, registro, selección de rol
│   │   │   ├── PedidoController.php ← Checkout + polling de estados en tiempo real
│   │   │   ├── ReservaController.php
│   │   │   └── HomeController.php   ← Home con ofertas del día
│   │   └── Middleware/
│   │       ├── CheckRole.php        ← Acceso por rol (barista, cajero, etc.)
│   │       └── SecurityHeaders.php  ← Headers HTTP + anti-bfcache
│   └── Models/
│       ├── Producto.php   ← estaEnOferta(), getPrecioFinalAttribute()
│       ├── Pedido.php     ← estado_label, estado_badge, estado_paso
│       └── User.php       ← esCliente(), esAdmin(), getRolLabelAttribute()
├── database/
│   ├── migrations/        ← 10 migraciones (schema completo)
│   └── seeders/           ← Usuarios demo + productos + categorías
├── public/
│   ├── css/app.css        ← Design system (~5100 líneas)
│   └── js/app.js          ← AJAX carrito · ripple · polling · dark toggle
├── resources/views/
│   ├── layouts/           ← app.blade.php + admin.blade.php
│   ├── admin/             ← Dashboard, productos, pedidos, pagos, reportes
│   ├── shop/              ← Home (ofertas), catálogo, carrito
│   ├── pedidos/           ← Checkout, mis pedidos (polling), boleta
│   └── reservas/          ← Mapa interactivo con disponibilidad
└── routes/web.php         ← ~70 rutas organizadas por sección y middleware
```

---

## Deploy en Hostinger

### Requisitos
- PHP 8.2+ y MySQL (plan Business o superior — necesario para SSH)
- Acceso al hPanel

### Pasos

```bash
# En tu PC
git add . && git commit -m "mensaje" && git push origin main

# En el servidor (via SSH)
ssh -p 65002 usuario@servidor
cd ~/tierra-taza
git pull origin main
php artisan migrate --force
php artisan view:clear && php artisan cache:clear
```

### .env en producción

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=nombre_bd
DB_USERNAME=usuario_bd
DB_PASSWORD=contraseña_bd

CACHE_DRIVER=file
SESSION_DRIVER=file
```

> `exec()` está deshabilitado en Hostinger — el storage link debe crearse manualmente con `ln -s` desde SSH.

---

<div align="center">

Desarrollado por **Yoselin Flores** · 2026

[![GitHub](https://img.shields.io/badge/GitHub-yoselin55-181717?style=flat-square&logo=github)](https://github.com/yoselin55)

</div>
