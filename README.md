# Tierra y Taza — Plataforma Web para Cafeteria Artesanal

**Tierra y Taza** es un sistema web completo construido en **Laravel 11** para una cafeteria artesanal peruana. Este proyecto fue desarrollado como pieza de portafolio para demostrar habilidades full-stack: backend robusto con PHP/Laravel, panel de administracion multi-rol, diseno premium con dark/light mode y CSS personalizado sin dependencias de Node.js.

> Stack: **Laravel 11** · **PHP 8.2** · **MySQL** · **Bootstrap 5.3** · **CSS custom** · Sin Node.js ni Webpack

---

## Demo en Vivo

**Sitio:** [https://tierraytaza.tu-dominio.com](https://tierraytaza.tu-dominio.com)

| Rol demo | Email | Contrasena |
|---|---|---|
| Admin General | general@tierraytaza.pe | General1234 |
| Cajero | cajero@tierraytaza.pe | Cajero1234 |
| Barista | barista@tierraytaza.pe | Barista1234 |
| Coord. Delivery | delivery@tierraytaza.pe | Delivery1234 |
| Cliente | cliente@demo.pe | Cliente1234 |

---

## Que demuestra este proyecto

- **Arquitectura MVC limpia** con Laravel 11: controllers, models, middlewares, seeders y migraciones organizados
- **Sistema de roles real** con 6 niveles de acceso controlados via middleware (`CheckRole`)
- **E-commerce funcional**: carrito con AJAX, checkout, historial de pedidos, descarga de boletas en PDF
- **Panel de administracion** completo: CRUD de productos, gestion de pedidos por estado, validacion de pagos, reportes diarios/mensuales/anuales
- **Reservas con mapa interactivo**: mesas y espacios coworking con disponibilidad en tiempo real
- **Seguridad implementada**: CSRF, XSS escaping, SQL injection via Eloquent ORM, headers de seguridad HTTP, HSTS
- **Diseno premium**: dark/light mode, glassmorphism, animaciones reveal con CSS puro, tipografia dual (Inter + Playfair Display)
- **Sin build tools**: todo el frontend via CDN y CSS/JS estatico — facil de mantener y desplegar

---

## Funcionalidades

**Vista cliente:**
- Catalogo con filtros por categoria y paginacion
- Detalle de producto con galeria y resenas
- Carrito gestionado via AJAX (sin recargar pagina)
- Checkout con opcion delivery o recojo en tienda
- Historial de pedidos con barra de progreso por estado
- Descarga de boleta en formato imprimible
- Reporte de incidencias sobre pedidos
- Reservas de mesas y coworking desde mapa interactivo
- Perfil de usuario con foto y datos editables
- Modo oscuro / modo claro con persistencia en localStorage

**Panel admin (segun rol):**
- Dashboard con metricas del dia
- CRUD completo de productos con imagen y stock
- Gestion de pedidos: cambiar estado, ver detalle
- Validacion de pagos con visualizacion de comprobante
- Control de reservas y liberacion de espacios vencidos
- Gestion de incidencias con respuesta al cliente
- Reportes de ventas (diario, mensual, anual) exportables
- Control de inventario con alertas de stock bajo
- Gestion de usuarios y asignacion de roles
- Notificaciones en tiempo real via polling

---

## Instalacion Local

```bash
# 1. Clonar el repositorio
git clone https://github.com/TU_USUARIO/tierra-taza-laravel.git
cd tierra-taza-laravel

# 2. Instalar dependencias PHP
composer install

# 3. Copiar variables de entorno
cp .env.example .env

# 4. Generar clave de aplicacion
php artisan key:generate

# 5. Configurar base de datos en .env
# DB_DATABASE=tierra_taza
# DB_USERNAME=root
# DB_PASSWORD=tu_password

# 6. Crear tablas y datos de prueba
php artisan migrate --seed

# 7. Enlazar storage para imagenes subidas
php artisan storage:link

# 8. Iniciar servidor
php artisan serve
```

Abrir: **http://localhost:8000**

> La base de datos se crea completamente desde las migraciones y seeders. No se incluye ningun archivo `.sql` — basta con `migrate --seed`.

---

## Deploy en Hostinger

### Prerequisitos
- Hosting con PHP 8.2+ y MySQL (plan Business o superior recomendado para SSH)
- Acceso a hPanel de Hostinger

### Paso 1 — Subir archivos

Opcion A — via Git (si tienes SSH en Hostinger):
```bash
ssh usuario@tu-servidor.hostinger.com
cd public_html
git clone https://github.com/TU_USUARIO/tierra-taza-laravel.git .
composer install --no-dev --optimize-autoloader
```

Opcion B — via FTP/Administrador de archivos:
- Subir todos los archivos **excepto** la carpeta `vendor/`
- Luego ejecutar `composer install` desde el terminal de Hostinger

### Paso 2 — Configurar base de datos

1. En hPanel ir a **Bases de datos** → **MySQL** → crear base de datos
2. Anotar: nombre de BD, usuario, contrasena y host

### Paso 3 — Configurar .env en produccion

Crear el archivo `.env` en la raiz del proyecto con:
```env
APP_NAME="Tierra y Taza"
APP_ENV=production
APP_DEBUG=false
APP_KEY=      ← ejecutar: php artisan key:generate
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_bd_hostinger
DB_USERNAME=usuario_bd_hostinger
DB_PASSWORD=contrasena_bd_hostinger

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Paso 4 — Migraciones y storage

```bash
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

### Paso 5 — Apuntar el dominio

En hPanel → **Dominios** → configurar el **Document Root** apuntando a `public_html/tierra-taza-laravel/public` (la carpeta `public/` de Laravel, no la raiz del proyecto).

---

## Seguridad Implementada

| Proteccion | Mecanismo |
|---|---|
| CSRF | Token automatico en todos los formularios (Laravel) |
| XSS | Escape HTML automatico con `{{ }}` en Blade |
| SQL Injection | Eloquent ORM con queries parametrizadas |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` |
| MIME Sniffing | `X-Content-Type-Options: nosniff` |
| Content Security Policy | Bloquea scripts de dominios no autorizados |
| Referrer leaks | `Referrer-Policy: strict-origin-when-cross-origin` |
| HSTS | `Strict-Transport-Security` activo en HTTPS |
| Control de acceso | Middleware `CheckRole` por rol en rutas admin |
| Sesiones expiradas | Redirige al login en vez de pantalla blanca (419) |
| Contrasenas | Hashing bcrypt via Laravel Auth |

---

## Roles y Permisos

| Rol | Accesos |
|---|---|
| `cliente` | Catalogo, carrito, pedidos, reservas, resenas, perfil |
| `barista` | Pedidos → marcar "En preparacion" / "Preparado" |
| `coordinador_delivery` | Pedidos → marcar "En camino" / "Entregado" |
| `cajero` | Reportes, pagos, incidencias, pedidos y reservas |
| `admin_sistema` | CRUD productos, inventario, usuarios + operaciones |
| `admin_general` | Control total: todos los modulos + recursos y configuracion |

---

## Estructura del Proyecto

```
tierra-taza-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          ← Panel admin (8 controllers por modulo)
│   │   │   ├── Auth/           ← Login y registro
│   │   │   ├── CarritoController.php
│   │   │   ├── CatalogoController.php
│   │   │   ├── PedidoController.php
│   │   │   ├── PerfilController.php
│   │   │   └── ReservaController.php
│   │   └── Middleware/
│   │       ├── CheckRole.php         ← Control de acceso por rol
│   │       └── SecurityHeaders.php   ← Headers HTTP de seguridad
│   └── Models/
│       ├── User · Producto · Pedido · DetallePedido
│       ├── Pago · Resena · Recurso · Reserva
│       └── Incidencia · Configuracion
├── database/
│   ├── migrations/   ← 9 migraciones (schema completo)
│   └── seeders/      ← Datos de prueba + usuarios demo
├── public/
│   ├── css/app.css   ← Design system (~2200 lineas, dark/light mode)
│   ├── js/app.js     ← AJAX carrito, dark toggle, animaciones
│   └── images/       ← Assets estaticos
├── resources/views/
│   ├── layouts/      ← app.blade.php + admin.blade.php
│   ├── admin/        ← Dashboard, productos, pedidos, reportes, pagos
│   ├── shop/         ← Home, catalogo, carrito
│   ├── pedidos/      ← Checkout, historial, boleta, comprobante
│   ├── reservas/     ← Mapa interactivo
│   └── legal/        ← Terminos, privacidad, cookies
└── routes/web.php    ← 66 rutas organizadas por seccion
```

---

## Diseno — Design System

- **Dark Mode Premium** como modo por defecto, toggle a modo claro
- **Glassmorphism**: fondos translucidos con blur
- **Paleta**: Gold `#C8963C`, Verde `#00D68F`, Rojo `#FF3D71`, Base `#0D0D0D`
- **Tipografia**: Inter (UI) + Playfair Display (titulos)
- **Animaciones**: scroll reveal, parallax hero, hover scale, stagger
- **Sin dependencias de build**: Bootstrap 5.3 + Bootstrap Icons via CDN

---

*Desarrollado por Yoselin Flores — 2026*
