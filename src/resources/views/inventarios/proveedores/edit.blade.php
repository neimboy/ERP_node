<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Editar Proveedor</h1>

        <form action="{{ route('proveedores.update', $proveedor->Id_Proveedor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1">RUC</label>
                <input type="text" name="RUC" value="{{ $proveedor->RUC }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Nombre</label>
                <input type="text" name="Nombre" value="{{ $proveedor->Nombre }}" 
                       class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Teléfono</label>
                <input type="text" name="Telefono" value="{{ $proveedor->Telefono }}" 
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="Email" value="{{ $proveedor->Email }}" 
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Dirección</label>
                <textarea name="Direccion" rows="3" 
                          class="w-full border rounded p-2">{{ $proveedor->Direccion }}</textarea>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Actualizar
                </button>
                <a href="{{ route('proveedores.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded">
                   Volver
                </a>
            </div>
        </form>
    </div>
</body>
</html>
