<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Producto - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Detalle del Producto</h1>

        <p><strong>Código:</strong> {{ $producto->Codigo }}</p>
        <p><strong>Nombre:</strong> {{ $producto->Nombre }}</p>
        <p><strong>Precio Compra:</strong> {{ number_format($producto->Precio_Compra, 2) }}</p>
        <p><strong>Precio Venta:</strong> {{ number_format($producto->Precio_Venta, 2) }}</p>
        <p><strong>Proveedor:</strong> {{ $producto->proveedor->Nombre }}</p>
        <p><strong>Categoría:</strong> {{ $producto->categoria->Nombre }}</p>
        <p><strong>Creado:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}</p>

        <a href="{{ route('productos.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded mt-4 inline-block">
           Volver
        </a>
    </div>
</body>
</html>
