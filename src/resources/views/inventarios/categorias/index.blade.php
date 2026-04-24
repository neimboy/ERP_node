<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Listado de Categorías</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between mb-4">
            <a href="{{ route('categorias.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded">
               Nueva Categoría
            </a>
            <a href="{{ route('productos.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded">
               ← Volver a Productos
            </a>
        </div>

        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Nombre</th>
                    <th class="border border-gray-300 p-2">Creado</th>
                    <th class="border border-gray-300 p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $categoria)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $categoria->Id_Categoria }}</td>
                        <td class="border border-gray-300 p-2">{{ $categoria->Nombre }}</td>
                        <td class="border border-gray-300 p-2">{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('categorias.show', $categoria->Id_Categoria) }}" 
                               class="bg-gray-600 text-white px-2 py-1 rounded">Ver</a>
                            <a href="{{ route('categorias.edit', $categoria->Id_Categoria) }}" 
                               class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</a>
                            <form action="{{ route('categorias.destroy', $categoria->Id_Categoria) }}" 
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
