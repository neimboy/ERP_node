<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';
    protected $primaryKey = 'Id_Orden';
    public $timestamps = true;

    protected $fillable = [
        'Id_Cliente',
        'Fecha',
        'Estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrden::class, 'Id_Orden', 'Id_Orden');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'Id_Orden', 'Id_Orden');
    }
}
