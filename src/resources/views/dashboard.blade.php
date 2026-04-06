@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            @auth
                <h2 class="text-2xl font-bold mb-2">
                    Bienvenido, {{ auth()->user()->name }} 👋
                </h2>

                <p class="mb-6">
                    Rol:
                    <strong class="text-blue-600">
                        {{ auth()->user()->getRoleNames()->first() ?? 'Sin rol asignado' }}
                    </strong>
                </p>
            @endauth

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- CONTABILIDAD --}}
                @isset($stats['total_asientos'])
                <div class="bg-blue-100 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <div class="flex items-center">
                        <i class="fas fa-book text-3xl text-blue-600"></i>
                        <div class="ml-4">
                            <h3 class="text-sm text-gray-600">Asientos</h3>
                            <p class="text-2xl font-bold">{{ number_format($stats['total_asientos']) }}</p>
                        </div>
                    </div>
                </div>
                @endisset

                {{-- VENTAS --}}
                @isset($stats['total_ventas'])
                <div class="bg-green-100 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-3xl text-green-600"></i>
                        <div class="ml-4">
                            <h3 class="text-sm text-gray-600">Ventas</h3>
                            <p class="text-2xl font-bold">S/ {{ number_format($stats['total_ventas'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endisset

                {{-- PAGOS --}}
                @isset($stats['total_pagos'])
                <div class="bg-indigo-100 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <div class="flex items-center">
                        <i class="fas fa-credit-card text-3xl text-indigo-600"></i>
                        <div class="ml-4">
                            <h3 class="text-sm text-gray-600">Pagos</h3>
                            <p class="text-2xl font-bold">S/ {{ number_format($stats['total_pagos'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endisset

                {{-- INVENTARIO --}}
                @isset($stats['total_productos'])
                <div class="bg-purple-100 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <div class="flex items-center">
                        <i class="fas fa-boxes text-3xl text-purple-600"></i>
                        <div class="ml-4">
                            <h3 class="text-sm text-gray-600">Productos</h3>
                            <p class="text-2xl font-bold">{{ number_format($stats['total_productos']) }}</p>

                            @if(($stats['stock_bajo'] ?? 0) > 0)
                                <p class="text-xs text-red-600">
                                    ⚠ {{ $stats['stock_bajo'] }} con stock bajo
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endisset

                {{-- RRHH --}}
                @isset($stats['total_empleados'])
                <div class="bg-yellow-100 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <div class="flex items-center">
                        <i class="fas fa-users text-3xl text-yellow-600"></i>
                        <div class="ml-4">
                            <h3 class="text-sm text-gray-600">Empleados</h3>
                            <p class="text-2xl font-bold">{{ number_format($stats['total_empleados']) }}</p>
                        </div>
                    </div>
                </div>
                @endisset

            </div>

        </div>
    </div>
</div>
@endsection