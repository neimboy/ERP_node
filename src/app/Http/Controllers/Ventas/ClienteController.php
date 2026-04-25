<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin,Ventas']);
        $this->authorizeResource(Cliente::class, 'cliente');
    }

    public function index(Request $request)
    {
        $q = $request->get('q');

        $allowed = [10, 25, 50, 100];

        if ($request->has('per_page')) {
            $perPage = intval($request->get('per_page', 10));
            if (!in_array($perPage, $allowed)) {
                $perPage = 10;
            }
            // guardar preferencia en sesión para futuras visitas
            $request->session()->put('clientes.per_page', $perPage);
        } else {
            // si no viene por GET, intentar leer desde la sesión
            $perPage = intval($request->session()->get('clientes.per_page', 10));
            if (!in_array($perPage, $allowed)) {
                $perPage = 10;
            }
        }

        $clientes = Cliente::query()
            ->when($q, function ($query) use ($q) {
                $query->where('Nombre', 'like', "%{$q}%")
                      ->orWhere('Correo', 'like', "%{$q}%");
            })
            ->orderBy('Nombre')
            ->paginate($perPage)
            ->withQueryString();

        return view('ventas.clientes.index', compact('clientes', 'q', 'perPage'));
    }

    public function create()
    {
        $cliente = new Cliente();
        return view('ventas.clientes.create', compact('cliente'));
    }

    public function store(StoreClienteRequest $request)
    {
        Cliente::create($request->validated());
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

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}

