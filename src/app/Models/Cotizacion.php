<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
     * Relación: Una cotización puede pertenecer a una oportunidad
     */
    public function oportunidad()
    {
        if (Schema::hasColumn($this->getTable(), 'oportunidad_id')) {
            return $this->belongsTo(Oportunidad::class, 'oportunidad_id', 'Id_Oportunidad');
        }

        return $this->belongsTo(Oportunidad::class, 'Id_Oportunidad', 'Id_Oportunidad');
    }

    /**
     * Relación: Una cotización puede tener una orden asociada
     */
    public function orden()
    {
        // Si la tabla ordenes tiene Id_Cotizacion
        if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
            return $this->hasOne(Orden::class, 'Id_Cotizacion', 'Id_Cotizacion');
        }

        // Si la cotización almacena el Id_Orden como columna
        if (Schema::hasColumn($this->getTable(), 'Id_Orden')) {
            return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
        }

        // Fallback: intentar vincular por Id_Orden lowercase
        if (Schema::hasColumn($this->getTable(), 'id_orden')) {
            return $this->belongsTo(Orden::class, 'id_orden', 'Id_Orden');
        }

        // Relación vacía por defecto
        return $this->hasOne(Orden::class, 'Id_Orden', 'Id_Orden');
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
