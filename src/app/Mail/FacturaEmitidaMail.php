<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Factura;
use Illuminate\Support\Facades\Storage;

class FacturaEmitidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;
    public ?string $pdfPath = null;

    /**
     * Create a new message instance.
     */
    public function __construct(int $facturaId, ?string $pdfPath = null)
    {
        $this->factura = Factura::with(['orden', 'orden.cliente', 'pagos'])->where('Id_Factura', $facturaId)->first();
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $numero = $this->factura->Id_Factura ?? '';

        $mail = $this->subject("Factura emitida FAC-" . str_pad($numero, 6, '0', STR_PAD_LEFT))
                     ->view('emails.facturas.emitida')
                     ->with(['factura' => $this->factura]);

        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $mail->attach($this->pdfPath, [
                'as' => 'Factura-' . str_pad($numero, 6, '0', STR_PAD_LEFT) . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
