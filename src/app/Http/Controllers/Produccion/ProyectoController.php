<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProyectoRequest;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProyectoController extends Controller
{

    public function index(): View
    {
        $proyectos = Proyecto::with('cliente', 'asignaciones.empleado')->get();
        return view('produccion.index', compact('proyectos'));
    }

    public function create(): View
    {
        $clientes = \App\Models\Cliente::all();
        return view('produccion.create', compact('clientes'));
    }

    public function store(ProyectoRequest $request):RedirectResponse
    {
        Proyecto::create($request->validated());
        return redirect()->route('produccion.proyectos.index');
    }

    public function show(Proyecto $proyecto): View
    {
        $proyecto->load('cliente', 'asignaciones.empleado');
        return view('produccion.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto): View
    {
        $clientes = \App\Models\Cliente::all();
        return view('produccion.edit', compact('proyecto', 'clientes'));
    }

    public function update(ProyectoRequest $request, Proyecto $proyecto):RedirectResponse
    {
        $proyecto->update($request->validated());
        return redirect()->route('produccion.proyectos.show', $proyecto);
    }

    public function destroy(Proyecto $proyecto):RedirectResponse
    {
        $proyecto->delete();
        return redirect()->route('produccion.proyectos.index');
    }
}
