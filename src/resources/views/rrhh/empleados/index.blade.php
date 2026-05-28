@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Recursos Humanos</h1>
                <p class="text-gray-500">Gestión de empleados registrados</p>
            </div>
            <a href="{{ route('rrhh.empleados.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Empleado
            </a>
        </div>

        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            {{-- Pestañas de Filtro --}}
            <div class="flex space-x-2 bg-gray-200 p-1 rounded-lg">
                <a href="{{ route('rrhh.empleados.index') }}" 
                class="px-4 py-2 text-sm font-bold rounded-md transition-all {{ request()->routeIs('rrhh.empleados.index') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                    Activos
                </a>
                <a href="{{ route('rrhh.empleados.inactivos') }}" 
                class="px-4 py-2 text-sm font-bold rounded-md transition-all {{ request()->routeIs('rrhh.empleados.inactivos') ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                    Inactivos
                </a>
            </div>

            {{-- Buscador --}}
            <div class="relative w-full max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" id="searchInput" onkeyup="buscarEmpleado()" 
                    placeholder="Buscar en esta lista..." 
                    class="pl-10 w-full border border-gray-300 p-2.5 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" />
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="empleadosTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">DNI</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Correo</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Telefono</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($empleados as $empleado)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-indigo-600 text-center">{{ $empleado->DNI }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Nombre }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Correo }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Telefono }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Id_Empleado }}</td>
                        
                        <td class="px-3 py-3 whitespace-nowrap text-center">
                            @if($empleado->Estado == 1)
                                <span class="px-2.5 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">ACTIVO</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">INACTIVO</span>
                            @endif
                        </td>

                        <td class="px-3 py-3 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center items-center space-x-4 h-full">
                                
                                {{-- Enlace Editar --}}
                                <a href="{{ route('rrhh.empleados.edit', $empleado->Id_Empleado) }}" class="text-indigo-600 hover:text-indigo-900 font-bold inline-block align-middle">
                                    Editar
                                </a>
                                
                                {{-- Formulario Eliminar--}}
                                <form action="{{ route('rrhh.empleados.destroy', $empleado->Id_Empleado) }}" method="POST" class="inline-block m-0 p-0 align-middle">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmarEliminar(this)" class="text-red-600 hover:text-red-900 font-bold bg-transparent border-none cursor-pointer p-0 m-0 leading-none">
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($empleados->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-400 italic">No hay empleados registrados actualmente.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.tailwindcss.com"></script>

<script>
    function buscarEmpleado() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#empleadosTable tbody tr");

        rows.forEach(row => {
            let dni = row.cells[0].innerText.toLowerCase();
            let nombre = row.cells[1].innerText.toLowerCase();
            let id = row.cells[4].innerText.toLowerCase(); // CORREGIDO: El ID está en la celda index 4

            if (dni.includes(input) || nombre.includes(input) || id.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function confirmarEliminar(button) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El empleado será retirado del registro activo (no se borrará el historial).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', 
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Sí, retirar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>