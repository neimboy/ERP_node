<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('Ventas');
    }

    public function rules(): array
    {
        return [
            'Id_Cliente' => 'required|exists:clientes,Id_Cliente',
            'lineas' => 'required|array|min:1',
            'lineas.*.Id_Producto' => 'required|exists:productos,Id_Producto',
            'lineas.*.cantidad' => 'required|integer|min:1',
        ];
    }
}
