<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'Id_Cliente';

    protected $fillable = [
        'Documento',
        'Nombre',
        'Correo',
        'Telefono'
    ];
}
