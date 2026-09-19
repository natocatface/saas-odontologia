<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Metadatos de cada modulo del sistema. Se usa tanto para el menu
     * como para renderizar las pantallas placeholder navegables.
     *
     * @var array<string, array<string, string>>
     */
    public const MODULOS = [
        'pacientes' => [
            'titulo' => 'Pacientes',
            'descripcion' => 'Gestion de la historia clinica y datos de los pacientes.',
            'icono' => 'users',
            'detalle' => 'Aqui podras registrar pacientes, ver su historia clinica, odontograma, alergias y documentos.',
        ],
        'citas' => [
            'titulo' => 'Citas',
            'descripcion' => 'Agenda y calendario de citas por doctor.',
            'icono' => 'calendar',
            'detalle' => 'Calendario de citas, confirmaciones, recordatorios y estados (pendiente, confirmada, completada).',
        ],
        'tratamientos' => [
            'titulo' => 'Tratamientos',
            'descripcion' => 'Catalogo de tratamientos y planes por paciente.',
            'icono' => 'clipboard',
            'detalle' => 'Catalogo de procedimientos, precios y planes de tratamiento asignados a cada paciente.',
        ],
        'presupuestos' => [
            'titulo' => 'Presupuestos',
            'descripcion' => 'Cotizaciones y planes de pago.',
            'icono' => 'document',
            'detalle' => 'Genera presupuestos por tratamiento, aprueba y convierte en planes de pago.',
        ],
        'pagos' => [
            'titulo' => 'Pagos',
            'descripcion' => 'Cobros, recibos y estado de cuenta.',
            'icono' => 'cash',
            'detalle' => 'Registro de pagos, cuotas, recibos e ingresos de la clinica.',
        ],
        'reportes' => [
            'titulo' => 'Reportes',
            'descripcion' => 'Indicadores y reportes del negocio.',
            'icono' => 'chart',
            'detalle' => 'Reportes de ingresos, productividad por doctor, citas y pacientes nuevos.',
        ],
        'usuarios' => [
            'titulo' => 'Usuarios',
            'descripcion' => 'Administracion de usuarios y roles.',
            'icono' => 'cog',
            'detalle' => 'Crea usuarios (admin, doctor, recepcion), asigna roles y permisos.',
        ],
        'actividad' => [
            'titulo' => 'Actividad',
            'descripcion' => 'Bitacora de acciones del sistema.',
            'icono' => 'activity',
            'detalle' => 'Registro de auditoria: quien hizo que y cuando dentro del sistema.',
        ],
        'mantenimiento' => [
            'titulo' => 'Mantenimiento',
            'descripcion' => 'Respaldos y herramientas de mantenimiento.',
            'icono' => 'shield',
            'detalle' => 'Copias de seguridad, limpieza de datos y parametros tecnicos.',
        ],
        'configuracion' => [
            'titulo' => 'Configuracion',
            'descripcion' => 'Parametros generales de la clinica.',
            'icono' => 'sliders',
            'detalle' => 'Datos de la clinica, horarios, especialidades, impuestos y preferencias.',
        ],
    ];

    public function show(string $modulo): View
    {
        abort_unless(array_key_exists($modulo, self::MODULOS), 404);

        return view('modules.placeholder', [
            'modulo' => $modulo,
            'meta' => self::MODULOS[$modulo],
        ]);
    }
}
