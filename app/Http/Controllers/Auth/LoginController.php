<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Login cliente
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = auth()->user();

            // Si es admin, redirigir al panel
            if ($user->esAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->withInput($request->only('email'));
    }

    // Login admin por rol específico
    public function showAdminLogin(Request $request)
    {
        $rol = $request->get('rol', 'admin_general');
        return view('auth.admin_login', compact('rol'));
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'rol'      => 'required|in:barista,cajero,coordinador_delivery,admin_sistema,admin_general',
        ]);

        // Cerrar cualquier sesión previa para evitar conflictos de CSRF
        if (auth()->check()) {
            Auth::logout();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = auth()->user();

            if ($user->rol !== $request->rol) {
                Auth::logout();
                return redirect()->route('admin.login', ['rol' => $request->rol])
                    ->withErrors(['email' => 'Este usuario no tiene el rol seleccionado.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput($request->only('email', 'rol'));
    }

    public function logout(Request $request)
    {
        $wasAdmin = auth()->check() && auth()->user()->esAdmin();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $wasAdmin
            ? redirect()->route('admin.select_rol')
            : redirect()->route('home');
    }
}
