@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="mb-8">
        @auth
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="breadcrumb">Inicio</span>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        Bienvenido, {{ auth()->user()->name }} 👋
                    </h1>
                    <p class="text-gray-500 mt-1">
                        Rol: 
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ auth()->user()->getRoleNames()->first() ?? 'Sin rol asignado' }}
                        </span>
                    </p>
                </div>
                <div class="text-sm text-gray-400">
                    <i class="far fa-calendar-alt mr-1"></i> {{ date('d/m/Y') }}
                </div>
            </div>
        @endauth
    </div>

    {{-- GRID DE TARJETAS PRINCIPALES --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        {{-- CONTABILIDAD --}}
        @isset($stats['total_asientos'])
        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group cursor-pointer"
             onclick="window.location='{{ route('asientos.index') }}'">
            {{-- Fondo decorativo --}}
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="relative flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Contabilidad</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 font-mono">
                        {{ number_format($stats['total_asientos']) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Asientos registrados</p>
                </div>
            </div>
            
            {{-- Enlace --}}
            <div class="mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    Ver asientos <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </div>
        @endisset

        {{-- VENTAS --}}
        @isset($stats['total_ventas'])
        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group cursor-pointer"
             onclick="window.location='{{ route('facturas.index') }}'">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="relative flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-emerald-600"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Ventas</span>
                    </div>
                    <div class="text-3xl font-bold text-emerald-700 font-mono">
                        S/ {{ number_format($stats['total_ventas'], 2) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Total facturado</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-emerald-600 hover:text-emerald-800 font-medium flex items-center gap-1">
                    Ver facturas <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </div>
        @endisset

        {{-- PAGOS --}}
        @isset($stats['total_pagos'])
        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group cursor-pointer"
             onclick="window.location='{{ route('pagos.index') }}'">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="relative flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-credit-card text-indigo-600"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Cobranzas</span>
                    </div>
                    <div class="text-3xl font-bold text-indigo-700 font-mono">
                        S/ {{ number_format($stats['total_pagos'], 2) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Pagos recibidos</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                    Ver pagos <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </div>
        @endisset

        {{-- INVENTARIO --}}
        @isset($stats['total_productos'])
        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group cursor-pointer"
             onclick="window.location='{{ route('productos.index') }}'">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="relative flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-boxes text-purple-600"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Inventario</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 font-mono">
                        {{ number_format($stats['total_productos']) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Productos registrados</p>
                    
                    @if(($stats['stock_bajo'] ?? 0) > 0)
                        <div class="mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-xs text-red-600 font-medium">
                                {{ $stats['stock_bajo'] }} con stock bajo
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-3">
                <a href="{{ route('productos.index') }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1">
                    Productos <i class="fas fa-arrow-right text-xs"></i>
                </a>
                @if(($stats['stock_bajo'] ?? 0) > 0)
                    <a href="{{ route('inventario.dashboard') }}" class="text-xs text-red-500 hover:text-red-700 font-medium">
                        Ver alertas
                    </a>
                @endif
            </div>
        </div>
        @endisset

        {{-- RRHH --}}
        @isset($stats['total_empleados'])
        <div class="relative overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group cursor-pointer"
             onclick="window.location='{{ route('empleados.index') }}'">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="relative flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-amber-600"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">RRHH</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 font-mono">
                        {{ number_format($stats['total_empleados']) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Empleados activos</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-amber-600 hover:text-amber-800 font-medium flex items-center gap-1">
                    Ver empleados <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </div>
        @endisset

    </div>

    {{-- ACCESOS RÁPIDOS --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-1 h-4 bg-blue-500 rounded-full"></span>
            Accesos Rápidos
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @role('Super Admin|Contador')
                <a href="{{ route('asientos.create') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">📝</span>
                    <span class="text-xs text-gray-600 font-medium">Nuevo Asiento</span>
                </a>
                <a href="{{ route('contabilidad.balance_general') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">🏦</span>
                    <span class="text-xs text-gray-600 font-medium">Balance General</span>
                </a>
                <a href="{{ route('contabilidad.estado_resultados') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">📈</span>
                    <span class="text-xs text-gray-600 font-medium">Edo. Resultados</span>
                </a>
            @endrole
            @can('view_ventas')
                <a href="{{ route('facturas.create') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-emerald-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">🧾</span>
                    <span class="text-xs text-gray-600 font-medium">Nueva Factura</span>
                </a>
                <a href="{{ route('clientes.index') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-emerald-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">👥</span>
                    <span class="text-xs text-gray-600 font-medium">Clientes</span>
                </a>
            @endcan
            @can('view_inventario')
                <a href="{{ route('productos.create') }}" class="flex flex-col items-center p-3 rounded-xl hover:bg-purple-50 transition-colors duration-200 text-center">
                    <span class="text-2xl mb-1">📦</span>
                    <span class="text-xs text-gray-600 font-medium">Nuevo Producto</span>
                </a>
            @endcan
        </div>
    </div>

    {{-- ESTADO DEL SISTEMA --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
            Estado del Sistema
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                <span class="text-gray-600">Módulo Contable: <strong class="text-emerald-600">Activo</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                <span class="text-gray-600">Módulo Ventas: <strong class="text-emerald-600">Activo</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                <span class="text-gray-600">Módulo Inventario: <strong class="text-emerald-600">Activo</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                <span class="text-gray-600">Módulo RRHH: <strong class="text-emerald-600">Activo</strong></span>
            </div>
        </div>
    </div>

    {{-- PIE --}}
    <div class="mt-6 text-center text-xs text-gray-400">
        <span>ERP Sistema de Gestión Empresarial v1.0 | {{ date('Y') }}</span>
    </div>

</div>
@endsection