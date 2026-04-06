<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro Mayor - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">Libro Mayor</h1>

    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" action="{{ route('contabilidad.libro_mayor') }}" class="flex gap-4 items-center">
            
            <label class="font-semibold text-gray-700">Periodo:</label>

            <select name="Id_Periodo" onchange="this.form.submit()" class="border p-2 rounded bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">-- Todos los periodos --</option>
                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->Id_Periodo }}" 
                        {{ $periodoSeleccionado == $periodo->Id_Periodo ? 'selected' : '' }}>
                        {{ $periodo->Año }} - {{ str_pad($periodo->Mes, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                Filtrar
            </button>
        </form>
    </div>

    @foreach($cuentas as $cuenta)
        @php
            $saldo = 0;
        @endphp

        <div class="bg-white shadow-md rounded-lg mb-8 overflow-hidden">
            
            <div class="bg-gray-800 text-white p-4 flex justify-between items-center">
                <div>
                    <span class="text-lg font-bold">{{ $cuenta->Codigo }}</span>
                    <span class="mx-2">-</span>
                    <span class="text-lg">{{ $cuenta->Nombre_Cuenta }}</span>
                </div>
                <span class="text-sm bg-gray-700 px-3 py-1 rounded text-gray-300">
                    {{ $cuenta->Tipo }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-3 text-left">Fecha</th>
                            <th class="p-3 text-left">Glosa</th>
                            <th class="p-3 text-right">Debe</th>
                            <th class="p-3 text-right">Haber</th>
                            <th class="p-3 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuenta->detalles as $detalle)
                            @php
                                $saldo += $detalle->Debe - $detalle->Haber;
                            @endphp
                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                <td class="p-3 whitespace-nowrap">
                                    {{ $detalle->asiento->Fecha }}
                                </td>
                                <td class="p-3">
                                    {{ $detalle->asiento->Glosa }}
                                </td>
                                <td class="p-3 text-right text-green-600 font-medium">
                                    {{ $detalle->Debe > 0 ? number_format($detalle->Debe, 2) : '-' }}
                                </td>
                                <td class="p-3 text-right text-red-600 font-medium">
                                    {{ $detalle->Haber > 0 ? number_format($detalle->Haber, 2) : '-' }}
                                </td>
                                <td class="p-3 text-right font-bold text-gray-800">
                                    {{ number_format($saldo, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500 italic">
                                    No se encontraron movimientos en esta cuenta para el periodo seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if($cuenta->detalles->count() > 0)
                    <tfoot>
                        <tr class="bg-yellow-50 font-bold border-t-2 border-yellow-200">
                            <td colspan="4" class="p-3 text-right text-gray-700">Saldo Final de la Cuenta:</td>
                            <td class="p-3 text-right text-blue-700 text-base">
                                {{ number_format($saldo, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endforeach

</div>

</body>
</html>