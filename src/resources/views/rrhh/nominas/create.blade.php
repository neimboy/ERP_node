<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Nómina</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <body class="bg-gray-100">

    <div class="min-h-screen p-8">
        <div class="max-w-4xl mx-auto">
            
            {{-- Encabezado Estilo Panel --}}
            <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-600">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Generar Nueva Nómina</h1>
                    <p class="text-gray-500 font-medium">Registro oficial de pagos - RRHH</p>
                </div>
                <a href="{{ route('rrhh.nominas.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-blue-500 text-white text-sm font-bold rounded-md transition-all">
                    Volver al Historial
                </a>
            </div>

            {{-- Formulario Blanco --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('rrhh.nominas.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Empleado --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Seleccionar Empleado</label>
                                <select name="Id_Empleado" class="w-full border-2 border-gray-200 p-3 rounded-lg focus:border-indigo-500 focus:outline-none bg-gray-50" required>
                                    <option value="">-- Seleccione un empleado --</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->Id_Empleado }}">
                                            {{ $empleado->Nombre }} {{ $empleado->Apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Periodo --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Periodo de Pago</label>
                                <select name="Id_Periodo" class="w-full border-2 border-gray-200 p-3 rounded-lg focus:border-indigo-500 focus:outline-none bg-gray-50" required>
                                    @foreach($periodos as $periodo)
                                        <option value="{{ $periodo->Id_Periodo }}">
                                            {{ $periodo->Mes }} - {{ $periodo->Año }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-8"></div>

                        {{-- Cálculos --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <label class="block text-xs font-bold text-blue-700 mb-1 uppercase">Sueldo Bruto ($)</label>
                                <input type="number" name="Total_Bruto" step="0.01" class="w-full bg-transparent text-xl font-bold focus:outline-none" placeholder="0.00" required>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <label class="block text-xs font-bold text-red-700 mb-1 uppercase">Deducciones ($)</label>
                                <input type="number" name="Total_Deducciones" step="0.01" class="w-full bg-transparent text-xl font-bold focus:outline-none" placeholder="0.00" required>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <label class="block text-xs font-bold text-green-700 mb-1 uppercase">Neto a Pagar ($)</label>
                                <input type="number" name="Neto_Pagar" step="0.01" class="w-full bg-transparent text-xl font-bold focus:outline-none" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-lg shadow-lg transform transition hover:-translate-y-1">
                                GUARDAR REGISTRO DE NÓMINA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brutoInput = document.querySelector('input[name="Total_Bruto"]');
            const deduccionesInput = document.querySelector('input[name="Total_Deducciones"]');
            const netoInput = document.querySelector('input[name="Neto_Pagar"]');

            brutoInput.addEventListener('input', function() {
                let bruto = parseFloat(this.value) || 0;
                
                // Calculamos el 13% de deducciones
                let deducciones = bruto * 0.13;
                let neto = bruto - deducciones;

                // Asignamos los valores a los inputs (con 2 decimales)
                deduccionesInput.value = deducciones.toFixed(2);
                netoInput.value = neto.toFixed(2);
            });
        });
        </script>

    </body>
</html>