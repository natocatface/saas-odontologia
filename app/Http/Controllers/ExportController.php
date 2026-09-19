<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Gasto;
use App\Models\Insumo;
use App\Models\Pago;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function pacientes(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $estado = $request->query('estado', '');

        $filas = Paciente::query()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%")
                ->orWhere('documento', 'like', "%{$buscar}%")->orWhere('telefono', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%")))
            ->when($estado === 'activos', fn ($q) => $q->where('activo', true))
            ->when($estado === 'inactivos', fn ($q) => $q->where('activo', false))
            ->orderBy('nombre')->get()
            ->map(fn ($p) => [
                $p->nombre, $p->apellido, $p->documento, $p->telefono, $p->email,
                optional($p->fecha_nacimiento)->format('d/m/Y'),
                $p->activo ? 'Activo' : 'Inactivo',
            ])->all();

        return $this->salida($request, 'Pacientes',
            ['Nombre', 'Apellido', 'Documento', 'Telefono', 'Correo', 'Nacimiento', 'Estado'], $filas);
    }

    public function pagos(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $metodo = $request->query('metodo', '');

        $filas = Pago::query()->with(['paciente', 'presupuesto'])
            ->when($buscar !== '', fn ($q) => $q->whereHas('paciente', fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%")))
            ->when($metodo !== '', fn ($q) => $q->where('metodo', $metodo))
            ->orderByDesc('fecha')->orderByDesc('id')->get()
            ->map(fn ($p) => [
                $p->numero_recibo, $p->fecha->format('d/m/Y'),
                $p->paciente->nombre_completo ?? '', $p->presupuesto->codigo ?? '',
                $p->metodo_nombre, number_format((float) $p->monto, 2, '.', ''),
            ])->all();

        return $this->salida($request, 'Pagos',
            ['Recibo', 'Fecha', 'Paciente', 'Presupuesto', 'Metodo', 'Monto'], $filas);
    }

    public function citas(Request $request)
    {
        $fecha = $request->query('fecha', '');
        $estado = $request->query('estado', '');
        $doctorId = $request->query('doctor', '');
        $buscar = trim((string) $request->query('buscar', ''));

        $filas = Cita::query()->with(['paciente', 'doctor'])
            ->when($fecha !== '', fn ($q) => $q->whereDate('fecha', $fecha))
            ->when($estado !== '', fn ($q) => $q->where('estado', $estado))
            ->when($doctorId !== '', fn ($q) => $q->where('doctor_id', $doctorId))
            ->when($buscar !== '', fn ($q) => $q->whereHas('paciente', fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%")))
            ->orderByDesc('fecha')->orderBy('hora')->get()
            ->map(fn ($c) => [
                $c->fecha->format('d/m/Y'), $c->hora ? Str::of($c->hora)->substr(0, 5) : '',
                $c->paciente->nombre_completo ?? '', $c->doctor->name ?? '',
                $c->silla ? 'Silla '.$c->silla : '', $c->motivo, $c->estado_nombre,
            ])->all();

        return $this->salida($request, 'Citas',
            ['Fecha', 'Hora', 'Paciente', 'Doctor', 'Silla', 'Motivo', 'Estado'], $filas);
    }

    public function gastos(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = $request->query('categoria', '');

        $filas = Gasto::query()->with('user')
            ->when($buscar !== '', fn ($q) => $q->where('descripcion', 'like', "%{$buscar}%"))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->orderByDesc('fecha')->orderByDesc('id')->get()
            ->map(fn ($g) => [
                $g->fecha->format('d/m/Y'), $g->descripcion, $g->categoria_nombre,
                $g->metodo_nombre, $g->user->name ?? '', number_format((float) $g->monto, 2, '.', ''),
            ])->all();

        return $this->salida($request, 'Gastos',
            ['Fecha', 'Descripcion', 'Categoria', 'Metodo', 'Registro', 'Monto'], $filas);
    }

    public function insumos(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = $request->query('categoria', '');
        $bajos = $request->boolean('bajos');

        $filas = Insumo::query()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('proveedor', 'like', "%{$buscar}%")))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->when($bajos, fn ($q) => $q->whereColumn('stock', '<=', 'stock_minimo'))
            ->orderBy('nombre')->get()
            ->map(fn ($i) => [
                $i->nombre, $i->categoria_nombre, $i->unidad,
                number_format((float) $i->stock, 2, '.', ''),
                number_format((float) $i->stock_minimo, 2, '.', ''),
                $i->costo_unitario !== null ? number_format((float) $i->costo_unitario, 2, '.', '') : '',
                $i->proveedor, $i->bajo_stock ? 'Bajo' : 'OK',
            ])->all();

        return $this->salida($request, 'Inventario',
            ['Insumo', 'Categoria', 'Unidad', 'Stock', 'Minimo', 'Costo unit.', 'Proveedor', 'Estado'], $filas);
    }

    /** Decide el formato de salida: pdf (imprimible) o csv (por defecto). */
    private function salida(Request $request, string $titulo, array $columnas, array $filas)
    {
        if ($request->query('formato') === 'pdf') {
            return view('exports.print', [
                'titulo' => $titulo,
                'columnas' => $columnas,
                'filas' => $filas,
                'clinica' => \App\Models\Configuracion::valor('nombre_clinica', 'OdontoCRM'),
            ]);
        }

        return $this->csv($titulo, $columnas, $filas);
    }

    /** Genera un CSV (con BOM UTF-8 para Excel). */
    private function csv(string $titulo, array $columnas, array $filas): StreamedResponse
    {
        $nombre = Str::slug($titulo).'_'.Carbon::now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($columnas, $filas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel respete los acentos
            fputcsv($out, $columnas);
            foreach ($filas as $fila) {
                fputcsv($out, $fila);
            }
            fclose($out);
        }, $nombre, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
