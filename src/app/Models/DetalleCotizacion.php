<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table = 'detalle_cotizaciones';
    protected $primaryKey = 'Id_Detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_Cotizacion',
        'Id_Producto',
        'Cantidad',
        'Precio_Unitario',
        'Costo_Unitario',
        'Descuento',
        'Total'
    ];

    protected $casts = [
        'Cantidad' => 'integer',
        'Precio_Unitario' => 'float',
        'Costo_Unitario' => 'float',
        'Descuento' => 'float',
        'Total' => 'float'
    ];

    /**
     * Relación: Un detalle pertenece a una cotización
     */
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'Id_Cotizacion', 'Id_Cotizacion');
    }

    /**
     * Relación: Un detalle pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }

    /**
     * Calcular total con descuento
     */
    public function calcularTotal()
    {
        $subtotal = $this->Cantidad * $this->Precio_Unitario;
        $descuento = $subtotal * ($this->Descuento / 100);
        return $subtotal - $descuento;
    }
}
