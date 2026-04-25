<!DOCTYPE html>
<html>
<head>
    <title>Detalle Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Pago #{{ $pago->Id_Pago }}</h1>

        <div class="mb-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pago->Fecha)->format('Y-m-d') }}</div>
        <div class="mb-3"><strong>Factura:</strong>
            @if($pago->factura)
                <a href="{{ route('facturas.show', $pago->factura) }}" class="text-blue-600">FAC-{{ str_pad($pago->factura->Id_Factura,6,'0',STR_PAD_LEFT) }}</a>
                | Cliente: {{ optional(optional($pago->factura)->orden->cliente)->Nombre ?? 'N/A' }}
            @else
                N/A
            @endif
        </div>
        <div class="mb-3"><strong>Monto:</strong> S/ {{ number_format($pago->Monto,2) }}</div>
        <div class="mb-3"><strong>Método:</strong> {{ $pago->Metodo }}</div>

        <div class="mt-6">
            <a href="{{ route('pagos.index') }}" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Volver</a>
            <a href="{{ route('pagos.edit', $pago) }}" class="bg-green-600 text-white px-4 py-2 rounded">Editar</a>
        </div>
    </div>
</body>
</html>
