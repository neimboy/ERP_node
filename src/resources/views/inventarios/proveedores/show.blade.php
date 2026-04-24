<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Proveedor - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Detalle del Proveedor</h1>

        <p><strong>RUC:</strong> {{ $proveedor->RUC }}</p>
        <p><strong>Nombre:</strong> {{ $proveedor->Nombre }}</p>
        <p><strong>Teléfono:</strong> {{ $proveedor->Telefono }}</p>
        <p><strong>Email:</strong> {{ $proveedor->Email }}</p>
        <p><strong>Dirección:</strong> {{ $proveedor->Direccion }}</p>
        <p><strong>Creado:</strong> {{ $proveedor->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $proveedor->updated_at->format('d/m/Y H:i') }}</p>

        <a href="{{ route('proveedores.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded mt-4 inline-block">
           Volver
        </a>
    </div>
</body>
</html>
