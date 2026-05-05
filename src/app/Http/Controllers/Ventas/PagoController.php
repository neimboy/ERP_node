<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Factura;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $pagos = Pago::with('factura.orden.cliente')
            ->when($q, function ($query) use ($q) {
                $query->where('Id_Pago', $q)
                      ->orWhereHas('factura.orden.cliente', function ($q2) use ($q) { $q2->where('Nombre', 'like', "%{$q}%"); });
            })
            ->orderByDesc('Fecha')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.pagos.index', compact('pagos', 'q'));
    }

    public function create()
    {
        $facturas = Factura::with('orden.cliente', 'pagos')->get()->map(function ($f) {
            $pagado = $f->pagos->sum('Monto') ?? 0;
            $f->Saldo = $f->Total - $pagado;
            return $f;
        });

        return view('ventas.pagos.create', compact('facturas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_Factura' => 'required|exists:facturas,Id_Factura',
            'Fecha' => 'required|date',
            'Monto' => 'required|numeric|min:0',
            'Metodo' => 'required|string|max:50',
        ]);

        Pago::create($validated);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado');
    }

    public function show(Pago $pago)
    {
        $pago->load('factura.orden.cliente');
        return view('ventas.pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $facturas = Factura::with('orden.cliente', 'pagos')->get()->map(function ($f) {
            $pagado = $f->pagos->sum('Monto') ?? 0;
            $f->Saldo = $f->Total - $pagado;
            return $f;
        });

        return view('ventas.pagos.edit', compact('pago', 'facturas'));
    }

    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'Id_Factura' => 'required|exists:facturas,Id_Factura',
            'Fecha' => 'required|date',
            'Monto' => 'required|numeric|min:0',
            'Metodo' => 'required|string|max:50',
        ]);

        $pago->update($validated);

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado');
    }
}
