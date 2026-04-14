
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Recursos Humanos</h1>
                <p class="text-gray-500">Gestión de empleados registrados</p>
            </div>
            <a href="{{ route('empleados.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Empleado
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">DNI</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre_Empleado</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Correo_Empleado</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Telefono</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Ingreso</th>
                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($empleados as $empleado)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-indigo-600">{{ $empleado->DNI }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Nombre_Empleado }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Correo_Empleado }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Telefono }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">{{ $empleado->Fecha_Ingreso }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $empleado->Id_Empleado}}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                            <button class="text-red-600 hover:text-red-900 mr-3">Eliminar</button>
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
        <script src="https://cdn.tailwindcss.com"></script>
    </div>
</div>