<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oportunidad extends Model
{
    use HasFactory;

    protected $table = 'oportunidades_crm';
    protected $primaryKey = 'Id_Oportunidad';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Cliente',
        'Titulo',
        'Descripcion',
        'Monto_Estimado',
        'Estado',
        'Fecha_Cierre',
    ];

    protected $casts = [
        'Fecha_Cierre' => 'date',
        'Monto_Estimado' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }
}
