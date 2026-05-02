@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Encabezado estilizado --}}
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-600">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Estructura de Puestos</h1>
                <p class="text-gray-500 font-medium">Gestiona los cargos y salarios base de la organización</p>
            </div>
            <a href="{{ route('rrhh.puestos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-md shadow-sm transition-all transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nuevo Puesto
            </a>
        </div>

        {{-- Tabla de Puestos --}}
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider italic">Código</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre del Cargo</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Salario Base Mensual</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($puestos as $puesto)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-400">
                                #{{ str_pad($puesto->Id_Puesto, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $puesto->Nombre_Puesto }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold border border-green-200">
                                    ${{ number_format($puesto->Salario_Base, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('rrhh.puestos.edit', $puesto->Id_Puesto) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('rrhh.puestos.destroy', $puesto->Id_Puesto) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este puesto? Los empleados vinculados podrían quedar huérfanos.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-gray-400 text-lg">No hay cargos definidos. Empieza creando uno nuevo.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buscamos todas las alertas de éxito
        const alerts = document.querySelectorAll('.bg-green-50, .alert-success');
        
        alerts.forEach(alert => {
            setTimeout(() => {
                // Le damos un efecto de desvanecimiento suave
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                
                // Después de la animación, lo eliminamos del diseño
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000); // 5000 milisegundos = 5 segundos
        });
    });
</script>