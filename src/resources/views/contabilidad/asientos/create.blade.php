@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📝</span> Registrar Asiento Diario Contable
            </h2>
            <p class="subtitle">Registro del Libro Diario — Partida Doble</p>
        </div>
        <a href="{{ route('asientos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>

    {{-- FORMULARIO PRINCIPAL --}}
    <div class="card animate-fade-in">
        
        {{-- Cabecera --}}
        <div class="bg-blue-600 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
            <span class="text-xl">📝</span>
            <div>
                <span class="font-semibold">Nuevo Asiento Contable</span>
                <span class="block text-blue-100 text-xs mt-0.5">Complete los datos del asiento y sus líneas de detalle</span>
            </div>
        </div>

        {{-- Cuerpo --}}
        <div class="p-6">
            
            {{-- Error de partida doble --}}
            @if($errors->has('partida_doble'))
                <div class="alert alert-danger mb-5 animate-fade-in">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-balance-scale-right text-lg mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Error de Partida Doble</p>
                            <p class="text-sm">{{ $errors->first('partida_doble') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Errores generales --}}
            @if($errors->any() && !$errors->has('partida_doble'))
                <div class="alert alert-danger mb-5 animate-fade-in">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-sm mb-1">Corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside text-sm space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('asientos.store') }}" method="POST" id="asientoForm">
                @csrf

                {{-- DATOS GENERALES --}}
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <h3 class="section-title text-base mb-4">
                        <span class="w-1 h-5 bg-blue-500 rounded-full mr-2.5"></span>
                        Datos Generales del Asiento
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Período Contable --}}
                        <div>
                            <label for="Id_Periodo" class="form-label">
                                Período Contable <span class="text-red-500">*</span>
                            </label>
                            <select name="Id_Periodo" id="Id_Periodo" class="form-select" required>
                                @foreach($periodos as $p)
                                    <option value="{{ $p->Id_Periodo }}" {{ old('Id_Periodo') == $p->Id_Periodo ? 'selected' : '' }}>
                                        {{ $p->label ?? ($p->Año . ' - Mes ' . str_pad($p->Mes, 2, '0', STR_PAD_LEFT)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha --}}
                        <div>
                            <label for="Fecha" class="form-label">
                                Fecha de Asiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="Fecha" 
                                   id="Fecha"
                                   class="form-input"
                                   value="{{ old('Fecha', date('Y-m-d')) }}" 
                                   required>
                        </div>

                        {{-- Glosa --}}
                        <div>
                            <label for="Glosa" class="form-label">
                                Glosa / Descripción <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="Glosa" 
                                   id="Glosa"
                                   class="form-input"
                                   placeholder="Ej: Por la provisión de la factura N° 001-..." 
                                   value="{{ old('Glosa') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                {{-- LÍNEAS DE DETALLE --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="section-title text-base">
                            <span class="w-1 h-5 bg-blue-500 rounded-full mr-2.5"></span>
                            Líneas Contables
                        </h3>
                        <span class="badge badge-info text-xs">
                            <i class="fas fa-info-circle mr-1"></i> Mínimo 2 líneas
                        </span>
                    </div>

                    {{-- Tabla de detalle --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="table" id="detalleTable">
                            <thead>
                                <tr>
                                    <th class="w-2/5 pl-4">Cuenta Contable</th>
                                    <th class="w-1/5 text-right">Debe (S/.)</th>
                                    <th class="w-1/5 text-right">Haber (S/.)</th>
                                    <th class="w-16 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="detalle-linea group">
                                    <td class="pl-4">
                                        <select name="detalles[0][Id_Cuenta]" class="form-select text-sm" required>
                                            <option value="">— Seleccionar cuenta —</option>
                                            @foreach($cuentas as $c)
                                                <option value="{{ $c->Id_Cuenta }}">
                                                    {{ $c->Codigo }} - {{ $c->Nombre_Cuenta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">S/.</span>
                                            <input type="number" 
                                                   name="detalles[0][Debe]" 
                                                   class="form-input text-right font-mono pl-10 debe-input"
                                                   value="0.00" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">S/.</span>
                                            <input type="number" 
                                                   name="detalles[0][Haber]" 
                                                   class="form-input text-right font-mono pl-10 haber-input"
                                                   value="0.00" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-ghost btn-sm text-gray-400 hover:text-red-500 remove-row"
                                                title="Eliminar línea">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Botón añadir línea --}}
                    <button type="button" 
                            id="btnAnadirLinea"
                            class="mt-3 btn btn-ghost text-blue-600 hover:bg-blue-50 text-sm">
                        <i class="fas fa-plus-circle mr-1.5"></i> Añadir Línea Contable
                    </button>
                </div>

                {{-- RESUMEN DE TOTALES --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="text-center sm:text-left">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Debe</span>
                            <div class="text-xl font-bold text-emerald-600 font-mono" id="txtTotalDebe">0.00</div>
                        </div>
                        <div class="text-center">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Haber</span>
                            <div class="text-xl font-bold text-red-500 font-mono" id="txtTotalHaber">0.00</div>
                        </div>
                        <div class="text-center sm:text-right">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Diferencia</span>
                            <div class="text-xl font-bold font-mono" id="txtDiferencia">
                                <span id="diferenciaValor">0.00</span>
                                <span id="diferenciaIcono" class="text-emerald-500">✅</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-5 border-t border-gray-200">
                    <a href="{{ route('asientos.index') }}" class="btn btn-secondary w-full sm:w-auto text-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit" 
                            class="btn btn-success w-full sm:w-auto"
                            id="btnGuardar">
                        <i class="fas fa-save mr-2"></i> Guardar Asiento Contable
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let index = 1;
    const tbody = document.querySelector('#detalleTable tbody');
    
    // Añadir nueva línea
    document.getElementById('btnAnadirLinea').addEventListener('click', function() {
        const row = tbody.querySelector('tr').cloneNode(true);
        
        // Actualizar índices
        row.querySelectorAll('select, input').forEach(input => {
            let name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            if (input.tagName === 'INPUT') input.value = "0.00";
            if (input.tagName === 'SELECT') input.selectedIndex = 0;
        });
        
        tbody.appendChild(row);
        index++;
        calcularTotales();
        
        // Scroll suave a la nueva línea
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    // Eliminar línea
    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            if (tbody.rows.length > 2) {
                e.target.closest('tr').remove();
                calcularTotales();
            } else {
                // Mostrar aviso sutil si intenta eliminar la última
                alert('El asiento debe tener al menos 2 líneas contables.');
            }
        }
    });

    // Calcular totales al escribir
    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('debe-input') || e.target.classList.contains('haber-input')) {
            // Si el usuario escribe en Debe, limpiar Haber (y viceversa)
            const row = e.target.closest('tr');
            const debeInput = row.querySelector('.debe-input');
            const haberInput = row.querySelector('.haber-input');
            
            if (e.target.classList.contains('debe-input') && parseFloat(e.target.value) > 0) {
                haberInput.value = "0.00";
            }
            if (e.target.classList.contains('haber-input') && parseFloat(e.target.value) > 0) {
                debeInput.value = "0.00";
            }
            
            calcularTotales();
        }
    });

    // Validación antes de enviar
    document.getElementById('asientoForm').addEventListener('submit', function(e) {
        const totalDebe = parseFloat(document.getElementById('txtTotalDebe').innerText);
        const totalHaber = parseFloat(document.getElementById('txtTotalHaber').innerText);
        const diferencia = Math.abs(totalDebe - totalHaber);
        
        if (diferencia > 0.01) {
            e.preventDefault();
            alert('⚠️ El asiento no cuadra. La diferencia entre Debe y Haber es S/. ' + diferencia.toFixed(2) + '\n\nVerifique los montos antes de guardar.');
            return false;
        }
        
        if (totalDebe === 0 && totalHaber === 0) {
            e.preventDefault();
            alert('⚠️ El asiento no puede tener todos los montos en cero.');
            return false;
        }
        
        // Verificar que haya al menos 2 líneas
        if (tbody.rows.length < 2) {
            e.preventDefault();
            alert('⚠️ El asiento debe tener al menos 2 líneas contables.');
            return false;
        }
    });

    function calcularTotales() {
        let db = 0, hb = 0;
        document.querySelectorAll('.debe-input').forEach(i => db += parseFloat(i.value || 0));
        document.querySelectorAll('.haber-input').forEach(i => hb += parseFloat(i.value || 0));
        
        document.getElementById('txtTotalDebe').innerText = db.toFixed(2);
        document.getElementById('txtTotalHaber').innerText = hb.toFixed(2);
        
        const diferencia = Math.abs(db - hb);
        const difElement = document.getElementById('diferenciaValor');
        const iconElement = document.getElementById('diferenciaIcono');
        const btnGuardar = document.getElementById('btnGuardar');
        
        difElement.innerText = diferencia.toFixed(2);
        
        if (diferencia <= 0.01 && (db > 0 || hb > 0)) {
            difElement.className = 'text-emerald-600';
            iconElement.className = 'text-emerald-500';
            iconElement.innerText = '✅';
            btnGuardar.disabled = false;
            btnGuardar.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            difElement.className = 'text-red-500';
            iconElement.className = 'text-red-500';
            iconElement.innerText = '⚠️';
        }
    }
    
    // Calcular al cargar
    calcularTotales();
});
</script>
@endsection