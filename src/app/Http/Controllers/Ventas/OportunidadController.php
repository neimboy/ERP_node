<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Oportunidad;
use App\Models\Cliente;

class OportunidadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin,Ventas']);
    }

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
            'Estado' => 'required|in:Prospecto,Negociación,Cerrado',
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
            'Estado' => 'required|in:Prospecto,Negociación,Cerrado',
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
}
