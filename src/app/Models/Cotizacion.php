<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'Id_Cotizacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_Cliente',
        'Fecha',
        'Fecha_Vencimiento',
        'Estado',
        'Total',
        'Notas'
    ];

    protected $casts = [
        'Fecha' => 'datetime',
        'Fecha_Vencimiento' => 'datetime',
        'Total' => 'float'
    ];

    /**
     * Relación: Una cotización pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }

    /**
     * Relación: Una cotización tiene muchos detalles
     */
    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'Id_Cotizacion', 'Id_Cotizacion');
    }

    /**
     * Calcular total
     */
    public function calcularTotal()
    {
        return $this->detalles->sum(function ($detalle) {
            return $detalle->Cantidad * $detalle->Precio_Unitario;
        });
    }

    /**
     * Convertir a orden
     */
    public function convertirAOrden()
    {
        // Lógica para convertir cotización a orden
        $this->update(['Estado' => 'Convertida']);
    }
}
