<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Orden #{{ $orden->Id_Orden ?? $orden->getKey() }}
            </h2>

            <div class="flex items-center space-x-2">
                @if($orden->factura)
                    {{-- Si la orden ya tiene factura, mostramos el botón para verla --}}
                    <a href="{{ route('facturas.show', $orden->factura) }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Ver Factura
                    </a>
                @else
                    {{-- Formulario corregido pasándole directamente el ID puro extraído de la instancia actual --}}
                    <form action="{{ route('ordenes.facturar', ['orden' => $orden->Id_Orden]) }}" method="POST" data-swal-confirm data-swal-message="¿Generar factura para esta orden?">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                            Generar Factura
                        </button>
                    </form>
                @endif

                @if(strtoupper($orden->Estado ?? $orden->estado ?? '') !== 'EJECUTADA')
                    <form action="{{ route('ordenes.confirmarEjecucion', ['orden' => $orden->Id_Orden]) }}" method="POST" data-swal-confirm data-swal-message="¿Confirmar ejecución de esta orden?">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded">
                            Confirmar Ejecución
                        </button>
                    </form>
                @endif

                <a href="{{ route('ordenes.index') }}" class="px-4 py-2 bg-gray-200 rounded">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <div class="mb-4">
                <strong>Cliente:</strong> {{ $orden->cliente->Nombre ?? 'N/A' }}
            </div>

            @if($orden->cotizacion)
                <div class="mb-4">
                    <strong>Cotización origen:</strong>
                    <a href="{{ route('cotizaciones.show', $orden->cotizacion) }}" class="text-indigo-600 hover:underline">
                        #{{ $orden->cotizacion->Id_Cotizacion }}
                    </a>
                </div>
            @endif

            <div class="mb-4">
                <strong>Fecha:</strong> {{ $orden->Fecha }}
            </div>

            <div class="mb-4">
                <strong>Estado:</strong>
                @php
                    $status = $orden->Estado ?? 'N/A';
                    $map = [
                        'Pagada' => 'bg-green-100 text-green-800',
                        'Pagado' => 'bg-green-100 text-green-800',
                        'PENDIENTE' => 'bg-yellow-100 text-yellow-800',
                        'Pendiente' => 'bg-yellow-100 text-yellow-800',
                        'Pendiente_Pago' => 'bg-yellow-100 text-yellow-800',
                        'Facturada' => 'bg-blue-100 text-blue-800',
                    ];
                    $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $cls }}">{{ $status }}</span>
            </div>

            <div class="mt-4">
                <h3 class="font-bold mb-2">Detalle</h3>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-2 text-left">Producto</th>
                            <th class="p-2 text-right">Cantidad</th>
                            <th class="p-2 text-right">Precio Unit.</th>
                            <th class="p-2 text-right">Costo Unit.</th>
                            <th class="p-2 text-right">Descuento</th>
                            <th class="p-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orden->detalles as $d)
                            <tr class="border-t">
                                <td class="p-2">{{ $d->producto->Nombre ?? '—' }}</td>
                                <td class="p-2 text-right">{{ $d->Cantidad }}</td>
                                <td class="p-2 text-right">S/ {{ number_format($d->Precio_Unitario ?? $d->Precio ?? 0, 2) }}</td>
                                <td class="p-2 text-right">S/ {{ number_format($d->Costo_Unitario ?? 0, 2) }}</td>
                                <td class="p-2 text-right">{{ number_format($d->Descuento ?? 0, 2) }}%</td>
                                <td class="p-2 text-right">S/ {{ number_format($d->Total ?? (($d->Precio_Unitario ?? $d->Precio ?? 0) * $d->Cantidad), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orden->cotizacion)
                <div class="mt-6 bg-white shadow rounded p-4">
                    <h3 class="font-bold mb-2">Desglose Financiero (Basado en Cotización #{{ $orden->cotizacion->Id_Cotizacion }})</h3>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td class="p-2">Costos Directos</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Costos_Directos ?? $orden->cotizacion->costos_directos ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">Gastos Generales (6%)</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Gastos_Generales ?? $orden->cotizacion->gastos_generales ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">Utilidad (10%)</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Utilidad ?? $orden->cotizacion->utilidad ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">Subtotal</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Subtotal ?? $orden->cotizacion->subtotal ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">IGV (18%)</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Impuesto ?? $orden->cotizacion->IGV ?? $orden->cotizacion->igv ?? 0, 2) }}</td>
                            </tr>
                            <tr class="border-t font-bold">
                                <td class="p-2">PRESUPUESTO TOTAL</td>
                                <td class="p-2 text-right">S/ {{ number_format($orden->cotizacion->Total ?? $orden->cotizacion->total ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6">
                    <div class="text-yellow-600">No hay cotización asociada a esta orden.</div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-swal-confirm]').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    var msg = form.dataset.swalMessage || '¿Confirmar?';
                    Swal.fire({
                        title: 'Confirmar',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar'
                    }).then(function(result){
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
