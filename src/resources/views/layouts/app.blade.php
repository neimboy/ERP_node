<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Sistema - @yield('title', 'Gestión Empresarial')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Estilos personalizados --}}
    @vite(['resources/css/app.css'])
    
    {{-- Fuente Inter (Google Fonts) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" 
         x-data="{ openContabilidad: false, openAdmin: false, openInventario: false, openVentas: false, openProduccion: false }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo y Menú --}}
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">
                            <span class="text-blue-600">ERP</span> Sistema
                        </h1>
                    </div>

                    <div class="hidden md:ml-8 md:flex md:space-x-1 items-center">

                        {{-- Dashboard --}}
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-th-large mr-1.5 text-xs"></i> Dashboard
                        </a>

                        @auth
                            {{-- ADMINISTRACIÓN --}}
                            @role('Super Admin')
                                <div class="relative">
                                    <button @click="openAdmin = !openAdmin"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                                   {{ request()->is('admin*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-shield-alt mr-1.5 text-xs"></i> Admin
                                        <i class="fas fa-chevron-down ml-1.5 text-xs"></i>
                                    </button>

                                    <div x-show="openAdmin" @click.away="openAdmin = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         style="display: none;">
                                        <div class="py-2 px-2">
                                            <span class="block px-3 py-1.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Sistema</span>
                                            <a href="{{ route('admin.users') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="fas fa-users-cog mr-2 text-red-500"></i> Gestión de Usuarios
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endrole

                            {{-- CONTABILIDAD --}}
                            @role('Super Admin|Contador')
                                <div class="relative">
                                    <button @click="openContabilidad = !openContabilidad"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                                   {{ request()->is('contabilidad*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-calculator mr-1.5 text-xs"></i> Contabilidad
                                        <i class="fas fa-chevron-down ml-1.5 text-xs"></i>
                                    </button>

                                    <div x-show="openContabilidad" @click.away="openContabilidad = false"
                                         class="absolute z-50 left-0 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         style="display: none;">
                                        <div class="py-2 px-2">
                                            <span class="block px-3 py-1.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Gestión Contable</span>
                                            <a href="{{ route('contabilidad.plan_cuentas') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-book mr-2 text-blue-500"></i> Plan de Cuentas
                                            </a>
                                            <a href="{{ route('asientos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-file-invoice mr-2 text-blue-500"></i> Asientos Contables
                                            </a>

                                            <div class="border-t border-gray-100 my-2"></div>

                                            <span class="block px-3 py-1.5 text-xs font-bold text-gray-400 uppercase tracking-wider">Reportes Financieros</span>
                                            <a href="{{ route('contabilidad.libro_mayor') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-book-open mr-2 text-indigo-500"></i> Libro Mayor
                                            </a>
                                            <a href="{{ route('contabilidad.estado_resultados') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-chart-line mr-2 text-indigo-500"></i> Estado de Resultados
                                            </a>
                                            <a href="{{ route('contabilidad.estado_resultados_semestral') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-chart-bar mr-2 text-indigo-500"></i> Resultado Semestral
                                            </a>
                                            <a href="{{ route('contabilidad.balance_general') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-balance-scale mr-2 text-indigo-500"></i> Balance General
                                            </a>
                                            <a href="{{ route('contabilidad.igv_mensual') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-file-invoice-dollar mr-2 text-indigo-500"></i> IGV Mensual
                                            </a>
                                            <a href="{{ route('contabilidad.resumen_gerencial') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fas fa-tachometer-alt mr-2 text-indigo-500"></i> Resumen Gerencial
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endrole

                            {{-- VENTAS --}}
                            @can('view_ventas')
                                <div class="relative">
                                    <button @click="openVentas = !openVentas"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                                   {{ request()->is('ventas*') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-shopping-cart mr-1.5 text-xs"></i> Ventas
                                        <i class="fas fa-chevron-down ml-1.5 text-xs"></i>
                                    </button>

                                    <div x-show="openVentas" @click.away="openVentas = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         style="display: none;">
                                        <div class="py-2 px-2">
                                            <a href="{{ route('clientes.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <i class="fas fa-users mr-2 text-emerald-500"></i> Clientes
                                            </a>
                                            <a href="{{ route('oportunidades.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <i class="fas fa-lightbulb mr-2 text-emerald-500"></i> Oportunidades
                                            </a>
                                            <a href="{{ route('ordenes.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <i class="fas fa-clipboard-list mr-2 text-emerald-500"></i> Órdenes
                                            </a>
                                            <a href="{{ route('facturas.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <i class="fas fa-file-invoice mr-2 text-emerald-500"></i> Facturas
                                            </a>
                                            <a href="{{ route('pagos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <i class="fas fa-money-bill-wave mr-2 text-emerald-500"></i> Pagos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            {{-- INVENTARIO --}}
                            @can('view_inventario')
                                <div class="relative">
                                    <button @click="openInventario = !openInventario"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                                   {{ request()->is('inventario*') ? 'bg-amber-50 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-boxes mr-1.5 text-xs"></i> Inventario
                                        <i class="fas fa-chevron-down ml-1.5 text-xs"></i>
                                    </button>

                                    <div x-show="openInventario" @click.away="openInventario = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         style="display: none;">
                                        <div class="py-2 px-2">
                                            <a href="{{ route('almacenes.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-warehouse mr-2 text-amber-500"></i> Almacenes
                                            </a>
                                            <a href="{{ route('productos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-box mr-2 text-amber-500"></i> Productos
                                            </a>
                                            <a href="{{ route('categorias.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-tags mr-2 text-amber-500"></i> Categorías
                                            </a>
                                            <a href="{{ route('compras.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-shopping-basket mr-2 text-amber-500"></i> Compras
                                            </a>
                                            <a href="{{ route('proveedores.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-truck mr-2 text-amber-500"></i> Proveedores
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            {{-- RRHH --}}
                            @can('view_rrhh')
                                <a href="{{ route('empleados.index') }}"
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                   {{ request()->is('rrhh*') ? 'bg-purple-50 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-users mr-1.5 text-xs"></i> RRHH
                                </a>
                            @endcan

                            {{-- PRODUCCIÓN --}}
                            @can('view_produccion')
                                <div class="relative" x-data="{ openProduccion: false }">
                                    <button @click="openProduccion = !openProduccion"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                                   {{ request()->is('produccion*') ? 'bg-sky-50 text-sky-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-industry mr-1.5 text-xs"></i> Producción
                                        <i class="fas fa-chevron-down ml-1.5 text-xs"></i>
                                    </button>

                                    <div x-show="openProduccion" @click.away="openProduccion = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         style="display: none;">
                                        <div class="py-2 px-2">
                                            <a href="{{ route('proyectos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-sky-50 rounded-lg transition-colors">
                                                <i class="fas fa-project-diagram mr-2 text-sky-500"></i> Proyectos
                                            </a>
                                            <a href="{{ route('asignaciones.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-sky-50 rounded-lg transition-colors">
                                                <i class="fas fa-user-check mr-2 text-sky-500"></i> Asignaciones
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        @endauth

                    </div>
                </div>

                {{-- Usuario y Cerrar Sesión --}}
                <div class="flex items-center space-x-3">
                    @auth
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-gray-700 leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-blue-600 font-medium">{{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}</div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 text-sm font-medium">
                                <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline ml-1">Salir</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        {{-- Header opcional --}}
        @if (isset($header))
            <div class="mb-6">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    {{ $header }}
                </div>
            </div>
        @endif

        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: @json(session('success')),
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        showClass: { popup: 'animate__animated animate__fadeInRight' },
                        hideClass: { popup: 'animate__animated animate__fadeOutRight' }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: @json(session('error')),
                        timer: 4000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        showClass: { popup: 'animate__animated animate__fadeInRight' },
                        hideClass: { popup: 'animate__animated animate__fadeOutRight' }
                    });
                @endif
            });
        </script>

        {{-- Contenido de la vista --}}
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

</body>
</html>