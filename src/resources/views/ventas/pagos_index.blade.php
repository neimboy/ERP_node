<!DOCTYPE html>
<html>
<head>
    <title>Pagos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">💰 Pagos</h1>
            <a href="{{ route('pagos.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Nuevo Pago</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Factura</th>
                    <th class="border p-2">Fecha</th>
                    <th class="border p-2">Monto</th>
                    <th class="border p-2">Método</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                <tr>
                    <td class="border p-2">{{ $pago->Id_Pago }}</td>
                    <td class="border p-2">{{ $pago->Id_Factura }}</td>
                    <td class="border p-2">{{ $pago->Fecha }}</td>
                    <td class="border p-2 text-right">S/ {{ number_format($pago->Monto, 2) }}</td>
                    <td class="border p-2">{{ $pago->Metodo }}</td>
                    <td class="border p-2">
                        <a href="{{ route('pagos.show', $pago->Id_Pago) }}" class="text-blue-600">Ver</a>
                        <a href="{{ route('pagos.edit', $pago->Id_Pago) }}" class="text-yellow-600 ml-2">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>