<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoAdminController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\ReservaAdminController;
use App\Http\Controllers\Admin\RecursoAdminController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\PagoAdminController;
use App\Http\Controllers\Admin\IncidenciaAdminController;
use App\Http\Controllers\Admin\NotifController;

// ── Públicas ──────────────────────────────────────────────────────────────────
Route::get('/',              [HomeController::class, 'index'])->name('home');
Route::get('/sobre-nosotros',[HomeController::class, 'sobre'])->name('sobre');
Route::get('/ubicacion',     [HomeController::class, 'ubicacion'])->name('ubicacion');
Route::get('/terminos-y-condiciones',  fn() => view('legal.terminos'))->name('terminos');
Route::get('/aviso-legal',             fn() => view('legal.aviso'))->name('aviso.legal');
Route::get('/politica-de-privacidad',  fn() => view('legal.privacidad'))->name('privacidad');
Route::get('/politica-de-cookies',     fn() => view('legal.cookies'))->name('cookies');

// Catálogo (público)
Route::get('/catalogo',                  [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/catalogo/{producto}',       [CatalogoController::class, 'show'])->name('catalogo.show');
Route::post('/catalogo/{producto}/resena',[CatalogoController::class, 'guardarResena'])->name('catalogo.resena')->middleware('auth');

// Carrito (público - sesión)
Route::get('/carrito',                           [CarritoController::class, 'index'])->name('carrito');
Route::post('/carrito/agregar/{producto}',        [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/actualizar/{id}',          [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/eliminar/{id}',           [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar',                    [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

// Reservas (pública para ver el mapa)
Route::get('/reservas',              [ReservaController::class, 'index'])->name('reservas.index');
Route::get('/reservas/estado',       [ReservaController::class, 'estadoRecursos'])->name('reservas.estado');
Route::post('/reservas',             [ReservaController::class, 'store'])->name('reservas.store')->middleware('auth');

// ── Auth Cliente ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro',[RegisterController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Pedidos (requiere auth de cliente) ───────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                      [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/pedidos',                      [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/mis-pedidos',                   [PedidoController::class, 'misPedidos'])->name('pedidos.mis_pedidos');
    Route::get('/mis-pedidos/estados-poll',      [PedidoController::class, 'estadosPoll'])->name('pedidos.estados_poll');
    Route::get('/mis-pedidos/{pedido}',          [PedidoController::class, 'detalle'])->name('pedidos.detalle');
    Route::get('/mis-pedidos/{pedido}/boleta',      [PedidoController::class, 'boleta'])->name('pedidos.boleta');
    Route::get('/mis-pedidos/{pedido}/comprobante', [PedidoController::class, 'comprobante'])->name('pedidos.comprobante');
    Route::post('/mis-pedidos/{pedido}/incidencia',[PedidoController::class, 'reportarIncidencia'])->name('pedidos.incidencia');

    // Perfil de usuario
    Route::get('/perfil',            [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar',     [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil/editar',    [PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/foto',      [PerfilController::class, 'updateFoto'])->name('perfil.foto');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::get('/admin', [AdminController::class, 'selectRol'])->name('admin.select_rol');

// Login admin
Route::get('/admin/login',  [LoginController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post')->middleware('throttle:5,1');

// Panel admin — todos los roles de staff
Route::middleware(['auth', 'role:barista,cajero,coordinador_delivery,admin_sistema,admin_general'])
    ->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifs/poll', [NotifController::class, 'poll'])->name('notifs.poll');

    // Productos — admin_sistema y admin_general gestionan el catálogo
    Route::middleware('role:admin_sistema,admin_general')->group(function () {
        Route::resource('productos', ProductoAdminController::class);
    });

    // Pedidos — todos los roles operativos
    Route::get('/pedidos',                   [PedidoAdminController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}',          [PedidoAdminController::class, 'detalle'])->name('pedidos.detalle');
    Route::patch('/pedidos/{pedido}/estado', [PedidoAdminController::class, 'cambiarEstado'])->name('pedidos.estado');

    // Reservas — todos los roles operativos
    Route::get('/reservas',                          [ReservaAdminController::class, 'index'])->name('reservas.index');
    Route::patch('/reservas/{reserva}/estado',       [ReservaAdminController::class, 'cambiarEstado'])->name('reservas.estado');
    Route::post('/reservas/liberar-vencidas',        [ReservaAdminController::class, 'liberarVencidas'])->name('reservas.liberar');

    // Reportes, pagos e incidencias — cajero y admin_general
    Route::middleware('role:cajero,admin_general')->group(function () {
        Route::get('/reportes',                            [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/pagos',                               [PagoAdminController::class, 'index'])->name('pagos.index');
        Route::patch('/pagos/{pago}/validar',              [PagoAdminController::class, 'validar'])->name('pagos.validar');
        Route::get('/pagos/{pago}/comprobante',            [PagoAdminController::class, 'comprobante'])->name('pagos.comprobante');
        Route::get('/incidencias',                         [IncidenciaAdminController::class, 'index'])->name('incidencias.index');
        Route::patch('/incidencias/{incidencia}/responder',[IncidenciaAdminController::class, 'responder'])->name('incidencias.responder');
    });

    // Recursos (mesas/coworking) — solo admin_general
    Route::middleware('role:admin_general')->group(function () {
        Route::get('/recursos',                        [RecursoAdminController::class, 'index'])->name('recursos.index');
        Route::patch('/recursos/{recurso}/estado',     [RecursoAdminController::class, 'cambiarEstado'])->name('recursos.estado');
        Route::post('/recursos/toggle-reservas',       [RecursoAdminController::class, 'toggleReservas'])->name('recursos.toggle_reservas');
    });

    // Inventario y usuarios — admin_sistema y admin_general (mantener plataforma)
    Route::middleware('role:admin_sistema,admin_general')->group(function () {
        Route::get('/inventario', function () {
            $productos = \App\Models\Producto::orderBy('stock')->paginate(20);
            return view('admin.inventario', compact('productos'));
        })->name('inventario');

        Route::post('/inventario/{producto}/stock', function (\App\Models\Producto $producto, \Illuminate\Http\Request $request) {
            $request->validate(['stock' => 'required|integer|min:0']);
            $producto->update(['stock' => $request->stock]);
            return back()->with('success', 'Stock actualizado.');
        })->name('inventario.stock');

        Route::get('/usuarios', function () {
            $usuarios = \App\Models\User::paginate(20);
            return view('admin.usuarios', compact('usuarios'));
        })->name('usuarios');
    });
});
