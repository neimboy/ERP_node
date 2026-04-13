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

        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Precio</th>
                    <th class="p-3 text-left">Stock</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr class="border-t">
                    <td class="p-3">{{ $producto->nombre }}</td>
                    <td class="p-3">{{ number_format($producto->precio, 2) }}</td>
                    <td class="p-3">{{ $producto->stock }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('productos.show', $producto) }}" class="text-green-600">Ver</a>
                        <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 ml-2">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
