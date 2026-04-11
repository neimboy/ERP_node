<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Id_Empleado' => 'required|exists:empleados,Id_Empleado',
            'Id_Proyecto' => 'required|exists:proyectos,Id_Proyecto',
            'Horas_Asignadas' => 'required|integer|min:1|max:999',
        ];
    }

    public function messages(): array
    {
        return [
            'Id_Empleado.required' => 'Debe seleccionar un empleado.',
            'Id_Empleado.exists' => 'El empleado seleccionado no existe.',
            'Id_Proyecto.required' => 'Debe seleccionar un proyecto.',
            'Id_Proyecto.exists' => 'El proyecto seleccionado no existe.',
            'Horas_Asignadas.required' => 'Las horas asignadas son obligatorias.',
            'Horas_Asignadas.integer' => 'Las horas deben ser un número entero.',
            'Horas_Asignadas.min' => 'Las horas deben ser al menos 1.',
        ];
    }
}