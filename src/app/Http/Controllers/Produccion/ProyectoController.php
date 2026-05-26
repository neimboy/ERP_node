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
use App\Services\PdfService;
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
        $proyecto = $this->proyectoService->crearProyectoProduccion(
            $request->only(['Nombre', 'Id_Cliente', 'Fecha_Inicio', 'Fecha_Fin', 'Estado']),
            $request->input('productos', [])
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Proyecto de producción creado correctamente.');
    }

    public function storeServicio(StoreProyectoServicioRequest $request): RedirectResponse
    {
        $proyecto = $this->proyectoService->crearProyectoServicio(
            $request->only(['Nombre', 'Id_Cliente', 'Fecha_Inicio', 'Fecha_Fin', 'Estado']),
            $request->input('gastos', [])
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Servicio creado correctamente.');
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
        $this->proyectoService->actualizarProyectoProduccion(
            $proyecto,
            $request->only(['Nombre', 'Id_Cliente', 'Fecha_Inicio', 'Fecha_Fin', 'Estado']),
            $request->input('nuevos_productos', [])
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Proyecto de producción actualizado correctamente.');
    }

    public function updateServicio(UpdateProyectoServicioRequest $request, Proyecto $proyecto): RedirectResponse
    {
        $this->proyectoService->actualizarProyectoServicio(
            $proyecto,
            $request->only(['Nombre', 'Id_Cliente', 'Fecha_Inicio', 'Fecha_Fin', 'Estado']),
            $request->input('gastos', [])
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function reporte(Proyecto $proyecto, PdfService $pdfService)
    {
        $proyecto->load('cliente', 'asignaciones.empleado', 'productos', 'gastos');

        $folio = 'REP-' . date('Y') . '-' . str_pad($proyecto->Id_Proyecto, 4, '0', STR_PAD_LEFT);

        return $pdfService->visualizar('reporte_proyecto', [
            'proyecto' => $proyecto,
            'folio' => $folio,
        ], "reporte_{$proyecto->Id_Proyecto}");
    }

    public function destroy(Proyecto $proyecto): RedirectResponse
    {
        $proyecto->delete();
        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    public function productosDisponibles(): \Illuminate\Http\JsonResponse
    {
        $productos = $this->proyectoService->productosDisponibles();
        return response()->json($productos);
    }

    public function agregarProductos(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.Id_Producto' => 'required|exists:productos,Id_Producto',
            'productos.*.Cantidad' => 'required|integer|min:1',
        ]);

        $this->proyectoService->actualizarProyectoProduccion(
            $proyecto,
            $proyecto->only(['Nombre', 'Id_Cliente', 'Fecha_Inicio', 'Fecha_Fin', 'Estado']),
            $request->input('productos', [])
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Productos agregados correctamente.');
    }

    public function devolverProductos(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.Id_Producto' => 'required|exists:productos,Id_Producto',
            'productos.*.Cantidad' => 'required|integer|min:1',
        ]);

        $this->proyectoService->devolverProductos($proyecto, $request->input('productos', []));

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Productos devueltos al inventario correctamente.');
    }

    public function notificarSinStock(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $request->validate([
            'Id_Producto' => 'required|exists:productos,Id_Producto',
            'Cantidad' => 'required|integer|min:1',
        ]);

        $this->proyectoService->notificarSinStock(
            $request->input('Id_Producto'),
            $request->input('Cantidad'),
            $proyecto->Id_Proyecto
        );

        return redirect()->route('proyectos.show', $proyecto)
            ->with('success', 'Notificación enviada al módulo de Inventario.');
    }

    public function notificarSinStockGeneral(Request $request): RedirectResponse
    {
        $request->validate([
            'Id_Producto' => 'required|exists:productos,Id_Producto',
            'Cantidad' => 'required|integer|min:1',
        ]);

        $this->proyectoService->notificarSinStock(
            $request->input('Id_Producto'),
            $request->input('Cantidad')
        );

        return redirect()->back()
            ->with('success', 'Notificación enviada al módulo de Inventario.');
    }
}
