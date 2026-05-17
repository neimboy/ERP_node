<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'Id_Categoria';
    public $timestamps = true;

    protected $fillable = [
        'Nombre'
    ];

    // 🔹 Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'Id_Categoria', 'Id_Categoria');
    }
}
