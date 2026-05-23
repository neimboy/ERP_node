<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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

    /**
     * Una oportunidad puede tener muchas cotizaciones
     */
    public function cotizaciones()
    {
        // Soporta both column naming schemes (oportunidad_id vs Id_Oportunidad)
        if (Schema::hasColumn('cotizaciones', 'oportunidad_id')) {
            return $this->hasMany(Cotizacion::class, 'oportunidad_id', 'Id_Oportunidad');
        }

        return $this->hasMany(Cotizacion::class, 'Id_Oportunidad', 'Id_Oportunidad');
    }

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
    }
}
