document.addEventListener('DOMContentLoaded', function () {
    const productosNuevos = {};
    let notificarProducto = null;

    // ── Productos en Inventario — buscar ──
    const buscadorInventario = document.getElementById('buscadorProductosEdit');
    if (buscadorInventario) {
        buscadorInventario.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.producto-item-edit').forEach(function (item) {
                item.style.display = item.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // ── Productos en Inventario — agregar nuevo ──
    document.querySelectorAll('.agregar-nuevo-producto').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const item = this.closest('.producto-item-edit');
            const id = item.dataset.id;
            const nombre = item.dataset.nombre;
            const stockMax = parseInt(item.dataset.stock);

            const cantidad = prompt('Cantidad para "' + nombre + '":', '1');
            if (!cantidad || isNaN(cantidad) || parseInt(cantidad) < 1) return;

            const cantNum = parseInt(cantidad);
            if (cantNum > stockMax) {
                alert('Stock insuficiente. Disponible: ' + stockMax);
                return;
            }

            if (productosNuevos[id]) {
                productosNuevos[id].Cantidad += cantNum;
            } else {
                productosNuevos[id] = { Id_Producto: id, Nombre: nombre, Cantidad: cantNum };
            }

            renderizarNuevosProductos();
        });
    });

    function renderizarNuevosProductos() {
        const container = document.getElementById('nuevosProductosContainer');
        const sinMsg = document.getElementById('sinNuevosMsg');
        if (!container) return;
        const ids = Object.keys(productosNuevos);

        container.querySelectorAll('.nuevo-producto-item').forEach(function (el) { el.remove(); });

        if (ids.length === 0) {
            if (sinMsg) sinMsg.style.display = '';
            return;
        }

        if (sinMsg) sinMsg.style.display = 'none';

        ids.forEach(function (id) {
            const p = productosNuevos[id];
            const div = document.createElement('div');
            div.className = 'nuevo-producto-item flex items-center justify-between bg-green-50 rounded p-2';
            div.innerHTML =
                '<span class="text-xs font-medium text-gray-700">' + p.Nombre + ' x' + p.Cantidad + '</span>' +
                '<button type="button" class="quitar-nuevo text-red-500 hover:text-red-700" data-id="' + id + '">' +
                '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>' +
                '<input type="hidden" name="productos[' + id + '][Id_Producto]" value="' + id + '">' +
                '<input type="hidden" name="productos[' + id + '][Cantidad]" value="' + p.Cantidad + '">';
            container.appendChild(div);

            div.querySelector('.quitar-nuevo').addEventListener('click', function () {
                delete productosNuevos[this.dataset.id];
                renderizarNuevosProductos();
            });
        });
    }

    // ── Devolver Productos — buscar ──
    const buscadorDevolver = document.getElementById('buscadorDevolverProductos');
    if (buscadorDevolver) {
        buscadorDevolver.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.devolver-producto-row').forEach(function (item) {
                item.style.display = item.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // ── Devolver Productos — filtrar vacíos al enviar ──
    const formDevolver = document.querySelector('form[action*="devolver-productos"]');
    if (formDevolver) {
        formDevolver.addEventListener('submit', function () {
            document.querySelectorAll('.devolver-producto-row').forEach(function (row) {
                const cantidad = row.querySelector('input[name*="[Cantidad]"]');
                if (!cantidad.value || parseInt(cantidad.value) < 1) {
                    row.querySelectorAll('input').forEach(function (i) { i.disabled = true; });
                }
            });
        });
    }

    // ── Notificar Falta de Stock — buscar ──
    const buscadorNotificar = document.getElementById('buscadorNotificarStock');
    if (buscadorNotificar) {
        buscadorNotificar.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.producto-item-notificar').forEach(function (item) {
                item.style.display = item.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // ── Notificar Falta de Stock — seleccionar producto ──
    document.querySelectorAll('.btn-notificar-producto').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const item = this.closest('.producto-item-notificar');
            const id = item.dataset.id;
            const nombre = item.dataset.nombre;

            const cantidad = prompt('Cantidad requerida para "' + nombre + '":', '1');
            if (!cantidad || isNaN(cantidad) || parseInt(cantidad) < 1) return;

            notificarProducto = { Id_Producto: id, Nombre: nombre, Cantidad: parseInt(cantidad) };
            actualizarSeleccionNotificar();
        });
    });

    function actualizarSeleccionNotificar() {
        const container = document.getElementById('notificarSeleccionContainer');
        if (!container) return;
        container.innerHTML = '';

        if (!notificarProducto) {
            container.innerHTML = '<p class="text-xs text-gray-400">Selecciona un producto de la lista.</p>';
            return;
        }

        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-red-50 rounded p-2';
        div.innerHTML =
            '<span class="text-xs font-medium text-gray-700">' + notificarProducto.Nombre + ' x' + notificarProducto.Cantidad + '</span>' +
            '<button type="button" class="quitar-notificar text-red-500 hover:text-red-700">' +
            '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
            '</button>' +
            '<input type="hidden" name="Id_Producto" value="' + notificarProducto.Id_Producto + '">' +
            '<input type="hidden" name="Cantidad" value="' + notificarProducto.Cantidad + '">';
        container.appendChild(div);

        div.querySelector('.quitar-notificar').addEventListener('click', function () {
            notificarProducto = null;
            actualizarSeleccionNotificar();
        });
    }
});
