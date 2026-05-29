<?php

namespace App\Events;

use App\Models\Orden;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrdenEjecutada
{
    use Dispatchable, SerializesModels;

    public $orden;
    public $detalles;

    public function __construct(Orden $orden, $detalles = null)
    {
        $this->orden = $orden;
        $this->detalles = $detalles;
    }
}
