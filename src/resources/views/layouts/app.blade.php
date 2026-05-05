<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP System - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

    <nav class="bg-white shadow-lg" x-data="{ openContabilidad: false, openAdmin: false, openInventario: false }">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">

                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">ERP System</h1>
                    </div>

                    <div class="hidden md:ml-6 md:flex md:space-x-4 items-center">

                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium
                           {{ request()->routeIs('dashboard') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                            Dashboard
                        </a>

                        @auth
                            {{-- 🔐 ADMINISTRACIÓN (Solo Super Admin) --}}
                            @role('Super Admin')
                                <div class="relative">
                                    <button @click="openAdmin = !openAdmin"
                                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none {{ request()->is('admin*') ? 'bg-red-50' : '' }}">
                                        <span>Administración</span>
                                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                    </button>

                                    <div x-show="openAdmin" @click.away="openAdmin = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                         x-transition>
                                        <div class="py-1 px-2">
                                            <span class="block px-3 py-1 text-xs font-bold text-gray-400 uppercase">Sistema</span>
                                            <a href="{{ route('admin.users') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                                👤 Gestión de Usuarios
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endrole

                            {{-- 🔵 CONTABILIDAD --}}
                            @role('Super Admin|Contador')
                                <div class="relative">
                                    <button @click="openContabilidad = !openContabilidad"
                                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none {{ request()->is('contabilidad*') ? 'bg-gray-100' : '' }}">
                                        <span>Contabilidad</span>
                                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                    </button>

                                    <div x-show="openContabilidad" @click.away="openContabilidad = false"
                                         class="absolute z-50 left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                         x-transition>
                                        <div class="py-1 px-2">
                                            <span class="block px-3 py-1 text-xs font-bold text-gray-400 uppercase">Gestión</span>
                                            <a href="{{ route('contabilidad.plan_cuentas') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md">📘 Plan de Cuentas</a>
                                            <a href="{{ route('asientos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md">📄 Asientos Contables</a>

                                            <div class="border-t border-gray-100 my-1"></div>

                                            <span class="block px-3 py-1 text-xs font-bold text-gray-400 uppercase">Reportes</span>
                                            <a href="{{ route('contabilidad.libro_mayor') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md">📊 Libro Mayor</a>
                                            <a href="{{ route('contabilidad.estado_resultados') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md">📈 Estado de Resultados</a>
                                            <a href="{{ route('contabilidad.balance_general') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md">📉 Balance General</a>
                                        </div>
                                    </div>
                                </div>
                            @endrole

                            @can('view_ventas')
                                <a href="{{ route('clientes.index') }}"
                                   class="px-3 py-2 rounded-md text-sm font-medium
                                   {{ request()->is('ventas*') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Ventas
                                </a>
                            @endcan

                            {{-- 🟢 MÓDULO DE INVENTARIO --}}
                                @can('view_inventario')
                                    <div class="relative">
                                        {{-- Botón Principal --}}
                                        <button @click="openInventario = !openInventario"
                                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none {{ request()->is('inventario*') ? 'bg-gray-100' : '' }}">
                                            <span>Inventario</span>
                                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                        </button>

                                        {{-- Menú Desplegable --}}
                                        <div x-show="openInventario" 
                                            @click.away="openInventario = false"
                                            class="absolute z-50 left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            style="display: none;" {{-- Evita parpadeo al cargar --}}
                                            x-transition>
                                            <div class="py-1 px-2">
                                                <span class="block px-3 py-1 text-xs font-bold text-gray-400 uppercase">Gestión</span>
                                                
                                                {{-- Enlace a Almacenes --}}
                                                <a href="{{ route('almacenes.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 rounded-md transition">
                                                    <i class="fas fa-warehouse mr-2 text-green-600"></i> Almacenes
                                                </a>

                                                {{-- Enlace a Productos --}}
                                                <a href="{{ route('productos.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 rounded-md transition">
                                                    <i class="fas fa-boxes mr-2 text-green-600"></i> Productos
                                                </a>

                                                {{-- Enlace a Categorías --}}
                                                <a href="{{ route('categorias.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 rounded-md transition">
                                                    <i class="fas fa-tags mr-2 text-green-600"></i> Categorías
                                                </a>

                                                {{-- Enlace a Inventarios --}}
                                                <a href="{{ route('inventarios.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 rounded-md transition">
                                                    <i class="fas fa-clipboard-list mr-2 text-green-600"></i> Inventarios
                                                </a>

                                               

                                                <div class="border-t border-gray-100 my-1"></div>

                                                {{-- Enlace a Proveedores --}}
                                                <a href="{{ route('proveedores.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 rounded-md transition">
                                                    <i class="fas fa-truck mr-2 text-green-600"></i> Proveedores
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endcan

                            @can('view_rrhh')
                                <a href="{{ route('empleados.index') }}"
                                   class="px-3 py-2 rounded-md text-sm font-medium
                                   {{ request()->is('rrhh*') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                                    RRHH
                                </a>
                            @endcan

                            @can('view_produccion')
                                <a href="{{ route('proyectos.index') }}"
                                   class="px-3 py-2 rounded-md text-sm font-medium
                                   {{ request()->is('produccion*') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Producción
                                </a>
                                <a href="{{ route('asignaciones.index') }}"
                                   class="px-3 py-2 rounded-md text-sm font-medium
                                   {{ request()->is('asiganacion*') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Asignaciones
                                </a>
                            @endcan
                        @endauth

                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-bold text-gray-700 leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-blue-600">{{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}</div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-50 text-red-600 px-3 py-2 rounded-md hover:bg-red-100 transition text-sm font-semibold">
                                <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Salir</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
