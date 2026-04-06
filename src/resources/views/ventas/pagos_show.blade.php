<!DOCTYPE html>
<html>
<head>
    <title>Ver Pago</title>
    <script src=\"https://cdn.tailwindcss.com\"></script>
</head>
<body class=\"bg-gray-100 p-8\">
    <div class=\"max-w-2xl mx-auto bg-white rounded-lg shadow p-6\">
        <h1 class=\"text-2xl font-bold mb-6\">Detalle del Pago</h1>
        <p><strong>ID:</strong> {{ \$pago->Id_Pago }}</p>
        <p><strong>Factura:</strong> {{ \$pago->Id_Factura }}</p>
        <p><strong>Fecha:</strong> {{ \$pago->Fecha }}</p>
        <p><strong>Monto:</strong> S/ {{ number_format(\$pago->Monto, 2) }}</p>
        <p><strong>Método:</strong> {{ \$pago->Metodo }}</p>
        <a href=\"{{ route('pagos.index') }}\" class=\"bg-gray-300 px-4 py-2 rounded mt-4 inline-block\">Volver</a>
    </div>
</body>
</html>
EOF"

# Vista edit
docker exec laravel_app bash -c "cat > resources/views/ventas/pagos_edit.blade.php << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>Editar Pago</title>
    <script src=\"https://cdn.tailwindcss.com\"></script>
</head>
<body class=\"bg-gray-100 p-8\">
    <div class=\"max-w-2xl mx-auto bg-white rounded-lg shadow p-6\">
        <h1 class=\"text-2xl font-bold mb-6\">Editar Pago</h1>
        <form action=\"{{ route('pagos.update', \$pago->Id_Pago) }}\" method=\"POST\">
            @csrf
            @method('PUT')
            <div class=\"mb-4\">
                <label class=\"block font-bold mb-2\">Fecha</label>
                <input type=\"date\" name=\"Fecha\" value=\"{{ \$pago->Fecha }}\" class=\"w-full border rounded p-2\" required>
            </div>
            <div class=\"mb-4\">
                <label class=\"block font-bold mb-2\">Monto</label>
                <input type=\"number\" step=\"0.01\" name=\"Monto\" value=\"{{ \$pago->Monto }}\" class=\"w-full border rounded p-2\" required>
            </div>
            <div class=\"mb-4\">
                <label class=\"block font-bold mb-2\">Método</label>
                <select name=\"Metodo\" class=\"w-full border rounded p-2\" required>
                    <option value=\"Efectivo\" {{ \$pago->Metodo == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value=\"Tarjeta\" {{ \$pago->Metodo == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value=\"Transferencia\" {{ \$pago->Metodo == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value=\"Cheque\" {{ \$pago->Metodo == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
            <button type=\"submit\" class=\"bg-yellow-600 text-white px-4 py-2 rounded\">Actualizar</button>
            <a href=\"{{ route('pagos.index') }}\" class=\"bg-gray-300 px-4 py-2 rounded\">Cancelar</a>
        </form>
    </div>
</body>
</html>