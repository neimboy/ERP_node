<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Oportunidad;
use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Factura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OportunidadController extends Controller
{


    public function index()
    {
        $oportunidades = Oportunidad::with('cliente')->orderByDesc('created_at')->paginate(15);
        return view('ventas.oportunidades.index', compact('oportunidades'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        return view('ventas.oportunidades.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Id_Cliente' => 'required|exists:clientes,Id_Cliente',
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'nullable|string',
            'Monto_Estimado' => 'nullable|numeric',
            // Agregar 'Ganada' y 'Cerrada/Ganada' como estados válidos
            'Estado' => 'required|in:Prospecto,Negociación,Cerrado,Ganada,Cerrada/Ganada',
            'Fecha_Cierre' => 'nullable|date',
        ]);

        Oportunidad::create($data);

        return redirect()->route('clientes.show', $data['Id_Cliente'])->with('success', 'Oportunidad creada correctamente.');
    }

    public function show(Oportunidad $oportunidad)
    {
        return view('ventas.oportunidades.show', compact('oportunidad'));
    }

    public function edit(Oportunidad $oportunidad)
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        return view('ventas.oportunidades.edit', compact('oportunidad', 'clientes'));
    }

    public function update(Request $request, Oportunidad $oportunidad)
    {
        $data = $request->validate([
            'Id_Cliente' => 'required|exists:clientes,Id_Cliente',
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'nullable|string',
            'Monto_Estimado' => 'nullable|numeric',
            // Agregar 'Ganada' y 'Cerrada/Ganada' como estados válidos
            'Estado' => 'required|in:Prospecto,Negociación,Cerrado,Ganada,Cerrada/Ganada',
            'Fecha_Cierre' => 'nullable|date',
        ]);

        $oportunidad->update($data);

        return redirect()->route('clientes.show', $data['Id_Cliente'])->with('success', 'Oportunidad actualizada.');
    }

    public function destroy(Oportunidad $oportunidad)
    {
        $clienteId = $oportunidad->Id_Cliente;
        $oportunidad->delete();
        return redirect()->route('clientes.show', $clienteId)->with('success', 'Oportunidad eliminada.');
    }

    /**
     * Cerrar una oportunidad (marcar como Cerrado y poner fecha de cierre)
     */
    public function cerrar(Oportunidad $oportunidad)
    {
        $oportunidad->update([
            'Estado' => 'Cerrado',
            'Fecha_Cierre' => now(),
        ]);

        return redirect()->route('clientes.show', $oportunidad->Id_Cliente)->with('success', 'Oportunidad cerrada.');
    }

    /**
     * Genera una orden de venta a partir de una oportunidad marcada como 'Ganada'.
     */
    public function generarOrden(Oportunidad $oportunidad)
    {
        // Evitar crear duplicados: si ya existe Id_Orden, redirigir a la orden
        if (Schema::hasColumn($oportunidad->getTable(), 'Id_Orden') && !empty($oportunidad->Id_Orden)) {
            return redirect()->route('ordenes.show', $oportunidad->Id_Orden)->with('info', 'Ya existe una orden para esta oportunidad.');
        }

        if ($oportunidad->Estado !== 'Ganada') {
            return back()->with('error', 'La oportunidad debe estar en estado "Ganada" para generar una orden.');
        }

        $orden = null;

        DB::transaction(function () use ($oportunidad, &$orden) {
            $orden = Orden::create([
                'Id_Cliente' => $oportunidad->Id_Cliente,
                'Fecha' => now(),
                'Estado' => 'Pendiente',
            ]);

            // Si la oportunidad tiene un monto estimado, creamos una factura asociada
            if (!empty($oportunidad->Monto_Estimado)) {
                Factura::create([
                    'Id_Orden' => $orden->Id_Orden,
                    'Fecha' => now(),
                    'Total' => $oportunidad->Monto_Estimado,
                    'Estado_Pago' => 'Pendiente',
                ]);
            }

            // Actualizar la oportunidad: marcar como cerrada/ganada y enlazar la orden si existe la columna
            $update = ['Estado' => 'Cerrada/Ganada', 'Fecha_Cierre' => now()];
            if (Schema::hasColumn($oportunidad->getTable(), 'Id_Orden')) {
                $update['Id_Orden'] = $orden->Id_Orden;
            }

            $oportunidad->update($update);
        });

        return redirect()->route('ordenes.show', $orden?->Id_Orden ?? 0)->with('success', 'Orden creada a partir de la oportunidad.');
    }
}
