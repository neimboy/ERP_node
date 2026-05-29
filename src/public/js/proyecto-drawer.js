document.addEventListener('DOMContentLoaded', function() {
    const drawer = document.getElementById('proyectoDrawer');
    const overlay = document.getElementById('drawerOverlay');
    const closeBtn = document.getElementById('closeDrawer');
    const content = document.getElementById('drawerContent');
    
    const proyectos = window.proyectosData || [];
    
    const estadoColors = {
        'Pendiente': 'bg-yellow-100 text-yellow-800',
        'En Progreso': 'bg-blue-100 text-blue-800',
        'Completado': 'bg-green-100 text-green-800'
    };
    
    function openDrawer(proyectoId) {
        const proyecto = proyectos.find(p => p.Id_Proyecto == proyectoId);
        if (!proyecto) return;
        
        const totalHoras = proyecto.asignaciones ? proyecto.asignaciones.reduce((sum, a) => sum + a.Horas_Asignadas, 0) : 0;
        const numEmpleados = proyecto.asignaciones ? proyecto.asignaciones.length : 0;
        
        let empleadosHtml = '';
        if (proyecto.asignaciones && proyecto.asignaciones.length > 0) {
            proyecto.asignaciones.forEach(function(emp) {
                empleadosHtml += '<div class="flex items-center justify-between py-2 border-b border-gray-100">' +
                    '<div class="flex items-center gap-3">' +
                        '<div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">' +
                            '<span class="text-indigo-600 font-medium text-sm">' + (emp.empleado ? emp.empleado.Nombre.charAt(0) : '?') + '</span>' +
                        '</div>' +
                        '<span class="text-gray-700">' + (emp.empleado ? emp.empleado.Nombre : 'N/A') + '</span>' +
                    '</div>' +
                    '<span class="text-gray-500 text-sm">' + emp.Horas_Asignadas + ' hrs</span>' +
                '</div>';
            });
        } else {
            empleadosHtml = '<p class="text-gray-500 text-sm">No hay empleados asignados</p>';
        }
        
        content.innerHTML = 
            '<div class="space-y-5">' +
                '<div>' +
                    '<p class="text-xs text-gray-500 uppercase mb-1">Proyecto</p>' +
                    '<p class="text-lg font-semibold text-gray-800">' + proyecto.Nombre + '</p>' +
                '</div>' +
                '<div>' +
                    '<p class="text-xs text-gray-500 uppercase mb-1">Cliente</p>' +
                    '<p class="text-gray-700">' + (proyecto.cliente ? proyecto.cliente.Nombre : 'Sin cliente') + '</p>' +
                '</div>' +
                '<div>' +
                    '<p class="text-xs text-gray-500 uppercase mb-1">Estado</p>' +
                    '<span class="px-2.5 py-1 rounded-full text-xs font-medium ' + (estadoColors[proyecto.Estado] || 'bg-gray-100 text-gray-800') + '">' + (proyecto.Estado || 'Sin estado') + '</span>' +
                '</div>' +
                '<div class="grid grid-cols-2 gap-4">' +
                    '<div>' +
                        '<p class="text-xs text-gray-500 uppercase mb-1">Inicio</p>' +
                        '<p class="text-gray-700">' + (proyecto.Fecha_Inicio || 'N/A') + '</p>' +
                    '</div>' +
                    '<div>' +
                        '<p class="text-xs text-gray-500 uppercase mb-1">Fin</p>' +
                        '<p class="text-gray-700">' + (proyecto.Fecha_Fin || 'N/A') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="bg-indigo-50 rounded-lg p-4">' +
                    '<div class="grid grid-cols-2 gap-4">' +
                        '<div>' +
                            '<span class="text-indigo-600 font-bold text-xl">' + totalHoras + '</span>' +
                            '<p class="text-xs text-gray-600">horas asignadas</p>' +
                        '</div>' +
                        '<div>' +
                            '<span class="text-indigo-600 font-bold text-xl">' + numEmpleados + '</span>' +
                            '<p class="text-xs text-gray-600">empleados</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div>' +
                    '<p class="text-xs text-gray-500 uppercase mb-2">Empleados Asignados</p>' +
                    '<div class="bg-gray-50 rounded-lg p-3">' + empleadosHtml + '</div>' +
                '</div>' +
                '<div class="pt-4 space-y-2">' +
                    (proyecto.Estado === 'Completado' ?
                    '<a href="/produccion/proyectos/' + proyecto.Id_Proyecto + '/reporte" target="_blank" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium transition">' +
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                        'Generar Reporte' +
                    '</a>' : '') +
                    '<a href="/produccion/proyectos/' + proyecto.Id_Proyecto + '" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium transition">' +
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>' +
                        'Ver Proyecto' +
                    '</a>' +
                    '<a href="/produccion/proyectos/' + proyecto.Id_Proyecto + '/edit" class="w-full flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg font-medium transition">' +
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>' +
                        'Editar Proyecto' +
                    '</a>' +
                    '<a href="/produccion/asignaciones/create?proyecto_id=' + proyecto.Id_Proyecto + '" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium transition">' +
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>' +
                        'Agregar Empleado' +
                    '</a>' +
                '</div>' +
            '</div>';
        
        drawer.classList.remove('hidden');
    }
    
    function closeDrawer() {
        drawer.classList.add('hidden');
    }
    
    document.querySelectorAll('.proyecto-card').forEach(function(card) {
        card.addEventListener('click', function() {
            openDrawer(this.dataset.proyectoId);
        });
    });
    
    closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDrawer();
    });
});