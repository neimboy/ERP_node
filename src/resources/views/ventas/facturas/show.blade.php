<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Factura #{{ $factura->Id_Factura }}</h2>
            <div class="space-x-2">
                <a href="{{ route('facturas.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
                <a href="{{ route('pagos.create', ['Id_Factura' => $factura->Id_Factura]) }}" class="px-3 py-2 bg-green-600 text-white rounded">Registrar Pago</a>
                <button onclick="window.print()" class="px-3 py-2 bg-blue-600 text-white rounded">Imprimir</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow print:p-0">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">ERP System</h1>
                    <div class="text-sm text-gray-600">RUC: 000000000</div>
                    <div class="text-sm text-gray-600">Dirección ficticia</div>
                </div>

                <div class="text-right">
                    <div class="text-sm">Factura #: <strong>{{ $factura->Id_Factura }}</strong></div>
                    <div class="text-sm">Fecha: <strong>{{ $factura->Fecha }}</strong></div>
                    <div class="text-sm">Estado Pago: <strong>{{ $factura->Estado_Pago }}</strong></div>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-bold">Cliente</h3>
                <div>{{ $factura->orden->cliente->Nombre ?? '—' }}</div>
                <div class="text-sm text-gray-600">{{ $factura->orden->cliente->Correo ?? '' }}</div>
            </div>

            <div>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50"><th class="p-2 text-left">Producto</th><th class="p-2 text-right">Cantidad</th><th class="p-2 text-right">Precio</th><th class="p-2 text-right">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($factura->detalles as $d)
                            <tr class="border-t">
                                <td class="p-2">{{ $d->producto->Nombre ?? '—' }}</td>
                                <td class="p-2 text-right">{{ $d->Cantidad }}</td>
                                <td class="p-2 text-right">S/ {{ number_format($d->Precio_Unitario,2) }}</td>
                                <td class="p-2 text-right">S/ {{ number_format($d->Total,2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t">
                            <td colspan="3" class="p-2 text-right">Subtotal</td>
                            <td class="p-2 text-right">S/ {{ number_format($factura->Subtotal ?? 0,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="p-2 text-right">IGV (18%)</td>
                            <td class="p-2 text-right">S/ {{ number_format($factura->IGV ?? 0,2) }}</td>
                        </tr>
                        <tr class="border-t">
                            <td colspan="3" class="p-2 text-right font-bold">Total</td>
                            <td class="p-2 text-right font-bold">S/ {{ number_format($factura->Total ?? 0,2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
