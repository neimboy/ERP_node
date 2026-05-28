<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'Id_Cotizacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_Cliente',
        'Fecha',
        'Fecha_Vencimiento',
        'Estado',
        'Total',
        'Subtotal',  // 🆕 Añadido para dar soporte a la lógica del controlador
        'Impuesto',  // 🆕 Añadido
        'total',     // 🆕 Soporte lowercase
        'subtotal',  // 🆕
        'impuesto',  // 🆕
        'Costos_Directos',
        'Gastos_Generales',
        'Utilidad',
        'Notas'
    ];

    protected $casts = [
        'Fecha' => 'datetime',
        'Fecha_Vencimiento' => 'datetime',
        'Total' => 'float',
        'Costos_Directos' => 'float',
        'Gastos_Generales' => 'float',
        'Utilidad' => 'float',
        'Subtotal' => 'float',
        'Impuesto' => 'float'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $attrsLower = array_change_key_case($model->getAttributes(), CASE_LOWER);

            if ((Schema::hasColumn($model->getTable(), 'Fecha') || Schema::hasColumn($model->getTable(), 'fecha')) && empty($attrsLower['fecha'])) {
                $fechaValor = $model->getAttribute('Fecha') ?? $model->getAttribute('fecha') ?? now();
                $model->setAttribute('Fecha', $fechaValor);

                $raw = $model->getAttributes();
                if (array_key_exists('fecha', $raw)) {
                    unset($raw['fecha']);
                    $model->setRawAttributes($raw);
                }
            }

            if ((Schema::hasColumn($model->getTable(), 'Fecha_Vencimiento') || Schema::hasColumn($model->getTable(), 'fecha_vencimiento')) && empty($attrsLower['fecha_vencimiento'])) {
                $fv = $model->getAttribute('Fecha_Vencimiento') ?? $model->getAttribute('fecha_vencimiento') ?? ($model->getAttribute('Fecha') ? $model->getAttribute('Fecha')->addDays(30) : now()->addDays(30));
                $model->setAttribute('Fecha_Vencimiento', $fv);
                $raw2 = $model->getAttributes();
                if (array_key_exists('fecha_vencimiento', $raw2)) {
                    unset($raw2['fecha_vencimiento']);
                    $model->setRawAttributes($raw2);
                }
            }
        });
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'Id_Cotizacion', 'Id_Cotizacion');
    }

    public function oportunidad()
    {
        if (Schema::hasColumn($this->getTable(), 'oportunidad_id')) {
            return $this->belongsTo(Oportunidad::class, 'oportunidad_id', 'Id_Oportunidad');
        }
        return $this->belongsTo(Oportunidad::class, 'Id_Oportunidad', 'Id_Oportunidad');
    }

    public function orden()
    {
        if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
            return $this->hasOne(Orden::class, 'Id_Cotizacion', 'Id_Cotizacion');
        }
        if (Schema::hasColumn($this->getTable(), 'Id_Orden')) {
            return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
        }
        if (Schema::hasColumn($this->getTable(), 'id_orden')) {
            return $this->belongsTo(Orden::class, 'id_orden', 'Id_Orden');
        }
        return $this->hasOne(Orden::class, 'Id_Orden', 'Id_Orden');
    }

    public function calcularTotal()
    {
        // Usar el total guardado en cada detalle (precio*cantidad - descuento)
        return $this->detalles->sum(function ($detalle) {
            return $detalle->Total;
        });
    }

    /**
     * Mapeamos de forma limpia el cambio de estado a nivel de modelo
     */
    public function marcarComoConvertida(): bool
    {
        $columnaEstado = Schema::hasColumn($this->getTable(), 'Estado') ? 'Estado' : 'estado';
        return $this->update([$columnaEstado => 'CONVERTIDA']);
    }

    public function isExpired(): bool
    {
        $fv = $this->Fecha_Vencimiento ?? $this->fecha_vencimiento ?? null;
        if (empty($fv)) return false;
        return $fv->lt(now());
    }

    public function checkAndMarkVencida(): void
    {
        try {
            if (!$this->isExpired()) return;

            $estado = strtoupper($this->Estado ?? $this->estado ?? '');
            $skip = ['ACEPTADA', 'RECHAZADA', 'CONVERTIDA', 'VENCIDA'];
            if (in_array($estado, $skip)) return;

            $columnaEstado = Schema::hasColumn($this->getTable(), 'Estado') ? 'Estado' : 'estado';
            $this->{$columnaEstado} = 'VENCIDA';
            $this->save();
        } catch (\Exception $e) {
            if (class_exists('Illuminate\Support\Facades\Log')) {
                \Illuminate\Support\Facades\Log::error('Error marcando cotización como vencida: ' . $e->getMessage());
            }
        }
    }
}
