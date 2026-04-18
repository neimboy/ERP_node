<!DOCTYPE html>
<html>
<head>
    <title>Pagos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Pagos</h1>
            <a href="{{ route('pagos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Pago</a>
        </div>

        <form method="GET" action="{{ route('pagos.index') }}" class="mb-4">
            <div class="flex gap-2">
                <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por ID o cliente" class="border rounded p-2 w-full" />
                <button class="bg-gray-200 px-3 rounded">Buscar</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Fecha</th>
                        <th class="p-2 text-left">Factura</th>
                        <th class="p-2 text-left">Cliente</th>
                        <th class="p-2 text-right">Monto</th>
                        <th class="p-2 text-left">Método</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2">{{ $pago->Id_Pago }}</td>
                                <td class="p-2">{{ \Carbon\Carbon::parse($pago->Fecha)->format('Y-m-d') }}</td>
                            <td class="p-2">
                                @if($pago->factura)
                                    <a href="{{ route('facturas.show', $pago->factura) }}" class="text-blue-600">FAC-{{ str_pad($pago->factura->Id_Factura,6,'0',STR_PAD_LEFT) }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="p-2">{{ optional(optional($pago->factura)->orden->cliente)->Nombre ?? 'N/A' }}</td>
                            <td class="p-2 text-right">S/ {{ number_format($pago->Monto,2) }}</td>
                            <td class="p-2">{{ $pago->Metodo }}</td>
                            <td class="p-2 text-center">
                                <a href="{{ route('pagos.show', $pago) }}" class="text-indigo-600 mr-2">Ver</a>
                                <a href="{{ route('pagos.edit', $pago) }}" class="text-green-600 mr-2">Editar</a>
                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar pago?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center">No hay pagos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pagos->links() }}
        </div>
    </div>
</body>
</html>
