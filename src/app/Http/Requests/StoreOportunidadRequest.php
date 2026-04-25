<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOportunidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('Ventas');
    }

    public function rules(): array
    {
        return [
            'Id_Cliente' => 'required|exists:clientes,Id_Cliente',
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'nullable|string',
            'Monto_Estimado' => 'nullable|numeric',
            'Estado' => 'required|in:Prospecto,Negociación,Cerrado,Ganada',
            'Fecha_Cierre' => 'nullable|date',
        ];
    }
}
