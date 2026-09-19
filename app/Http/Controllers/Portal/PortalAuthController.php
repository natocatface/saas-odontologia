<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PortalAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('paciente')->check()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresa tu correo.',
            'password.required' => 'Ingresa tu clave.',
        ]);

        if (! Auth::guard('paciente')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Correo o clave incorrectos.',
            ]);
        }

        $paciente = Auth::guard('paciente')->user();

        if (! $paciente->portal_activo || ! $paciente->activo) {
            Auth::guard('paciente')->logout();

            throw ValidationException::withMessages([
                'email' => 'Tu acceso al portal no esta habilitado. Comunicate con la clinica.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('portal.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('paciente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
