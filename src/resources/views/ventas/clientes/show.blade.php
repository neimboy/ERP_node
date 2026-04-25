@extends('layouts.app')

@section('title', 'Ver Cliente')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Cliente: {{ $cliente->Nombre }}</h2>

    <div class="grid grid-cols-1 gap-4">
        <div><strong>Documento:</strong> {{ $cliente->Documento }}</div>
        <div><strong>Correo:</strong> {{ $cliente->Correo }}</div>
        <div><strong>Teléfono:</strong> {{ $cliente->Telefono }}</div>
        <div><strong>Creado:</strong> {{ $cliente->created_at?->format('d/m/Y H:i') }}</div>
    </div>

    <div class="mt-6 flex space-x-2">
        <a href="{{ route('clientes.edit', $cliente) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Editar</a>
        <a href="{{ route('oportunidades.create', ['Id_Cliente' => $cliente->Id_Cliente]) }}" class="px-3 py-2 bg-green-600 text-white rounded">Nueva Oportunidad</a>
        <a href="{{ route('clientes.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
    </div>
</div>

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
    <h3 class="text-lg font-bold mb-3">Oportunidades abiertas</h3>

    @php
        $oportunidades = $cliente->oportunidades()->where('Estado', '<>', 'Cerrado')->orderByDesc('created_at')->get();
    @endphp

    @if($oportunidades->isEmpty())
        <div class="text-gray-600">No hay oportunidades abiertas para este cliente.</div>
    @else
        <div class="overflow-x-auto mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="p-3 text-left">Título</th><th class="p-3 text-right">Monto</th><th class="p-3">Estado</th><th class="p-3">Cierre</th><th class="p-3">Acciones</th></tr></thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($oportunidades as $op)
                    <tr>
                        <td class="p-3">{{ $op->Titulo }}</td>
                        <td class="p-3 text-right">S/ {{ number_format($op->Monto_Estimado,2) }}</td>
                        <td class="p-3">
                            @php
                                $status = $op->Estado ?? 'N/A';
                                $map = [
                                    'Ganada' => 'bg-green-100 text-green-800',
                                    'Cerrado' => 'bg-gray-100 text-gray-800',
                                    'Prospecto' => 'bg-blue-100 text-blue-800',
                                    'Negociación' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $cls }}">{{ $status }}</span>
                        </td>
                        <td class="p-3">{{ $op->Fecha_Cierre?->format('d/m/Y') ?? '—' }}</td>
                        <td class="p-3">
                            <a href="{{ route('oportunidades.edit', ['oportunidad' => $op->Id_Oportunidad]) }}" class="text-indigo-600 mr-2">Editar</a>

                            <form action="{{ route('oportunidades.cerrar', ['oportunidad' => $op->Id_Oportunidad]) }}" method="POST" class="inline" data-swal-confirm data-swal-message="Cerrar oportunidad?">
                                @csrf
                                <button type="submit" class="text-green-700 mr-2">Cerrar</button>
                            </form>

                            @if($op->Estado === 'Ganada' && empty($op->Id_Orden))
                                <form action="{{ route('oportunidades.generarOrden', ['oportunidad' => $op->Id_Oportunidad]) }}" method="POST" class="inline" data-swal-confirm data-swal-message="Generar Orden de Venta para esta oportunidad?">
                                    @csrf
                                    <button type="submit" class="text-blue-600 mr-2">Generar Orden de Venta</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-swal-confirm]').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                var msg = form.dataset.swalMessage || '¿Confirmar?';
                Swal.fire({
                    title: 'Confirmar',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then(function(result){
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
