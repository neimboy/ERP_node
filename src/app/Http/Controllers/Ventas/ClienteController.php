<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q = $request->get('q');

        $clientes = Cliente::query()
            ->when($q, function ($query) use ($q) {
                $query->where('Nombre', 'like', "%{$q}%")
                      ->orWhere('Correo', 'like', "%{$q}%");
            })
            ->orderBy('Nombre')
            ->paginate(15)
            ->withQueryString();

        return view('ventas.clientes.index', compact('clientes', 'q'));
    }

    public function create()
    {
        return view('ventas.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Documento' => 'required|unique:clientes,Documento|max:8',
            'Nombre' => 'required|string|max:150',
            'Correo' => 'nullable|email',
            'Telefono' => 'nullable|string|max:9',
        ]);

        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('ventas.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('ventas.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'Documento' => [
                'required',
                Rule::unique('clientes', 'Documento')->ignore($cliente->Id_Cliente, 'Id_Cliente'),
                'max:8'
            ],
            'Nombre' => 'required|string|max:150',
            'Correo' => 'nullable|email',
            'Telefono' => 'nullable|string|max:9',
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}

