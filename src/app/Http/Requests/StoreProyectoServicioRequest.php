<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyectoServicioRequest extends FormRequest
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
            'gastos.*.Descripcion.required' => 'Cada gasto debe tener una descripción.',
            'gastos.*.Monto.required' => 'Cada gasto debe tener un monto.',
            'gastos.*.Monto.min' => 'El monto del gasto debe ser mayor o igual a 0.',
        ];
    }
}
