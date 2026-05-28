<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'Id_Notificacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_Producto',
        'Cantidad_Requerida',
        'Id_Proyecto',
        'Mensaje',
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
