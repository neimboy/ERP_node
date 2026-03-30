<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro Diario - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Libro Diario (Asientos Contables)</h1>

        @foreach($asientos as $asiento)
        <div class="bg-white shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-800 text-white p-4 flex justify-between">
                <span><strong>Asiento #{{ $asiento->Id_Asiento }}</strong> - {{ $asiento->Fecha }}</span>
                <span class="italic text-gray-300">{{ $asiento->Glosa }}</span>
            </div>
            
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-sm">
                        <th class="p-3 text-left">Código</th>
                        <th class="p-3 text-left">Cuenta</th>
                        <th class="p-3 text-right">Debe</th>
                        <th class="p-3 text-right">Haber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asiento->detalles as $detalle)
                    <tr class="border-t">
                        <td class="p-3 text-blue-600">{{ $detalle->cuenta->Codigo }}</td>
                        <td class="p-3">{{ $detalle->cuenta->Nombre_Cuenta }}</td>
                        <td class="p-3 text-right">{{ $detalle->Debe > 0 ? number_format($detalle->Debe, 2) : '-' }}</td>
                        <td class="p-3 text-right">{{ $detalle->Haber > 0 ? number_format($detalle->Haber, 2) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-yellow-50 font-bold border-t-2 border-gray-300">
                        <td colspan="2" class="p-3 text-right">TOTALES:</td>
                        <td class="p-3 text-right text-green-700">{{ number_format($asiento->detalles->sum('Debe'), 2) }}</td>
                        <td class="p-3 text-right text-red-700">{{ number_format($asiento->detalles->sum('Haber'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach
    </div>
</body>
</html>