<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProyectoRequest;
use App\Http\Requests\StoreProyectoProduccionRequest;
use App\Http\Requests\StoreProyectoServicioRequest;
use App\Http\Requests\UpdateProyectoProduccionRequest;
use App\Http\Requests\UpdateProyectoServicioRequest;
use App\Models\Proyecto;
use App\Models\Cliente;
use App\Services\ProyectoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    protected ProyectoService $proyectoService;

    public function __construct(ProyectoService $proyectoService)
    {
        $this->proyectoService = $proyectoService;
    }

    public function index(): View
    {
        $proyectos = Proyecto::with('cliente', 'asignaciones.empleado')->get();
        return view('produccion.index', compact('proyectos'));
    }

    public function tipoProyecto(): View
    {
        return view('produccion.tipo_proyecto');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('proyectos.tipo');
    }

    public function createProduccion(): View
    {
        $clientes = Cliente::all();
        $productos = $this->proyectoService->productosDisponibles();
        return view('produccion.create.create_proyecto_produccion', compact('clientes', 'productos'));
    }

    public function createServicio(): View
    {
        $clientes = Cliente::all();
        return view('produccion.create.create_servicio', compact('clientes'));
    }

    public function storeProduccion(StoreProyectoProduccionRequest $request): RedirectResponse
    {
        Proyecto::create($request->validated());
        return redirect()->route('proyectos.index');
    }

    public function show(Proyecto $proyecto): View
    {
        $proyecto->load('cliente', 'asignaciones.empleado');

        if ($proyecto->Tipo === 'produccion') {
            $proyecto->load('productos');
            return view('produccion.show.show_proyecto_produccion', compact('proyecto'));
        }

        $proyecto->load('gastos');
        $totalGastos = $proyecto->gastos->sum('Monto');
        return view('produccion.show.show_servicio', compact('proyecto', 'totalGastos'));
    }

    public function edit(Proyecto $proyecto): View
    {
        $clientes = Cliente::all();

        if ($proyecto->Tipo === 'produccion') {
            $proyecto->load('productos');
            $productos = $this->proyectoService->productosDisponibles();
            return view('produccion.edit.edit_proyecto_produccion', compact('proyecto', 'clientes', 'productos'));
        }

        $proyecto->load('gastos');
        return view('produccion.edit.edit_servicio', compact('proyecto', 'clientes'));
    }

    public function updateProduccion(UpdateProyectoProduccionRequest $request, Proyecto $proyecto): RedirectResponse
    {
        $proyecto->update($request->validated());
        return redirect()->route('proyectos.show', $proyecto);
    }

    public function destroy(Proyecto $proyecto): RedirectResponse
    {
        $proyecto->delete();
        return redirect()->route('proyectos.index');
    }
}
