<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $rol = $request->query('rol', '');

        $usuarios = User::query()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('name', 'like', "%{$buscar}%")->orWhere('email', 'like', "%{$buscar}%")))
            ->when($rol !== '', fn ($q) => $q->where('rol', $rol))
            ->orderBy('name')
            ->paginate(12)->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin' => User::where('rol', 'admin')->count(),
            'doctor' => User::where('rol', 'doctor')->count(),
            'recepcion' => User::where('rol', 'recepcion')->count(),
        ];

        return view('usuarios.index', compact('usuarios', 'stats', 'buscar', 'rol'));
    }

    public function create(): View
    {
        return view('usuarios.create', ['usuario' => new User(['activo' => true, 'rol' => 'recepcion'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('usuarios.index')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('usuarios.edit', ['usuario' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validar($request, $user);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('status', 'Usuario eliminado.');
    }

    public function toggleEstado(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->update(['activo' => ! $user->activo]);

        return back()->with('status', 'Estado del usuario actualizado.');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request, ?User $user = null): array
    {
        $reglaPassword = $user
            ? ['nullable', 'confirmed', Password::min(8)]
            : ['required', 'confirmed', Password::min(8)];

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user)],
            'rol' => ['required', Rule::in(array_keys(User::ROLES))],
            'telefono' => ['nullable', 'string', 'max:30'],
            'especialidad' => ['nullable', 'string', 'max:120'],
            'comision' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'password' => $reglaPassword,
            'activo' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Ese correo ya esta registrado.',
            'rol.required' => 'Selecciona un rol.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]) + ['activo' => $request->boolean('activo')];
    }
}
