<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActividadController extends Controller
{
    public function index(Request $request): View
    {
        $accion = $request->query('accion', '');
        $userId = $request->query('usuario', '');
        $buscar = trim((string) $request->query('buscar', ''));

        $actividades = Actividad::query()
            ->with('user')
            ->when($accion !== '', fn ($q) => $q->where('accion', $accion))
            ->when($userId !== '', fn ($q) => $q->where('user_id', $userId))
            ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('descripcion', 'like', "%{$buscar}%")->orWhere('modulo', 'like', "%{$buscar}%")))
            ->orderByDesc('id')
            ->paginate(20)->withQueryString();

        $usuarios = User::orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => Actividad::count(),
            'hoy' => Actividad::whereDate('created_at', today())->count(),
        ];

        return view('actividad.index', compact('actividades', 'usuarios', 'stats', 'accion', 'userId', 'buscar'));
    }
}
