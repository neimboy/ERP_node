<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = DB::table('pagos')->get();
        return view('ventas.pagos_index', ['pagos' => $pagos]);
    }

    public function create()
    {
        $facturas = DB::table('facturas')->get();
        return view('ventas.pagos_create', ['facturas' => $facturas]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_Factura' => 'required|exists:facturas,Id_Factura',
            'Fecha' => 'required|date',
            'Monto' => 'required|numeric|min:0',
            'Metodo' => 'required|string'
        ]);

        DB::table('pagos')->insert($request->all());
        
        return redirect()->route('pagos.index')->with('success', 'Pago registrado');
    }

    public function show($id)
    {
        $pago = DB::table('pagos')->where('Id_Pago', $id)->first();
        return view('ventas.pagos_show', ['pago' => $pago]);
    }

    public function edit($id)
    {
        $pago = DB::table('pagos')->where('Id_Pago', $id)->first();
        $facturas = DB::table('facturas')->get();
        return view('ventas.pagos_edit', ['pago' => $pago, 'facturas' => $facturas]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_Factura' => 'required|exists:facturas,Id_Factura',
            'Fecha' => 'required|date',
            'Monto' => 'required|numeric|min:0',
            'Metodo' => 'required|string'
        ]);

        DB::table('pagos')->where('Id_Pago', $id)->update($request->all());
        
        return redirect()->route('pagos.index')->with('success', 'Pago actualizado');
    }

    public function destroy($id)
    {
        DB::table('pagos')->where('Id_Pago', $id)->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado');
    }
}