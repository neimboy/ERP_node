@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Editar Cliente</h2>

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PATCH')

        @include('ventas.clientes._form')

        <div class="flex justify-end">
            <x-primary-button>Actualizar</x-primary-button>
        </div>
    </form>
</div>
@endsection
