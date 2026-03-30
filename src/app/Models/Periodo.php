<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';
    protected $primaryKey = 'Id_Periodo';
    protected $fillable = ['Año', 'Mes'];

    // Un periodo tiene muchos asientos
    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'Id_Periodo');
    }
}