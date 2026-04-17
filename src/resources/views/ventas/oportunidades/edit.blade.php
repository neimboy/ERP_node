@extends('layouts.app')

@section('title', 'Editar Oportunidad')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Editar Oportunidad</h2>

    <form action="{{ route('oportunidades.update', $oportunidad) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Cliente</label>
            <select name="Id_Cliente" class="mt-1 block w-full border p-2 rounded" required>
                <option value="">Seleccione</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->Id_Cliente }}" {{ old('Id_Cliente', $oportunidad->Id_Cliente) == $c->Id_Cliente ? 'selected' : '' }}>{{ $c->Nombre }}</option>
                @endforeach
            </select>
            @error('Id_Cliente') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Título</label>
            <input name="Titulo" value="{{ old('Titulo', $oportunidad->Titulo) }}" class="mt-1 block w-full border p-2 rounded" />
            @error('Titulo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="Descripcion" class="mt-1 block w-full border p-2 rounded" rows="4">{{ old('Descripcion', $oportunidad->Descripcion) }}</textarea>
            @error('Descripcion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4 grid grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Monto Estimado</label>
                <input name="Monto_Estimado" type="number" step="0.01" value="{{ old('Monto_Estimado', $oportunidad->Monto_Estimado) }}" class="mt-1 block w-full border p-2 rounded" />
                @error('Monto_Estimado') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Estado</label>
                <select name="Estado" class="mt-1 block w-full border p-2 rounded">
                    <option value="Prospecto" {{ old('Estado', $oportunidad->Estado) == 'Prospecto' ? 'selected' : '' }}>Prospecto</option>
                    <option value="Negociación" {{ old('Estado', $oportunidad->Estado) == 'Negociación' ? 'selected' : '' }}>Negociación</option>
                    <option value="Cerrado" {{ old('Estado', $oportunidad->Estado) == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                </select>
                @error('Estado') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha Cierre</label>
                <input name="Fecha_Cierre" type="date" value="{{ old('Fecha_Cierre', optional($oportunidad->Fecha_Cierre)->format('Y-m-d')) }}" class="mt-1 block w-full border p-2 rounded" />
                @error('Fecha_Cierre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('clientes.show', $oportunidad->Id_Cliente) }}" class="px-3 py-2 bg-gray-200 rounded">Volver al cliente</a>
                <a href="{{ route('oportunidades.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
            </div>

            <div class="flex items-center space-x-2">
                <form action="{{ route('oportunidades.destroy', $oportunidad) }}" method="POST" onsubmit="return confirm('Eliminar oportunidad?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Eliminar</button>
                </form>

                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Guardar</button>
            </div>
        </div>
    </form>
</div>
@endsection
