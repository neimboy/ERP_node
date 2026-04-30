<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignacionRequest;
use App\Models\Asignacion;
use App\Models\Empleado;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsignacionController extends Controller
{
    public function index(): View
    {
        $asignaciones = Asignacion::with('empleado', 'proyecto')->get();
        return view('produccion.asignaciones.index', compact('asignaciones'));
    }

    public function create(Request $request): View
    {
        $proyecto_seleccionado = $request->get('proyecto_id');
        $proyectos = Proyecto::all();

        $empleados_asignados = [];
        if ($proyecto_seleccionado) {
            $empleados_asignados = Asignacion::where('Id_Proyecto', $proyecto_seleccionado)
                ->pluck('Id_Empleado')
                ->toArray();
        }

        $empleados = Empleado::whereNotIn('Id_Empleado', $empleados_asignados)->get();

        return view('produccion.asignaciones.create', compact('empleados', 'proyectos', 'proyecto_seleccionado'));
    }

    public function store(AsignacionRequest $request): RedirectResponse
    {
        $asignacion = Asignacion::create($request->validated());

        $proyecto = Proyecto::find($request->Id_Proyecto);
        return redirect()->route('produccion.proyectos.show', $proyecto->Id_Proyecto);
    }


    public function show(Asignacion $asignacion): View
    {
        $asignacion->load('empleado', 'proyecto');
        return view('produccion.asignaciones.show', compact('asignacion'));
    }

    public function edit(Asignacion $asignacion): View
    {
        $empleados = Empleado::all();
        $proyectos = Proyecto::all();
        return view('produccion.asignaciones.edit', compact('asignacion', 'empleados', 'proyectos'));
    }

    public function update(AsignacionRequest $request, Asignacion $asignacion): RedirectResponse
    {
        $asignacion->update($request->validated());
        return redirect()->route('produccion.asignaciones.show', $asignacion);
    }

    public function destroy(Asignacion $asignacion): RedirectResponse
    {
        $proyectoId = $asignacion->Id_Proyecto;
        $asignacion->delete();

        return redirect()->route('produccion.proyectos.show', $proyectoId);
    }
}
