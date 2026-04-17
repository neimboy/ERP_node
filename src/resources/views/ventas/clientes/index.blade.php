@extends('layouts.app') {{-- o el nombre de tu layout --}}

@section('content')

<div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold">Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
        Nuevo Cliente
    </a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr>
            <th class="p-2">Nombre</th>
            <th class="p-2">Correo</th>
            <th class="p-2">Teléfono</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td class="p-2">{{ $cliente->Nombre }}</td>
            <td class="p-2">{{ $cliente->Correo }}</td>
            <td class="p-2">{{ $cliente->Telefono }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection