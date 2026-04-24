<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Listado de Proveedores</h1>

        <div class="flex space-x-4 mb-4">
            <a href="{{ route('proveedores.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded">
               Nuevo Proveedor
            </a>

            <a href="{{ route('dashboard') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded">
               ← Volver al Inventario
            </a>
        </div>

        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">RUC</th>
                    <th class="p-3 text-left">Teléfono</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Dirección</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proveedores as $proveedor)
                <tr class="border-t">
                    <td class="p-3">{{ $proveedor->Nombre }}</td>
                    <td class="p-3">{{ $proveedor->RUC }}</td>
                    <td class="p-3">{{ $proveedor->Telefono }}</td>
                    <td class="p-3">{{ $proveedor->Email }}</td>
                    <td class="p-3">{{ $proveedor->Direccion }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('proveedores.show', $proveedor->Id_Proveedor) }}" class="text-green-600">Ver</a>
                        <a href="{{ route('proveedores.edit', $proveedor->Id_Proveedor) }}" class="text-blue-600 ml-2">Editar</a>
                        <form action="{{ route('proveedores.destroy', $proveedor->Id_Proveedor) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-4">No hay proveedores registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
