<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignacionRequest;
use App\Models\Asignacion;
use App\Models\Empleado;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AsignacionController extends Controller
{
    public function index(): View
    {
        $asignaciones = Asignacion::with('empleado', 'proyecto')->get();
        return view('produccion.asignaciones.index', compact('asignaciones'));
    }

    public function create(): View
    {
        $empleados = Empleado::all();
        $proyectos = Proyecto::all();
        return view('produccion.asignaciones.create', compact('empleados', 'proyectos'));
    }

    public function store(AsignacionRequest $request): RedirectResponse
    {
        Asignacion::create($request->validated());
        return redirect()->route('asignaciones.index');
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
        return redirect()->route('asignaciones.show', $asignacion);
    }

    public function destroy(Asignacion $asignacion): RedirectResponse
    {
        $asignacion->delete();
        return redirect()->route('asignaciones.index');
    }
}
