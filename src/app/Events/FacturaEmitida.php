<?php

namespace App\Events;

use App\Models\Factura;
use App\Models\Orden;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FacturaEmitida
{
    use Dispatchable, SerializesModels;

    public $factura;
    public $orden;

    public function __construct(Factura $factura, Orden $orden = null)
    {
        $this->factura = $factura;
        $this->orden = $orden;
    }
}
