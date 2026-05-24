<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOportunidad extends Model
{
    protected $table = 'detalle_oportunidades';
    protected $primaryKey = 'Id_Detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_Oportunidad',
        'Id_Producto',
        'Cantidad',
        'Precio_Unitario',
        'Descuento',
        'Total',
    ];

    protected $casts = [
        'Cantidad' => 'integer',
        'Precio_Unitario' => 'float',
        'Descuento' => 'float',
        'Total' => 'float',
    ];

    public function oportunidad()
    {
        return $this->belongsTo(Oportunidad::class, 'Id_Oportunidad', 'Id_Oportunidad');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }

    public function calcularTotal()
    {
        $subtotal = ($this->Cantidad ?? 0) * ($this->Precio_Unitario ?? 0);
        $descuento = $subtotal * (($this->Descuento ?? 0) / 100);
        return $subtotal - $descuento;
    }
}
