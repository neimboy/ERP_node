<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyectoProduccionRequest extends FormRequest
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
            'productos' => 'required|array|min:1',
            'productos.*.Id_Producto' => 'required|exists:productos,Id_Producto',
            'productos.*.Cantidad' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'Nombre.required' => 'El nombre del proyecto es obligatorio.',
            'Id_Cliente.required' => 'Debe seleccionar un cliente.',
            'productos.required' => 'Debe agregar al menos un producto.',
            'productos.min' => 'Debe agregar al menos un producto.',
            'productos.*.Id_Producto.required' => 'Cada producto debe tener un ID válido.',
            'productos.*.Cantidad.required' => 'Cada producto debe tener una cantidad.',
            'productos.*.Cantidad.min' => 'La cantidad mínima por producto es 1.',
        ];
    }
}
