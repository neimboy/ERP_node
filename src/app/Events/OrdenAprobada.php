<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrdenAprobada
{
    use Dispatchable, SerializesModels;

    public $orden;
    public $cotizacion;

    public function __construct($orden, $cotizacion = null)
    {
        $this->orden = $orden;
        $this->cotizacion = $cotizacion;
    }
}
