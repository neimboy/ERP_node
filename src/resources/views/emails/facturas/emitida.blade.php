<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura Emitida</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 680px; margin: 24px auto; padding: 20px; border: 1px solid #e6e6e6; border-radius: 8px; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .h1 { font-size:18px; font-weight:700; }
        .muted { color:#666; font-size:13px }
        .table { width:100%; border-collapse:collapse; margin-top:12px }
        .table td, .table th { padding:8px; border:1px solid #f0f0f0 }
        .total { font-weight:700; font-size:16px }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="h1">Factura Emitida</div>
                <div class="muted">FAC-{{ str_pad($factura->Id_Factura ?? '', 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="muted">Fecha: {{ optional($factura->created_at)->format('Y-m-d') ?? '' }}</div>
        </div>

        <p>Estimado cliente,</p>
        <p>Adjuntamos la factura generada por su orden. A continuación se muestra un resumen:</p>

        <table class="table">
            <tr>
                <th>Cliente</th>
                <td>{{ optional(optional($factura->orden)->cliente)->Nombre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td class="total">S/ {{ number_format($factura->Total ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>{{ $factura->Estado_Pago ?? 'N/A' }}</td>
            </tr>
        </table>

        <p class="muted">No responda a este correo. Si necesita asistencia, contacte con soporte.</p>
    </div>
</body>
</html>
