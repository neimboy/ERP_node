<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance General</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-4 text-blue-700">
        Balance General
    </h1>

    <!-- FILTRO -->
    <form method="GET" class="mb-6">
        <select name="Id_Periodo" onchange="this.form.submit()" class="border p-2 rounded">
            <option value="">Todos</option>

            @foreach($periodos as $p)
                <option value="{{ $p->Id_Periodo }}"
                    {{ $periodoSeleccionado == $p->Id_Periodo ? 'selected' : '' }}>
                    
                    {{ $p->Año }} - {{ $p->Mes }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-2 gap-6">

        <!-- ACTIVOS -->
        <div>
            <h2 class="text-lg font-semibold text-green-700 mb-2">Activos</h2>

            @foreach($activos as $cuenta)
                @php
                    $saldo = $cuenta->detalles->sum('Debe') - $cuenta->detalles->sum('Haber');
                @endphp

                @if($saldo != 0)
                <div class="flex justify-between border-b py-1">
                    <span>{{ $cuenta->Nombre_Cuenta }}</span>
                    <span>{{ number_format($saldo, 2) }}</span>
                </div>
                @endif
            @endforeach

            <div class="font-bold mt-3 border-t pt-2">
                Total Activos: {{ number_format($totalActivos, 2) }}
            </div>
        </div>

        <!-- PASIVO + PATRIMONIO -->
        <div>
            <h2 class="text-lg font-semibold text-red-700 mb-2">Pasivos</h2>

            @foreach($pasivos as $cuenta)
                @php
                    $saldo = $cuenta->detalles->sum('Haber') - $cuenta->detalles->sum('Debe');
                @endphp

                @if($saldo != 0)
                <div class="flex justify-between border-b py-1">
                    <span>{{ $cuenta->Nombre_Cuenta }}</span>
                    <span>{{ number_format($saldo, 2) }}</span>
                </div>
                @endif
            @endforeach

            <div class="font-bold mt-3 border-t pt-2">
                Total Pasivos: {{ number_format($totalPasivos, 2) }}
            </div>

            <h2 class="text-lg font-semibold text-purple-700 mt-4 mb-2">Patrimonio</h2>

            @foreach($patrimonio as $cuenta)
                @php
                    $saldo = $cuenta->detalles->sum('Haber') - $cuenta->detalles->sum('Debe');
                @endphp

                @if($saldo != 0)
                <div class="flex justify-between border-b py-1">
                    <span>{{ $cuenta->Nombre_Cuenta }}</span>
                    <span>{{ number_format($saldo, 2) }}</span>
                </div>
                @endif
            @endforeach

            <div class="font-bold mt-3 border-t pt-2">
                Total Patrimonio: {{ number_format($totalPatrimonio, 2) }}
            </div>

            <!-- VALIDACIÓN -->
            <div class="mt-4 text-xl font-bold">
                Pasivo + Patrimonio:
                {{ number_format($totalPasivos + $totalPatrimonio, 2) }}
            </div>
        </div>

    </div>

</div>

</body>
</html>