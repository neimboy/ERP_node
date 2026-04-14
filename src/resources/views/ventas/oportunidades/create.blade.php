@extends('layouts.app')

@section('title', 'Crear Oportunidad')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Crear Oportunidad</h2>

    <form action="{{ route('oportunidades.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Cliente</label>
            <select name="Id_Cliente" class="mt-1 block w-full border p-2 rounded" required>
                <option value="">Seleccione</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->Id_Cliente }}" {{ (old('Id_Cliente', request('Id_Cliente')) == $c->Id_Cliente) ? 'selected' : '' }}>{{ $c->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Título</label>
            <input name="Titulo" class="mt-1 block w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="Descripcion" class="mt-1 block w-full border p-2 rounded" rows="4"></textarea>
        </div>

        <div class="mb-4 grid grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Monto Estimado</label>
                <input name="Monto_Estimado" type="number" step="0.01" class="mt-1 block w-full border p-2 rounded" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Estado</label>
                <select name="Estado" class="mt-1 block w-full border p-2 rounded">
                    <option value="Prospecto">Prospecto</option>
                    <option value="Negociación">Negociación</option>
                    <option value="Cerrado">Cerrado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha Cierre</label>
                <input name="Fecha_Cierre" type="date" class="mt-1 block w-full border p-2 rounded" />
            </div>
        </div>

        <div class="flex justify-between items-center">
            @if(request('Id_Cliente'))
                <a href="{{ route('clientes.show', request('Id_Cliente')) }}" class="px-3 py-2 bg-gray-200 rounded">Volver al cliente</a>
            @else
                <a href="{{ route('oportunidades.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
            @endif

            <div>
                <button class="px-3 py-2 bg-gray-800 text-white rounded">Crear</button>
            </div>
        </div>
    </form>
</div>
@endsection
