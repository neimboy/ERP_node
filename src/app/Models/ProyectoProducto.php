<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoProducto extends Model
{
    protected $table = 'proyecto_productos';
    protected $primaryKey = 'Id_Proyecto_Producto';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Proyecto',
        'Id_Producto',
        'Cantidad',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Id_Proyecto', 'Id_Proyecto');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }
}
