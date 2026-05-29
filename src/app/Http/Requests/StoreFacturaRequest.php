<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Factura;

class StoreFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('Ventas');
    }

    public function rules(): array
    {
        return [
            'Id_Orden' => ['required', 'exists:ordenes,Id_Orden'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ordenId = $this->input('Id_Orden');
            if ($ordenId) {
                $orden = \App\Models\Orden::where('Id_Orden', $ordenId)->first();
                if (!$orden) {
                    $validator->errors()->add('Id_Orden', 'Orden no encontrada.');
                    return;
                }

                if (strtoupper($orden->Estado ?? '') !== 'EJECUTADA') {
                    $validator->errors()->add('Id_Orden', 'La orden debe estar en estado EJECUTADA.');
                    return;
                }

                $exists = Factura::where('Id_Orden', $ordenId)->exists();
                if ($exists) {
                    $validator->errors()->add('Id_Orden', 'La orden ya tiene una factura asociada.');
                }
            }
        });
    }
}
