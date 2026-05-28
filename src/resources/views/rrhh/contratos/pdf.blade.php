<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 2.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11pt; line-height: 1.5; color: #333; }
        
        .header { 
            display: table; 
            width: 100%; 
            border-bottom: 3px solid #4338ca; 
            padding-bottom: 15px; 
            margin-bottom: 30px; 
        }
        .header-logo { 
            display: table-cell; 
            width: 30%; 
            vertical-align: middle; 
        }
        .header-logo img { 
            max-width: 150px; 
            height: auto; 
        }
        .header-info { 
            display: table-cell; 
            width: 70%; 
            text-align: right; 
            vertical-align: middle; 
        }
        .company-title { 
            font-size: 18pt; 
            font-weight: bold; 
            color: #4338ca; 
            margin-bottom: 5px; 
        }
        
        .title { text-align: center; font-weight: bold; font-size: 14pt; text-decoration: underline; margin-bottom: 25px; }
        .content { text-align: justify; }
        .clause { margin-bottom: 15px; }
        .clause-title { font-weight: bold; text-transform: uppercase; }
        .signature-table { width: 100%; margin-top: 60px; border-collapse: collapse; }
        .signature-box { width: 40%; text-align: center; vertical-align: top; }
        .line { border-top: 1px solid #000; width: 80%; margin: 0 auto 5px; }
        .footer { position: fixed; bottom: -1cm; width: 100%; text-align: center; font-size: 9pt; color: #777; }
    </style>
</head>
<body>

    {{-- Encabezado con el Logo a la izquierda --}}
    <div class="header">
        <div class="header-logo">
            @if(!empty($logoData))
                <img src="{{ $logoData }}" alt="Logo Yunix">
            @else
                <div style="font-weight: bold; color: #4338ca; font-size: 16pt;">YUNIX</div>
            @endif
        </div>
        <div class="header-info">
            <div class="company-title">Compañia Yunix Ingenieros</div>
            <div style="font-size: 10pt; color: #666;">RUC N.º 20568780681 | Jr. Las Gardenias 123, Lima</div>
        </div>
    </div>

    <div class="title">CONTRATO INDIVIDUAL DE TRABAJO</div>

    <div class="content">
        <div class="clause">
            Conste por el presente documento el Contrato Individual de Trabajo que celebran de una parte 
            <strong>Compañia Yunix Ingenieros </strong>, identificada con N.º 20568780681, con Dirección: Urb.Jesus del norte Mz-A L-05 Carabayllo - Lima, 
            represented por su Gerente General, en adelante el <strong>EMPLEADOR</strong>; y de la otra parte el Sr(a). 
            <strong>{{ $contrato->empleado->Nombre }} {{ $contrato->empleado->Apellido }}</strong>, 
            identificado(a) con DNI N.º __________, nacionalidad __________, de __ años de edad, con domicilio en ____________________, 
            en adelante el <strong>EMPLEADO</strong>; bajo los términos y condiciones siguientes:
        </div>

        <div class="clause">
            <span class="clause-title">Primera (Cargo):</span> El EMPLEADOR contrata los servicios del EMPLEADO para desempeñarse en el cargo de 
            <strong>{{ $contrato->puesto->Nombre_Puesto }}</strong>, realizando las labores propias de dicha posición y demás funciones relacionadas 
            con su cargo que le sean asignadas por el EMPLEADOR.
        </div>

        <div class="clause">
            <span class="clause-title">Segunda (Plazo):</span> El presente contrato es de 
            <strong>{{ $contrato->Fecha_Fin ? 'PLAZO FIJO' : 'PLAZO INDEFINIDO' }}</strong>. 
            El EMPLEADO iniciará sus labores el día <strong>{{ date('d/m/Y', strtotime($contrato->Fecha_Inicio)) }}</strong>
            @if($contrato->Fecha_Fin)
                y concluirá el día <strong>{{ date('d/m/Y', strtotime($contrato->Fecha_Fin)) }}</strong>
            @endif.
        </div>

        <div class="clause">
            <span class="clause-title">Tercera (Período de Prueba):</span> El EMPLEADO estará sujeto a un período de prueba de 
            <strong>tres meses</strong>, conforme a la legislación laboral vigente.
        </div>

        <div class="clause">
            <span class="clause-title">Cuarta (Remuneración):</span> El EMPLEADOR abonará al EMPLEADO la suma mensual de 
            <strong>S/ {{ number_format($contrato->puesto->Salario_Base ?? 1025, 2) }}</strong>, pagaderos de manera mensual mediante depósito bancario, 
            sujeto a los descuentos de ley (AFP/ONP).
        </div>

        <div class="clause">
            <span class="clause-title">Quinta (Jornada Laboral):</span> El EMPLEADO cumplirá una jornada laboral de 
            lunes a viernes de 8:00 a.m. a 6:00 p.m., con una hora de refrigerio, respetando el máximo de horas semanales permitidas por ley.
        </div>

        <div class="clause">
            <span class="clause-title">Sexta (Beneficios):</span> El EMPLEADO gozará de todos los beneficios laborales establecidos por la ley peruana 
            según el régimen laboral correspondiente, tales como vacaciones, gratificaciones y CTS.
        </div>

        <p>Firmado en la ciudad de <strong>Lima</strong>, a los {{ date('d') }} días del mes de {{ date('m') }} del año {{ date('Y') }}.</p>
    </div>

    {{-- Tabla de firmas con el QR incorporado en el centro --}}
    <table class="signature-table">
        <tr>
            <td class="signature-box">
                <div style="height: 80px;"></div>
                <div class="line"></div>
                <strong>RRHH</strong><br>
                <span>Compañia Yunix Ingenieros</span>
            </td>
            
            {{-- CÓDIGO DE VERIFICACIÓN SEGURO (Sustituto del QR 100% Local) --}}
            <td style="width: 30%; text-align: center; vertical-align: bottom; padding-bottom: 10px;">
                <div style="border: 2px dashed #4338ca; padding: 8px; background-color: #f8fafc; rounded: 4px;">
                    <span style="font-size: 8pt; font-weight: bold; color: #4338ca; display: block; margin-bottom: 3px;">SISTEMA DE VERIFICACIÓN</span>
                    <span style="font-size: 7pt; font-family: monospace; color: #334155; display: block;">ID: YUNIX-CTR-{{ $contrato->Id_Contrato }}</span>
                    <span style="font-size: 6pt; font-family: monospace; color: #64748b; display: block; margin-top: 2px;">HASH: {{ strtoupper(substr(md5($contrato->Id_Contrato . $contrato->empleado->Nombre), 0, 12)) }}</span>
                </div>
                <div style="font-size: 7pt; color: #777; margin-top: 5px; text-align: center;">Documento Auténtico</div>
            </td>
            
            <td class="signature-box">
                <div style="height: 80px;"></div>
                <div class="line"></div>
                <strong>EL EMPLEADO</strong><br>
                <span>{{ $contrato->empleado->Nombre }} {{ $contrato->empleado->Apellido }}</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado por el Sistema de Recursos Humanos Yunix - Página 1 de 1
    </div>

</body>
</html>