<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Editar Empleado</h1>
                <p class="text-gray-500">Modifica la información de {{ $empleado->Nombre }}</p>
            </div>
            <a href="{{ route('empleados.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                &larr; Volver al panel
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden p-8">
            <form action="{{ route('empleados.update', $empleado->Id_Empleado) }}" method="POST">
                @csrf
                @method('PUT') 
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">DNI</label>
                        <input type="text" name="DNI" value="{{ $empleado->DNI }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-2 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" name="Nombre" value="{{ $empleado->Nombre }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" name="Correo" value="{{ $empleado->Correo }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="Telefono" value="{{ $empleado->Telefono }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                        <input type="date" name="Fecha_Ingreso" value="{{ $empleado->Fecha_Ingreso }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado del Empleado</label>
                        <select name="Estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                            <option value="1" {{ $empleado->Estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $empleado->Estado == 0 ? 'selected' : '' }}>Inactivo </option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-md shadow-sm transition-colors">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</div>