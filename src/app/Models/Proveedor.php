<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'Id_Proveedor';

    protected $fillable = [
        'RUC',
        'Nombre',
        'Telefono'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'Id_Proveedor', 'Id_Proveedor');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'Id_Proveedor', 'Id_Proveedor');
    }
}

