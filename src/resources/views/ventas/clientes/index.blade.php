<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
            <a href="{{ route('clientes.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">Nuevo Cliente</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <form method="GET" class="flex gap-2">
                    <x-text-input name="q" placeholder="Buscar por nombre o correo" value="{{ $q ?? '' }}" class="w-64" />
                    <x-primary-button>Buscar</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow rounded">
                <table class="w-full">
                    <thead class="bg-gray-50"><tr><th class="text-left p-2">Nombre</th><th class="text-left p-2">Correo</th><th class="text-left p-2">Teléfono</th><th class="p-2">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr class="border-t">
                            <td class="p-2">{{ $cliente->Nombre }}</td>
                            <td class="p-2">{{ $cliente->Correo }}</td>
                            <td class="p-2">{{ $cliente->Telefono }}</td>
                            <td class="p-2">
                                <a href="{{ route('clientes.show', $cliente) }}" class="text-blue-600 mr-2">Ver</a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="text-indigo-600 mr-2">Editar</a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
