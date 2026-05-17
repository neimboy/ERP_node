<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoGasto extends Model
{
    protected $table = 'proyecto_gastos';
    protected $primaryKey = 'Id_Gasto';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Proyecto',
        'Descripcion',
        'Monto',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Id_Proyecto', 'Id_Proyecto');
    }
}
