<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('Ventas');
    }

    public function rules(): array
    {
        return [
            'Documento' => 'nullable|string|max:20|unique:clientes,Documento',
            'Nombre' => 'required|string|max:150',
            'Correo' => 'nullable|email|max:150|unique:clientes,Correo',
            'Telefono' => 'nullable|string|max:20',
        ];
    }
}
