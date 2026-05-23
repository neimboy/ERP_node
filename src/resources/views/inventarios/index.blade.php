<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6">Módulo Inventarios</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('productos.index') }}" 
           class="bg-blue-600 text-white p-6 rounded-lg shadow hover:bg-blue-700 text-center">
           Productos
        </a>

        <a href="{{ route('proveedores.index') }}" 
           class="bg-green-600 text-white p-6 rounded-lg shadow hover:bg-green-700 text-center">
           Proveedores
        </a>

        <a href="{{ route('categorias.index') }}" 
           class="bg-purple-600 text-white p-6 rounded-lg shadow hover:bg-purple-700 text-center">
           Categorías
        </a>
    </div>
</div>

