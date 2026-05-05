<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2">Registrar Nuevo Empleado</h2>
        
        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold">DNI:</label>
                    <input type="text" name="DNI" value="{{ old('DNI') }}" 
                        class="w-full border p-2 rounded @error('DNI') border-red-500 @enderror" required>
                    @error('DNI')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold">Nombre Completo:</label>
                    <input type="text" name="Nombre" value="{{ old('Nombre') }}" 
                        class="w-full border p-2 rounded @error('Nombre') border-red-500 @enderror" required>
                    @error('Nombre')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold">Correo:</label>
                    <input type="email" name="Correo" value="{{ old('Correo') }}" 
                        class="w-full border p-2 rounded @error('Correo') border-red-500 @enderror">
                    @error('Correo')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold">Teléfono:</label>
                    <input type="text" name="Telefono" value="{{ old('Telefono') }}" 
                        class="w-full border p-2 rounded @error('Telefono') border-red-500 @enderror">
                    @error('Telefono')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold">Fecha de Ingreso:</label>
                    <input type="date" name="Fecha_Ingreso" value="{{ old('Fecha_Ingreso') }}" 
                        class="w-full border p-2 rounded @error('Fecha_Ingreso') border-red-500 @enderror" required>
                    @error('Fecha_Ingreso')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold">Estado:</label>
                    <select name="Estado" class="w-full border p-2 rounded">
                        <option value="1" {{ old('Estado') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('Estado') == '0' ? 'selected' : '' }}>Inactivo </option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4 hover:bg-blue-700">
                    GUARDAR EMPLEADO
                </button>
            </div>
        </form>
    </div>
</body>
</html>