<?php

namespace App\Models\Contabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    protected $table = 'periodos';
    protected $primaryKey = 'Id_Periodo';
    public $timestamps = true;

    protected $fillable = [
        'Año',
        'Mes',
        'Estado', // 'Abierto' | 'Cerrado'
    ];

    /**
     * Scopes de conveniencia
     */
    public function scopeAbiertos($query)
    {
        return $query->where('Estado', 'Abierto');
    }

    public function scopeCerrados($query)
    {
        return $query->where('Estado', 'Cerrado');
    }

    /**
     * Devuelve el nombre del mes en español para mostrar en vistas.
     */
    public function getNombreMesAttribute(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
        return $meses[$this->Mes] ?? "Mes {$this->Mes}";
    }

    /**
     * Etiqueta completa: "Enero 2025"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->nombre_mes} {$this->Año}";
    }

    public function asientos(): HasMany
    {
        return $this->hasMany(Asiento::class, 'Id_Periodo', 'Id_Periodo');
    }
}