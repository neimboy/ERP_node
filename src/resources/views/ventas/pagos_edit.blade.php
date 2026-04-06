<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white">
            <div class="p-4 text-xl font-bold border-b border-gray-700">ERP System</div>
            <nav class="mt-4">
                <a href="{{ route('pagos.index') }}" class="block px-4 py-2 bg-green-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Pagos
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit text-yellow-600 mr-2"></i> Editar Pago #{{ $pago->Id_Pago }}
                </h1>
            </div>

            <div class="m-6 bg-white rounded-lg shadow max-w-2xl mx-auto">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Al modificar este pago, se regenerará automáticamente el asiento contable asociado.
                    </p>
                </div>

                <form action="{{ route('pagos.update', $pago) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Factura (solo lectura) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-file-invoice text-blue-500 mr-1"></i> Factura
                        </label>
                        <select name="Id_Factura" class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
                            <option value="{{ $pago->Id_Factura }}">
                                FAC-{{ str_pad($pago->Id_Factura, 6, '0', STR_PAD_LEFT) }} - 
                                Total: S/ {{ number_format($pago->factura->Total, 2) }}
                            </option>
                        </select>
                        <input type="hidden" name="Id_Factura" value="{{ $pago->Id_Factura }}">
                        <p class="text-xs text-gray-500 mt-1">La factura no se puede modificar</p>
                    </div>

                    <!-- Información de la factura -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-700 mb-2">Información de la Factura</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>Total Factura:</div>
                            <div class="font-mono font-bold">S/ {{ number_format($pago->factura->Total, 2) }}</div>
                            <div>Total pagado (incluyendo este):</div>
                            <div class="font-mono text-blue-600" id="totalPagadoDisplay">
                                S/ {{ number_format($pago->factura->pagos->sum('Monto'), 2) }}
                            </div>
                            <div class="font-bold">Saldo pendiente después del cambio:</div>
                            <div class="font-mono font-bold text-orange-600" id="nuevoSaldo">
                                S/ {{ number_format($pago->factura->Total - $pago->factura->pagos->sum('Monto'), 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> Fecha del Pago *
                        </label>
                        <input type="date" name="Fecha" value="{{ old('Fecha', $pago->Fecha) }}" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                        @error('Fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Monto -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-green-500 mr-1"></i> Monto *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">S/</span>
                            <input type="number" step="0.01" name="Monto" value="{{ old('Monto', $pago->Monto) }}" 
                                   id="montoPago"
                                   class="w-full border rounded-lg pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                                   placeholder="0.00" required>
                        </div>
                        <p id="montoAdvertencia" class="text-xs mt-1 hidden"></p>
                        @error('Monto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Método de Pago -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-blue-500 mr-1"></i> Método de Pago *
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="Metodo" value="Efectivo" class="mr-2" {{ $pago->Metodo == 'Efectivo' ? 'checked' : '' }} required>
                                <i class="fas fa-money-bill text-green-600 mr-2"></i> Efectivo
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="Metodo" value="Tarjeta" class="mr-2" {{ $pago->Metodo == 'Tarjeta' ? 'checked' : '' }}>
                                <i class="fas fa-credit-card text-blue-600 mr-2"></i> Tarjeta
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="Metodo" value="Transferencia" class="mr-2" {{ $pago->Metodo == 'Transferencia' ? 'checked' : '' }}>
                                <i class="fas fa-university text-purple-600 mr-2"></i> Transferencia
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="Metodo" value="Cheque" class="mr-2" {{ $pago->Metodo == 'Cheque' ? 'checked' : '' }}>
                                <i class="fas fa-receipt text-gray-600 mr-2"></i> Cheque
                            </label>
                        </div>
                        @error('Metodo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4 border-t">
                        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition">
                            <i class="fas fa-save mr-2"></i> Actualizar Pago
                        </button>
                        <a href="{{ route('pagos.show', $pago) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const totalFactura = {{ $pago->factura->Total }};
        const otrosPagos = {{ $pago->factura->pagos->sum('Monto') - $pago->Monto }};
        const montoInput = document.getElementById('montoPago');
        const nuevoSaldoSpan = document.getElementById('nuevoSaldo');
        const totalPagadoSpan = document.getElementById('totalPagadoDisplay');
        const advertencia = document.getElementById('montoAdvertencia');

        function updateSaldo() {
            const nuevoMonto = parseFloat(montoInput.value) || 0;
            const nuevoTotalPagado = otrosPagos + nuevoMonto;
            const nuevoSaldo = totalFactura - nuevoTotalPagado;
            
            totalPagadoSpan.textContent = `S/ ${nuevoTotalPagado.toFixed(2)}`;
            nuevoSaldoSpan.textContent = `S/ ${nuevoSaldo.toFixed(2)}`;
            
            if (nuevoMonto > (totalFactura - otrosPagos)) {
                advertencia.textContent = `⚠️ El monto (S/ ${nuevoMonto.toFixed(2)}) excede el saldo permitido. Máximo: S/ ${(totalFactura - otrosPagos).toFixed(2)}`;
                advertencia.classList.add('text-red-600');
                advertencia.classList.remove('hidden');
                return false;
            } else if (nuevoMonto > 0) {
                advertencia.textContent = `✓ Monto válido. Nuevo saldo: S/ ${nuevoSaldo.toFixed(2)}`;
                advertencia.classList.add('text-green-600');
                advertencia.classList.remove('hidden');
                return true;
            }
            advertencia.classList.add('hidden');
            return false;
        }

        montoInput.addEventListener('input', updateSaldo);
        updateSaldo();
    </script>
</body>
</html>