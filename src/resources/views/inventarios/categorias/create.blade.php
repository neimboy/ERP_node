<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Categoría - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Nueva Categoría</h1>

        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Nombre</label>
                <input type="text" name="Nombre" class="w-full border rounded p-2" required>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                <a href="{{ route('categorias.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
