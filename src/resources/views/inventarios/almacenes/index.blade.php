<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Almacenes - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Listado de Almacenes</h1>

        <a href="{{ route('almacenes.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
           Nuevo Almacén
        </a>
        <a href="{{ route('productos.index') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
           Gestionar Productos
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
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Dirección</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($almacenes as $almacen)
                <tr class="border-t">
                    <td class="p-3">{{ $almacen->Id_Almacen }}</td>
                    <td class="p-3">{{ $almacen->Nombre }}</td>
                    <td class="p-3">{{ $almacen->Direccion }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('almacenes.show', $almacen->Id_Almacen) }}" class="text-green-600">Ver</a>
                        <a href="{{ route('almacenes.edit', $almacen->Id_Almacen) }}" class="text-blue-600 ml-2">Editar</a>
                        <form action="{{ route('almacenes.destroy', $almacen->Id_Almacen) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este almacén?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-4">No hay almacenes registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>

