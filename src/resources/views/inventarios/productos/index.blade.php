<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Listado de Productos</h1>

        <a href="{{ route('productos.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
           Nuevo Producto
        </a>
        <a href="{{ route('categorias.index') }}" 
        class="bg-yellow-600 text-white px-4 py-2 rounded mb-4 inline-block">
        Gestionar Categorías
        </a>
        <a href="{{ route('inventario.dashboard') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded mb-4 inline-block">
           ← Volver al Inventario
        </a>

        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="p-3 text-left">Código</th>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Precio Compra</th>
                    <th class="p-3 text-left">Precio Venta</th>
                    <th class="p-3 text-left">Proveedor</th>
                    <th class="p-3 text-left">Categoría</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr class="border-t">
                    <td class="p-3">{{ $producto->Codigo }}</td>
                    <td class="p-3">{{ $producto->Nombre }}</td>
                    <td class="p-3">{{ number_format($producto->Precio_Compra, 2) }}</td>
                    <td class="p-3">{{ number_format($producto->Precio_Venta, 2) }}</td>
                    <td class="p-3">{{ $producto->proveedor->Nombre }}</td>
                    <td class="p-3">{{ $producto->categoria->Nombre }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('productos.show', $producto->Id_Producto) }}" class="text-green-600">Ver</a>
                        <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="text-blue-600 ml-2">Editar</a>
                        <form action="{{ route('productos.destroy', $producto->Id_Producto) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4">No hay productos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
