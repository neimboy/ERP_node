<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Categoría - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Detalle de Categoría</h1>

        <p><strong>ID:</strong> {{ $categoria->Id_Categoria }}</p>
        <p><strong>Nombre:</strong> {{ $categoria->Nombre }}</p>
        <p><strong>Creado:</strong> {{ $categoria->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $categoria->updated_at->format('d/m/Y H:i') }}</p>

        <a href="{{ route('categorias.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded mt-4 inline-block">Volver</a>
    </div>
</body>
</html>
