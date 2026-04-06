<!DOCTYPE html>
<html>
<head>
    <title>Crear Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        
        <h1 class="text-2xl font-bold mb-6">Registrar Nuevo Pago</h1>

        <!-- 🔴 ERRORES -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO -->
        <form action="{{ route('pagos.store') }}" method="POST">
            @csrf

            <!-- FACTURA -->
            <div class="mb-4">
                <label class="block font-bold mb-2">Factura</label>
                <select name="Id_Factura" class="w-full border rounded p-2" required>
                    <option value="">Seleccione una factura</option>

                    @foreach($facturas as $factura)
                        <option value="{{ $factura->Id_Factura }}" 
                            {{ old('Id_Factura') == $factura->Id_Factura ? 'selected' : '' }}>
                            
                            FAC-{{ str_pad($factura->Id_Factura, 6, '0', STR_PAD_LEFT) }} 
                            | Cliente: {{ $factura->cliente->Nombre ?? 'N/A' }} 
                            | Total: S/ {{ number_format($factura->Total, 2) }} 
                            | Saldo: S/ {{ number_format($factura->Saldo, 2) }}

                        </option>
                    @endforeach

                </select>
            </div>

            <!-- FECHA -->
            <div class="mb-4">
                <label class="block font-bold mb-2">Fecha</label>
                <input 
                    type="date" 
                    name="Fecha" 
                    value="{{ old('Fecha', date('Y-m-d')) }}" 
                    class="w-full border rounded p-2" 
                    required>
            </div>

            <!-- MONTO -->
            <div class="mb-4">
                <label class="block font-bold mb-2">Monto</label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="Monto" 
                    value="{{ old('Monto') }}" 
                    class="w-full border rounded p-2" 
                    placeholder="Ingrese el monto" 
                    required>
            </div>

            <!-- MÉTODO -->
            <div class="mb-6">
                <label class="block font-bold mb-2">Método</label>
                <select name="Metodo" class="w-full border rounded p-2" required>
                    <option value="Efectivo" {{ old('Metodo') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="Tarjeta" {{ old('Metodo') == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="Transferencia" {{ old('Metodo') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="Cheque" {{ old('Metodo') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>

            <!-- BOTONES -->
            <div class="flex gap-2">
                <button 
                    type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Guardar
                </button>

                <a 
                    href="{{ route('pagos.index') }}" 
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
                    Cancelar
                </a>
            </div>

        </form>
    </div>

</body>
</html>