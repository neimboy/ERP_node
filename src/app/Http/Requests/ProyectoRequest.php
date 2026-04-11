<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'Nombre.required' => 'El nombre del proyecto es obligatorio.',
            'Nombre.max' => 'El nombre no puede exceder 150 caracteres.',
            'Id_Cliente.required' => 'Debe seleccionar un cliente.',
            'Id_Cliente.exists' => 'El cliente seleccionado no existe.',
            'Fecha_Fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
