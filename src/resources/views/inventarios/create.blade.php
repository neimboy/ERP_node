<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Crear Producto</h1>

        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Nombre</label>
                <input type="text" name="nombre" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Precio</label>
                <input type="number" step="0.01" name="precio" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Stock</label>
                <input type="number" name="stock" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>
</body>
</html>
