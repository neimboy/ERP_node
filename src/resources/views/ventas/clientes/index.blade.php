@extends('layouts.app') {{-- o el nombre de tu layout --}}

@section('content')

<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-4">
        <h2 class="text-xl font-bold">Clientes</h2>

        <form method="GET" action="{{ route('clientes.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="q" value="{{ $q ?? '' }}">
            <label for="per_page" class="text-sm text-gray-600">Mostrar</label>
            <div class="relative">
                <select id="per_page" name="per_page" onchange="this.form.submit()" class="appearance-none bg-white border border-gray-300 text-sm rounded py-1 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="10" {{ (isset($perPage) && $perPage==10) ? 'selected' : '' }}>10</option>
                    <option value="25" {{ (isset($perPage) && $perPage==25) ? 'selected' : '' }}>25</option>
                    <option value="50" {{ (isset($perPage) && $perPage==50) ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (isset($perPage) && $perPage==100) ? 'selected' : '' }}>100</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 12a1 1 0 01-.707-.293l-3-3a1 1 0 011.414-1.414L10 9.586l2.293-2.293a1 1 0 111.414 1.414l-3 3A1 1 0 0110 12z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <a href="{{ route('clientes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i> Nuevo Cliente
    </a>
</div>

<!-- Mobile: card list -->
<div class="md:hidden space-y-3">
    @foreach($clientes as $cliente)
    <div class="bg-white shadow rounded p-4 flex justify-between items-start">
        <div>
            <div class="font-semibold text-gray-800">{{ $cliente->Nombre }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ $cliente->Correo }}</div>
            <div class="text-sm text-gray-500">{{ $cliente->Telefono }}</div>
            <div class="text-sm text-gray-600">{{ $cliente->Documento }}</div>
        </div>
        <div class="flex flex-col items-end space-y-2 ml-4">
            <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded">
                <i class="fas fa-edit"></i>
                <span>Editar</span>
            </a>
            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded">
                    <i class="fas fa-trash-alt"></i>
                    <span>Eliminar</span>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Desktop / Tablet: table -->
<table class="hidden md:table w-full bg-white shadow rounded">
    <thead>
        <tr>
            <th class="p-2 text-left">Nombre</th>
            <th class="p-2 text-left">Correo</th>
            <th class="p-2 text-left">Teléfono</th>
            <th class="p-2 text-left">Documento</th>
            <th class="p-2 text-left">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr class="border-b">
            <td class="p-3 align-top">
                <div class="font-semibold text-gray-800">{{ $cliente->Nombre }}</div>
            </td>
            <td class="p-3 align-top">
                <div class="text-sm text-gray-500">{{ $cliente->Correo }}</div>
            </td>
            <td class="p-3 align-top">
                <div class="text-sm text-gray-500">{{ $cliente->Telefono }}</div>
            </td>
            <td class="p-3 align-top">
                <div class="text-sm text-gray-600">{{ $cliente->Documento }}</div>
            </td>
            <td class="p-3 align-top">
                <div class="flex items-center justify-end space-x-2">
                    <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-edit"></i>
                        <span>Editar</span>
                    </a>
                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-trash-alt"></i>
                            <span>Eliminar</span>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4 flex items-center justify-between">
    <div class="text-sm text-gray-600">
        Mostrando {{ $clientes->firstItem() ?? 0 }}–{{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }}
    </div>
    <div class="bg-white rounded shadow px-3 py-2">
        {{ $clientes->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('per_page');
    if (!select) return;

    // Guardar selección en localStorage al cambiar
    select.addEventListener('change', function () {
        try { localStorage.setItem('clientes.perPage', select.value); } catch (e) { /* ignore */ }
        // el formulario ya envía por onchange; si no, se podría enviar manualmente
    });

    // Si no hay per_page en la URL pero existe una preferencia guardada, aplicarla
    try {
        const params = new URLSearchParams(window.location.search);
        if (!params.has('per_page')) {
            const saved = localStorage.getItem('clientes.perPage');
            if (saved) {
                params.set('per_page', saved);
                window.location.search = params.toString();
            }
        }
    } catch (e) { /* ignore on older browsers */ }
});
</script>

@endsection
