<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generar(string $vista, array $datos, array $config = [])
    {
        $paper = $config['paper'] ?? 'a4';
        $orientation = $config['orientation'] ?? 'portrait';

        return Pdf::loadView("pdf.$vista", $datos)
            ->setPaper($paper, $orientation);
    }

    public function visualizar(string $vista, array $datos, string $nombre, array $config = [])
    {
        return $this->generar($vista, $datos, $config)
            ->stream("{$nombre}.pdf");
    }

    public function descargar(string $vista, array $datos, string $nombre, array $config = [])
    {
        return $this->generar($vista, $datos, $config)
            ->download("{$nombre}.pdf");
    }

    public function guardar(string $vista, array $datos, string $nombre, array $config = []): string
    {
        $path = "pdfs/{$nombre}.pdf";
        $this->generar($vista, $datos, $config)->save(storage_path("app/public/{$path}"));
        return $path;
    }
}
