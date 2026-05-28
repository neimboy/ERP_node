<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentaContable;
use Illuminate\Http\Request;

class PlanContableController extends Controller
{
    private const TIPOS_VALIDOS = [
        'Activo', 'Activo (Contra)', 'Pasivo', 'Patrimonio',
        'Ingreso', 'Gasto', 'Costo',
    ];

    public function index()
    {
        $cuentas = CuentaContable::orderBy('Codigo', 'asc')->get();

        // Agrupamos por tipo para mostrar en secciones
        $agrupadas = $cuentas->groupBy('Tipo');

        return view('contabilidad.plan_cuentas.index', compact('cuentas', 'agrupadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Codigo'        => 'required|string|max:20|unique:cuenta_contable,Codigo',
            'Nombre_Cuenta' => 'required|string|max:150',
            'Tipo'          => 'required|string|in:' . implode(',', self::TIPOS_VALIDOS),
        ]);

        CuentaContable::create($request->only('Codigo', 'Nombre_Cuenta', 'Tipo'));

        return redirect()->route('contabilidad.plan_cuentas')
            ->with('success', "Cuenta {$request->Codigo} – {$request->Nombre_Cuenta} registrada correctamente.");
    }

    public function edit($id)
    {
        $cuenta = CuentaContable::findOrFail($id);
        $tipos  = self::TIPOS_VALIDOS;

        return view('contabilidad.plan_cuentas.edit', compact('cuenta', 'tipos'));
    }

    public function update(Request $request, $id)
    {
        $cuenta = CuentaContable::findOrFail($id);

        $request->validate([
            'Codigo'        => "required|string|max:20|unique:cuenta_contable,Codigo,{$cuenta->Id_Cuenta},Id_Cuenta",
            'Nombre_Cuenta' => 'required|string|max:150',
            'Tipo'          => 'required|string|in:' . implode(',', self::TIPOS_VALIDOS),
        ]);

        $cuenta->update($request->only('Codigo', 'Nombre_Cuenta', 'Tipo'));

        return redirect()->route('contabilidad.plan_cuentas')
            ->with('success', 'Cuenta contable actualizada.');
    }

    public function destroy($id)
    {
        $cuenta = CuentaContable::findOrFail($id);

        // Verificar que la cuenta no tenga movimientos registrados
        if ($cuenta->detalles()->exists()) {
            return redirect()->route('contabilidad.plan_cuentas')
                ->with('error', "No se puede eliminar la cuenta {$cuenta->Codigo} porque tiene movimientos registrados.");
        }

        $cuenta->delete();

        return redirect()->route('contabilidad.plan_cuentas')
            ->with('success', "Cuenta {$cuenta->Codigo} eliminada.");
    }
}