<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Proveedor - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Registrar Nuevo Proveedor</h1>

        <form action="{{ route('proveedores.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="RUC" class="block text-sm font-medium text-gray-700">RUC</label>
                <input type="text" name="RUC" id="RUC" maxlength="20"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                       required>
            </div>

            <div>
                <label for="Nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="Nombre" id="Nombre" maxlength="150"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                       required>
            </div>

            <div>
                <label for="Telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="Telefono" id="Telefono" maxlength="20"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label for="Email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="Email" id="Email"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label for="Direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                <textarea name="Direccion" id="Direccion" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded">
                    Guardar
                </button>

                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded">
                   ← Volver 
                </a>
            </div>
        </form>
    </div>
</body>
</html>
