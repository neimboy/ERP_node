<?php

namespace App\Http\Controllers\RRHH;
use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Empleado;
use App\Models\Puesto;   
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Mostrar la lista de contratos
     */
    public function index()
    {
        $contratos = Contrato::with(['empleado', 'puesto'])->get();
        return view('rrhh.contratos.index', compact('contratos'));
    }

    /**
     * Mostrar el formulario para crear un contrato
     */
    public function create()
    {
        $empleados = Empleado::all();
        $puestos = Puesto::all();
        
        return view('rrhh.contratos.create', compact('empleados', 'puestos'));
    }

    /**
     * Guardar el contrato en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'Id_Empleado'  => 'required|exists:empleados,Id_Empleado',
            'Id_Puesto'    => 'required|exists:puestos,Id_Puesto',
            'Fecha_Inicio' => 'required|date',
            'Fecha_Fin'    => 'nullable|date|after_or_equal:Fecha_Inicio',
        ]);

        try {
            Contrato::create($request->all());
            return redirect()->route('rrhh.contratos.index')->with('success', 'Contrato creado.');
        } catch (\Exception $e) {
            return dd($e->getMessage()); 
        }
    }

    public function destroy($id)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();

            return redirect()->route('rrhh.contratos.index')
                            ->with('success', 'El contrato ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el contrato: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $contrato = Contrato::findOrFail($id);
        $empleados = Empleado::all();
        $puestos = Puesto::all();

        return view('rrhh.contratos.edit', compact('contrato', 'empleados', 'puestos'));
    }

    public function descargarPDF($id)
    {
        $contrato = Contrato::with(['empleado', 'puesto'])->findOrFail($id);

        $path = public_path('images/logo-erp.png');
        $logoData = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $textoQR = "CONTRATO VERIFICADO - YUNIX INGENIEROS\n" .
                   "ID Registro: YUNIX-CTR-" . $contrato->Id_Contrato . "\n" .
                   "Empleado: " . $contrato->empleado->Nombre . " " . $contrato->empleado->Apellido . "\n" .
                   "Puesto: " . $contrato->puesto->Nombre_Puesto . "\n" .
                   "Sueldo: S/ " . number_format($contrato->puesto->Salario_Base ?? 1025, 2);

        $qrData = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($textoQR);

        $pdf = Pdf::loadView('rrhh.contratos.pdf', compact('contrato', 'logoData', 'qrData'));
        
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
        $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
        
        return $pdf->stream('Contrato_Laboral_' . $contrato->empleado->Nombre . '.pdf');
    }
}
