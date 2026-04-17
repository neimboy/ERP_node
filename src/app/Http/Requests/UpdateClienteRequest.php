<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('Ventas');
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente') ? $this->route('cliente')->Id_Cliente : null;

        return [
            'Documento' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('clientes', 'Documento')->ignore($clienteId, 'Id_Cliente'),
            ],
            'Nombre' => 'required|string|max:150',
            'Correo' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clientes', 'Correo')->ignore($clienteId, 'Id_Cliente'),
            ],
            'Telefono' => 'nullable|string|max:20',
        ];
    }
}
