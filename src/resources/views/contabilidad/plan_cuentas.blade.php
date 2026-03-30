<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Cuentas - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4 text-blue-600">Catálogo de Cuentas Contables</h1>
        
        <form action="{{ route('contabilidad.plan_cuentas.store') }}" method="POST" class="mb-6 grid grid-cols-3 gap-4">
            @csrf
            <input type="text" name="Codigo" placeholder="Código (ej. 101)" class="border p-2 rounded">
            <input type="text" name="Nombre_Cuenta" placeholder="Nombre de la Cuenta" class="border p-2 rounded">
            <select name="Tipo" class="border p-2 rounded">
                <option value="Activo">Activo</option>
                <option value="Pasivo">Pasivo</option>
                <option value="Patrimonio">Patrimonio</option>
                <option value="Ingreso">Ingreso</option>
                <option value="Gasto">Gasto</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Agregar Cuenta</button>
        </form>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Código</th>
                    <th class="p-2 border">Nombre</th>
                    <th class="p-2 border">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuentas as $cuenta)
                <tr>
                    <td class="p-2 border">{{ $cuenta->Codigo }}</td>
                    <td class="p-2 border">{{ $cuenta->Nombre_Cuenta }}</td>
                    <td class="p-2 border">{{ $cuenta->Tipo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>