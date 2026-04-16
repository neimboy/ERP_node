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
        
        <form action="{{ route('rrhh.empleados.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold">DNI:</label>
                    <input type="text" name="DNI" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-semibold">Nombre Completo:</label>
                    <input type="text" name="Nombre_Empleado" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-semibold">Correo:</label>
                    <input type="email" name="Correo_Empleado" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold">Teléfono:</label>
                    <input type="text" name="Telefono" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-semibold">Fecha de Ingreso:</label>
                    <input type="date" name="Fecha_Ingreso" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-semibold">Estado:</label>
                    <select name="Estado" class="w-full border p-2 rounded">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo (Baja)</option>
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