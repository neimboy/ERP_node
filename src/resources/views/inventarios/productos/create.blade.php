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
                <label class="block mb-1">Código</label>
                <input type="text" name="Codigo" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Nombre</label>
                <input type="text" name="Nombre" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Precio Compra</label>
                <input type="number" step="0.01" name="Precio_Compra" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Precio Venta</label>
                <input type="number" step="0.01" name="Precio_Venta" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Proveedor</label>
                <select name="Id_Proveedor" class="w-full border rounded p-2" required>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->Id_Proveedor }}">{{ $proveedor->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Categoría</label>
                <select name="Id_Categoria" class="w-full border rounded p-2" required>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->Id_Categoria }}">{{ $categoria->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Guardar
                </button>
                <a href="{{ route('productos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                    Volver
                </a>
            </div>
        </form>
    </div>
</body>
</html>
