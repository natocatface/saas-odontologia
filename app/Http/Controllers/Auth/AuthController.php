<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Muestra el formulario de inicio de sesion. */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /** Procesa el inicio de sesion. */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresa un correo valido.',
            'password.required' => 'La contrasena es obligatoria.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        if (! $request->user()->activo) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta esta inactiva. Contacta al administrador.',
            ]);
        }

        $request->session()->regenerate();

        Actividad::registrar('acceso', null, $request->user()->name);

        return redirect()->intended(route('dashboard'));
    }

    /** Cierra la sesion. */
    public function logout(Request $request): RedirectResponse
    {
        Actividad::registrar('salida', null, $request->user()?->name);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
