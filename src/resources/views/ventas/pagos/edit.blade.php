<!DOCTYPE html>
<html>
<head>
    <title>Editar Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">

        <h1 class="text-2xl font-bold mb-6">Editar Pago #{{ $pago->Id_Pago }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pagos.update', $pago) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-bold mb-2">Factura</label>
                <select name="Id_Factura" class="w-full border rounded p-2" required>
                    <option value="">Seleccione una factura</option>
                    @foreach($facturas as $factura)
                        <option value="{{ $factura->Id_Factura }}"
                            {{ (old('Id_Factura') ?? $pago->Id_Factura) == $factura->Id_Factura ? 'selected' : '' }}>
                            FAC-{{ str_pad($factura->Id_Factura, 6, '0', STR_PAD_LEFT) }} | Cliente: {{ optional(optional($factura->orden)->cliente)->Nombre ?? 'N/A' }} | Saldo: S/ {{ number_format($factura->Saldo ?? $factura->Total, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Fecha</label>
                <input type="date" name="Fecha" value="{{ old('Fecha', \Carbon\Carbon::parse($pago->Fecha)->format('Y-m-d')) }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Monto</label>
                <input type="number" step="0.01" name="Monto" value="{{ old('Monto', $pago->Monto) }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2">Método</label>
                <select name="Metodo" class="w-full border rounded p-2" required>
                    <option value="Efectivo" {{ (old('Metodo', $pago->Metodo) == 'Efectivo') ? 'selected' : '' }}>Efectivo</option>
                    <option value="Tarjeta" {{ (old('Metodo', $pago->Metodo) == 'Tarjeta') ? 'selected' : '' }}>Tarjeta</option>
                    <option value="Transferencia" {{ (old('Metodo', $pago->Metodo) == 'Transferencia') ? 'selected' : '' }}>Transferencia</option>
                    <option value="Cheque" {{ (old('Metodo', $pago->Metodo) == 'Cheque') ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Actualizar</button>
                <a href="{{ route('pagos.index') }}" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Cancelar</a>
            </div>

        </form>
    </div>

</body>
</html>
