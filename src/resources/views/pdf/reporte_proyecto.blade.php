<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Proyecto</title>
    <style>
        @page { margin: 12mm 12mm 30mm 12mm; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .container {
            width: 100%;
        }

        /* ── HEADER ── */
        .header {
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 2px solid #1f3c88;
            margin-bottom: 12px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f3c88;
            margin-bottom: 2px;
        }

        .header-subtitle {
            font-size: 9px;
            color: #7f8c8d;
        }

        .header-logo img {
            width: 70px;
            height: auto;
        }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1f3c88;
            margin-top: 14px;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #dcdcdc;
        }

        /* ── INFO BOX ── */
        .info-box {
            border: 1px solid #dcdcdc;
            background: #f8f9fa;
            padding: 10px 12px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 2px 6px;
            font-size: 10px;
            vertical-align: top;
        }

        .info-box .label {
            font-weight: bold;
            color: #1f3c88;
            width: 100px;
        }

        /* ── CARDS ── */
        .cards {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .card {
            border: 1px solid #dfe6e9;
            border-radius: 4px;
            padding: 8px;
            background: #f8f9fa;
            text-align: center;
        }

        .card-title {
            font-size: 9px;
            color: #7f8c8d;
            margin-bottom: 3px;
        }

        .card-value {
            font-size: 16px;
            font-weight: bold;
            color: #1f3c88;
        }

        /* ── TABLE ── */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .table thead {
            background: #1f3c88;
            color: white;
        }

        .table th {
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        .table td {
            padding: 5px 10px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 10px;
        }

        .table tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .table .total-row td {
            font-weight: bold;
            background: #eef2ff;
            border-top: 2px solid #1f3c88;
        }

        /* ── BADGES ── */
        .badge {
            padding: 3px 9px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            color: white;
        }

        .badge-produccion { background: #4a69bd; }
        .badge-servicio   { background: #27ae60; }
        .badge-completado { background: #27ae60; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            padding-top: 12px;
            border-top: 1px solid #dfe4ea;
        }

        .footer img {
            width: 75px;
            height: auto;
            margin-bottom: 4px;
        }

        .footer .validez {
            font-size: 9px;
        }

        /* ── HELPERS ── */
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-muted  { color: #7f8c8d; }
    </style>
</head>
<body>

<div class="container">

    {{-- ════════════════════════════════════════════════
         HEADER CORPORATIVO
         ════════════════════════════════════════════════ --}}
    <table class="header" cellspacing="0" cellpadding="0">
        <tr>
            <td width="70%" valign="middle">
                <div class="header-title">Reporte de Proyecto</div>
                <div class="header-subtitle">Sistema de Gestión Empresarial</div>
            </td>
            <td width="30%" align="right" valign="middle" class="header-logo">
                <img src="{{ public_path('img/erpnode.jpeg') }}" alt="Logo">
            </td>
        </tr>
    </table>

    {{-- ════════════════════════════════════════════════
         INFORMACIÓN GENERAL
         ════════════════════════════════════════════════ --}}
    <div class="section-title">Información del Proyecto</div>

    <div class="info-box">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td class="label">Nombre</td>
                <td width="35%">{{ $proyecto->Nombre }}</td>
                <td class="label">Tipo</td>
                <td width="25%">
                    <span class="badge {{ $proyecto->Tipo === 'produccion' ? 'badge-produccion' : 'badge-servicio' }}">
                        {{ $proyecto->Tipo === 'produccion' ? 'Producción' : 'Servicio' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Estado</td>
                <td><span class="badge badge-completado">{{ $proyecto->Estado }}</span></td>
                <td class="label">Cliente</td>
                <td>{{ $proyecto->cliente->Nombre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Fecha Inicio</td>
                <td>{{ $proyecto->Fecha_Inicio ? \Carbon\Carbon::parse($proyecto->Fecha_Inicio)->format('d/m/Y') : 'N/A' }}</td>
                <td class="label">Fecha Fin</td>
                <td>{{ $proyecto->Fecha_Fin ? \Carbon\Carbon::parse($proyecto->Fecha_Fin)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    {{-- ════════════════════════════════════════════════
         CARDS RESUMEN
         ════════════════════════════════════════════════ --}}
    @php
        $totalHoras = $proyecto->asignaciones->sum('Horas_Asignadas');
        $numEmpleados = $proyecto->asignaciones->count();

        if ($proyecto->Tipo === 'produccion') {
            $totalCantidad = $proyecto->productos->sum('pivot.Cantidad');
            $gastoTotal = $proyecto->productos->sum(function($p) {
                return $p->pivot->Cantidad * ($p->Precio_Venta ?? 0);
            });
        } else {
            $totalCantidad = $proyecto->gastos->sum('Monto');
            $gastoTotal = $totalCantidad;
        }
    @endphp

    <div class="cards">
        <table width="100%" cellspacing="8" cellpadding="0">
            <tr>
                <td width="33%" valign="top">
                    <div class="card">
                        <div class="card-title">Horas Asignadas</div>
                        <div class="card-value">{{ $totalHoras }}</div>
                    </div>
                </td>
                <td width="33%" valign="top">
                    <div class="card">
                        <div class="card-title">Empleados</div>
                        <div class="card-value">{{ $numEmpleados }}</div>
                    </div>
                </td>
                <td width="33%" valign="top">
                    <div class="card">
                        <div class="card-title">Gasto Total</div>
                        <div class="card-value">S/ {{ number_format($gastoTotal, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ════════════════════════════════════════════════
         PRODUCTOS (solo producción)
         ════════════════════════════════════════════════ --}}
    @if ($proyecto->Tipo === 'produccion' && $proyecto->productos->isNotEmpty())
        <div class="section-title">Productos Utilizados</div>
        <table class="table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="55">Código</th>
                    <th>Producto</th>
                    <th width="60" class="text-center">Cant.</th>
                    <th width="80" class="text-right">P. Unit.</th>
                    <th width="90" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyecto->productos as $producto)
                @php $subtotal = $producto->pivot->Cantidad * ($producto->Precio_Venta ?? 0); @endphp
                <tr>
                    <td>{{ $producto->Codigo ?? '—' }}</td>
                    <td>{{ $producto->Nombre }}</td>
                    <td class="text-center">{{ $producto->pivot->Cantidad }}</td>
                    <td class="text-right">S/ {{ number_format($producto->Precio_Venta ?? 0, 2) }}</td>
                    <td class="text-right">S/ {{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Totales</td>
                    <td class="text-center">{{ $totalCantidad }}</td>
                    <td></td>
                    <td class="text-right">S/ {{ number_format($gastoTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════
         GASTOS (solo servicio)
         ════════════════════════════════════════════════ --}}
    @if ($proyecto->Tipo === 'servicio' && $proyecto->gastos->isNotEmpty())
        <div class="section-title">Gastos del Servicio</div>
        <table class="table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Descripción</th>
                    <th width="110" class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyecto->gastos as $i => $gasto)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $gasto->Descripcion }}</td>
                    <td class="text-right">S/ {{ number_format($gasto->Monto, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total gastos</td>
                    <td class="text-right">S/ {{ number_format($gastoTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════
         EMPLEADOS ASIGNADOS
         ════════════════════════════════════════════════ --}}
    @if ($proyecto->asignaciones->isNotEmpty())
        <div class="section-title">Empleados Asignados</div>
        <table class="table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th width="120" class="text-center">Horas Asignadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyecto->asignaciones as $asignacion)
                <tr>
                    <td>{{ $asignacion->empleado->Nombre ?? 'N/A' }}</td>
                    <td class="text-center">{{ $asignacion->Horas_Asignadas ?? 0 }} hrs</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total horas</td>
                    <td class="text-center">{{ $totalHoras }} hrs</td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════
         FOOTER — Firma digital
         ════════════════════════════════════════════════ --}}
    <div class="footer">
        <img src="{{ public_path('img/firma.jpg') }}" alt="Firma digital">
        <div class="validez">
            Documento emitido por COMPAÑÍA YUNIX INGENIEROS &mdash; Folio {{ $folio }}
        </div>
    </div>

</div>

</body>
</html>
