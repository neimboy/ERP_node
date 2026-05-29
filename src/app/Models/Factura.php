<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $primaryKey = 'Id_Factura';
    protected $fillable = [
        'Id_Orden', 'Id_Cliente', 'Numero_Factura', 'Fecha',
        'Subtotal', 'IGV', 'Total', 'Estado_Pago', 'Estado'
    ];

    protected $casts = [
        'Subtotal' => 'decimal:2',
        'IGV' => 'decimal:2',
        'Total' => 'decimal:2',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'Id_Factura', 'Id_Factura');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, 'Id_Factura', 'Id_Factura');
    }

    /**
     * Saldo pendiente calculado: total - sum(pagos)
     */
    public function getSaldoAttribute()
    {
        $pagado = $this->pagos()->sum('Monto') ?? 0;
        return (float) ($this->Total ?? 0) - (float) $pagado;
    }
}
