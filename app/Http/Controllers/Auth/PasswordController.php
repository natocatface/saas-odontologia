<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /** Formulario para cambiar la contrasena. */
    public function edit(): View
    {
        return view('auth.password');
    }

    /** Actualiza la contrasena del usuario autenticado. */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Ingresa tu contrasena actual.',
            'password.required' => 'Ingresa la nueva contrasena.',
            'password.confirmed' => 'La confirmacion no coincide.',
            'password.min' => 'La nueva contrasena debe tener al menos 8 caracteres.',
        ]);

        if (! Hash::check($validated['current_password'], $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contrasena actual no es correcta.',
            ]);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('dashboard')->with('status', 'Contrasena actualizada correctamente.');
    }

    /** Formulario para solicitar el enlace de recuperacion. */
    public function showForgot(): View
    {
        return view('auth.forgot-password');
    }

    /** Envia el enlace de recuperacion al correo. */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => ['required', 'email']],
            ['email.required' => 'Ingresa tu correo.', 'email.email' => 'Ingresa un correo valido.']
        );

        $status = PasswordBroker::sendResetLink($request->only('email'));

        // No revelamos si el correo existe o no por seguridad.
        return back()->with('status', 'Si el correo esta registrado, te enviamos un enlace para restablecer tu contrasena.');
    }

    /** Formulario para definir la nueva contrasena. */
    public function showReset(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Procesa el restablecimiento de la contrasena. */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Ingresa la nueva contrasena.',
            'password.confirmed' => 'La confirmacion no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]);

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Tu contrasena fue restablecida. Ya puedes iniciar sesion.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
