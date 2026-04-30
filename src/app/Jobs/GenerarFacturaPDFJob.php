<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class GenerarFacturaPDFJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $facturaId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $facturaId)
    {
        $this->facturaId = $facturaId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simular generación de PDF
        Log::info("[GenerarFacturaPDFJob] Generando PDF para factura Id={$this->facturaId}");

        // (Aquí se generaría el PDF usando una librería: snappy/dompdf, etc.)
        sleep(1); // Simular trabajo

        Log::info("[GenerarFacturaPDFJob] Enviando PDF de la factura Id={$this->facturaId} por correo al cliente");

        // (Aquí se enviaría el correo con Mail::send o Mailable)
    }
}
