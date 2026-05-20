<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyectoServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Nombre' => 'required|string|min:3|max:150',
            'Id_Cliente' => 'required|exists:clientes,Id_Cliente',
            'Fecha_Inicio' => 'nullable|date',
            'Fecha_Fin' => 'nullable|date|after_or_equal:Fecha_Inicio',
            'Estado' => 'nullable|string|max:50',
            'gastos' => 'nullable|array',
            'gastos.*.Descripcion' => 'required|string|max:255',
            'gastos.*.Monto' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'Nombre.required' => 'El nombre del proyecto es obligatorio.',
            'Id_Cliente.required' => 'Debe seleccionar un cliente.',
        ];
    }
}
