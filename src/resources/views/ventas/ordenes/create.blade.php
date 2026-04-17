@extends('layouts.app')

@section('title', 'Crear Orden')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Crear Orden de Venta</h2>

    <form action="{{ route('ordenes.store') }}" method="POST" x-data="orderForm()">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Cliente</label>
            <select name="Id_Cliente" class="mt-1 block w-full border p-2 rounded" required>
                <option value="">Seleccione</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->Id_Cliente }}">{{ $c->Nombre }}{{ $c->Correo ? ' - '.$c->Correo : '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left"><th class="p-2">Producto</th><th class="p-2">Cantidad</th><th class="p-2 text-right">Precio</th><th class="p-2 text-right">Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    <template x-for="(line, index) in lines" :key="index">
                        <tr class="border-t">
                            <td class="p-2">
                                <select :name="'lineas['+index+'][Id_Producto]'" x-model="line.productoId" @change="updateLine(index)" class="border p-2 w-full rounded">
                                    <option value="">Seleccione</option>
                                    @foreach($productos as $p)
                                        <option value="{{ $p->Id_Producto }}" data-precio="{{ $p->Precio_Venta }}">{{ $p->Nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2"><input type="number" :name="'lineas['+index+'][cantidad]'" x-model.number="line.cantidad" @input="updateLine(index)" min="1" class="border p-2 w-24 rounded" /></td>
                            <td class="p-2 text-right">S/ <span x-text="formatMoney(line.precio)"></span></td>
                            <td class="p-2 text-right">S/ <span x-text="formatMoney(line.subtotal)"></span></td>
                            <td class="p-2 text-center"><button type="button" @click="removeLine(index)" class="text-red-600">Eliminar</button></td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="mt-4">
                <button type="button" @click="addLine()" class="px-3 py-2 bg-gray-800 text-white rounded">Añadir línea</button>
            </div>
        </div>

        <div class="text-right font-bold mb-4">
            Total: <span x-text="formatMoney(total)"></span>
        </div>

        <div class="flex justify-end">
            <x-primary-button>Crear Orden</x-primary-button>
        </div>
    </form>
</div>

<script>
function orderForm() {
    return {
        productos: @json($productos->map(function($p){ return ['id' => $p->Id_Producto, 'precio' => $p->Precio_Venta]; })),
        lines: [{productoId: '', cantidad:1, precio:0, subtotal:0}],
        addLine() { this.lines.push({productoId:'', cantidad:1, precio:0, subtotal:0}); },
        removeLine(i) { this.lines.splice(i,1); },
        updateLine(i) {
            let line = this.lines[i];
            let producto = this.productos.find(p => p.id == line.productoId);
            line.precio = producto ? parseFloat(producto.precio) : 0;
            line.subtotal = line.precio * (Number(line.cantidad) || 0);
        },
        get total() { return this.lines.reduce((s,l) => s + (l.subtotal || 0), 0); },
        formatMoney(v) { return new Intl.NumberFormat('es-PE', {minimumFractionDigits:2, maximumFractionDigits:2}).format(v || 0); }
    }
}
</script>

@endsection
