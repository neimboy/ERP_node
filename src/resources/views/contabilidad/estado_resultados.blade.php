<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Resultados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-4 text-blue-700">
        Estado de Resultados
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

    <!-- INGRESOS -->
    <h2 class="text-lg font-semibold text-green-700 mb-2">Ingresos</h2>
    <table class="w-full mb-4">
        @foreach($ingresos as $cuenta)
            @php
                $monto = $cuenta->detalles->sum('Haber');
            @endphp

            @if($monto > 0)
            <tr>
                <td>{{ $cuenta->Nombre_Cuenta }}</td>
                <td class="text-right text-green-600">
                    {{ number_format($monto, 2) }}
                </td>
            </tr>
            @endif
        @endforeach

        <tr class="font-bold border-t">
            <td>Total Ingresos</td>
            <td class="text-right">{{ number_format($totalIngresos, 2) }}</td>
        </tr>
    </table>

    <!-- GASTOS -->
    <h2 class="text-lg font-semibold text-red-700 mb-2">Gastos</h2>
    <table class="w-full mb-4">
        @foreach($gastos as $cuenta)
            @php
                $monto = $cuenta->detalles->sum('Debe');
            @endphp

            @if($monto > 0)
            <tr>
                <td>{{ $cuenta->Nombre_Cuenta }}</td>
                <td class="text-right text-red-600">
                    {{ number_format($monto, 2) }}
                </td>
            </tr>
            @endif
        @endforeach

        <tr class="font-bold border-t">
            <td>Total Gastos</td>
            <td class="text-right">{{ number_format($totalGastos, 2) }}</td>
        </tr>
    </table>

    <!-- RESULTADO FINAL -->
    <div class="text-xl font-bold mt-6 border-t pt-4">
        Resultado del Ejercicio:
        <span class="{{ $utilidad >= 0 ? 'text-green-700' : 'text-red-700' }}">
            {{ number_format($utilidad, 2) }}
        </span>
    </div>

</div>

</body>
</html>