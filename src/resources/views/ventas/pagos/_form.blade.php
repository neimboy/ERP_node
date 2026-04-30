<div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Factura</label>
        <select name="Id_Factura" class="w-full border rounded px-3 py-2" required>
            <option value="">-- Seleccionar factura --</option>
            @foreach($facturas ?? [] as $factura)
                <option value="{{ $factura->Id_Factura }}" {{ (old('Id_Factura', $pago->Id_Factura ?? '') == $factura->Id_Factura) ? 'selected' : '' }}>
                    {{ $factura->Numero_Factura }} — {{ $factura->orden->cliente->Nombre ?? 'Sin cliente' }} — Saldo: {{ number_format($factura->Saldo ?? ($factura->Total - ($factura->pagos->sum('Monto') ?? 0)), 2) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
        <input type="number" step="0.01" name="Monto" value="{{ old('Monto', $pago->Monto ?? '') }}" class="w-full border rounded px-3 py-2" required />
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
        <input type="date" name="Fecha" value="{{ old('Fecha', isset($pago->Fecha) ? $pago->Fecha->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2" />
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Método</label>
        <input type="text" name="Metodo" value="{{ old('Metodo', $pago->Metodo ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="E.g. Transferencia, Efectivo" />
    </div>
</div>
