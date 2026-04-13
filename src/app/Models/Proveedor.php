<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'contacto', 'telefono', 'direccion'];

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
