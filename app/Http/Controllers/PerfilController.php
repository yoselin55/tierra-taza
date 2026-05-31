<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $pedidos = $user->pedidos()->with('pago')->latest()->take(5)->get();

        return view('perfil.index', compact('user', 'pedidos'));
    }

    public function edit()
    {
        return view('perfil.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nombre' => 'required|string|max:100',
            'dni'    => 'required|digits:8',
            'email'  => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password_actual']       = 'required';
            $rules['password']              = 'min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        $request->validate($rules, [
            'nombre.required'    => 'El nombre es obligatorio.',
            'dni.digits'         => 'El DNI debe tener 8 dígitos.',
            'email.unique'       => 'Ese correo ya está registrado.',
            'password_actual.required' => 'Debes ingresar tu contraseña actual.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'dni'    => $request->dni,
            'email'  => $request->email,
        ];

        if ($request->filled('password')) {
            if (!Hash::check($request->password_actual, $user->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto.image'  => 'El archivo debe ser una imagen.',
            'foto.mimes'  => 'Formatos permitidos: jpg, jpeg, png, webp.',
            'foto.max'    => 'La imagen no debe superar 2 MB.',
        ]);

        $user = auth()->user();

        // Eliminar foto anterior si existe
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('fotos-perfil', 'public');
        $user->update(['foto' => $path]);

        return back()->with('success', 'Foto de perfil actualizada.');
    }
}