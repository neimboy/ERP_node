<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Compra #{{ $compra->Id_Orden_Compra }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #374151; /* gris-700 */
            background-color: #f3f4f6; /* gris-100 */
            display: flex;
            justify-content: center;
            padding: 40px;
        }
        .documento {
            background: #fff;
            width: 600px; /* más angosto */
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1f2937; /* gris-800 */
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #e5e7eb; /* gris-200 */
            color: #1f2937;
            font-weight: bold;
        }
        th, td {
            border: 1px solid #d1d5db; /* gris-300 */
            padding: 8px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9fafb; /* gris-50 */
        }
        .totales {
            margin-top: 20px;
            text-align: right;
        }
        .totales p {
            margin: 4px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="documento">
        <h2>Comprobante de Compra #{{ $compra->Id_Orden_Compra }}</h2>
        <p><strong>Proveedor:</strong> {{ $compra->proveedor->Nombre }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->Fecha)->format('d/m/Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compra->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->Nombre }}</td>
                        <td>{{ $detalle->Cantidad }}</td>
                        <td>{{ number_format($detalle->Costo, 2) }}</td>
                        <td>{{ number_format($detalle->Cantidad * $detalle->Costo, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totales">
            <p>Total: {{ number_format($compra->total, 2) }}</p>
        </div>
    </div>
</body>
</html>
