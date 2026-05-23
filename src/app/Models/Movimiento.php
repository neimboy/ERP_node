<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'Id_Movimiento';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Producto',
        'Id_Proyecto',
        'Tipo',
        'Cantidad',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Id_Proyecto', 'Id_Proyecto');
    }
}
