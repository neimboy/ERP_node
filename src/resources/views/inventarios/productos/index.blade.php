@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Productos</h1>

   <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('productos.create') }}" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Nuevo Producto
            </a>

            <div class="relative">
                <button id="notificacionesBtn" class="relative p-2 text-gray-600 hover:text-gray-800 transition rounded hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($notificaciones->count() > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $notificaciones->count() }}</span>
                    @endif
                </button>

                <div id="notificacionesDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                    <div class="p-3 border-b border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-800">Notificaciones de Stock</h4>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        @forelse($notificaciones as $notif)
                            <div class="p-3 border-b border-gray-50 hover:bg-gray-50">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-800">{{ $notif->producto->Nombre ?? 'Producto' }}</p>
                                        <p class="text-xs text-gray-500">Cantidad requerida: {{ $notif->Cantidad_Requerida }}</p>
                                        @if($notif->proyecto)
                                            <p class="text-xs text-gray-400">Proyecto: {{ $notif->proyecto->Nombre }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <form action="{{ route('notificaciones.destroy', $notif) }}" method="POST" onsubmit="return confirm('¿Eliminar esta notificación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-6">No hay notificaciones.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <form action="{{ route('productos.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar producto (nombre o código)..."
                class="border-gray-300 rounded-lg shadow-sm px-3 py-2">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Buscar
            </button>
        </form>
    </div>

    <script src="{{ asset('js/notificaciones-dropdown.js') }}"></script>

    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">Código</th>
                <th class="border px-4 py-2 text-left">Nombre</th>
                <th class="border px-4 py-2 text-left">Precio Compra</th>
                <th class="border px-4 py-2 text-left">Precio Venta</th>
                <th class="border px-4 py-2 text-left">Proveedor</th>
                <th class="border px-4 py-2 text-left">Categoría</th>
                <th class="border px-4 py-2 text-left">Stock</th> <!-- 🔹 Nueva columna -->
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $producto->Codigo }}</td>
                    <td class="border px-4 py-2">{{ $producto->Nombre }}</td>
                    <td class="border px-4 py-2">{{ number_format($producto->Precio_Compra, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($producto->Precio_Venta, 2) }}</td>
                    <td class="border px-4 py-2">{{ $producto->proveedor->Nombre ?? 'Sin proveedor' }}</td>
                    <td class="border px-4 py-2">{{ $producto->categoria->Nombre ?? 'Sin categoría' }}</td>
                    <td class="border px-4 py-2">
                        {{ $producto->stock }}
                    </td>

                    <td class="border px-4 py-2 text-right">
                        <a href="{{ route('productos.show', $producto) }}" class="text-green-600 hover:underline">Ver</a> |
                        <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 hover:underline">Editar</a> |
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center p-4 text-gray-500">No hay productos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
