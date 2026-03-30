<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\CuentaContable;
use Illuminate\Http\Request;

class PlanContableController extends Controller
{
    public function index()
    {
        $cuentas = CuentaContable::orderBy('Codigo')->get();
        return view('contabilidad.plan_cuentas', compact('cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Codigo' => 'required|unique:cuenta_contable',
            'Nombre_Cuenta' => 'required',
            'Tipo' => 'required'
        ]);

        CuentaContable::create($request->all());
        return redirect()->back()->with('success', 'Cuenta creada correctamente.');
    }
}