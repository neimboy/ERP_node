<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-4 md:p-8">

<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-blue-600 flex items-center">
            <i class="fas fa-users-cog mr-3"></i> Gestión de Usuarios
        </h1>

        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow transition ease-in-out duration-150">
            <span class="mr-2">🏠</span>
            <span class="font-semibold">Volver al Dashboard</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="p-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                    <th class="p-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="p-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Rol Actual</th>
                    <th class="p-3 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">Asignar Rol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">

            @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 text-sm text-gray-700 font-medium">
                        {{ $user->name }}
                    </td>
                    <td class="p-3 text-sm text-gray-600">
                        {{ $user->email }}
                    </td>
                    <td class="p-3 text-sm">
                        @if($user->roles->isEmpty())
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">Sin rol</span>
                        @else
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold mr-1">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        @endif
                    </td>

                    <td class="p-3">
                        <form method="POST" action="{{ route('admin.users.assignRole', $user->id) }}">
                            @csrf
                            <div class="flex justify-center gap-2">
                                <select name="role" class="border border-gray-300 text-sm p-1.5 rounded focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow transition">
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

</div>

</body>
</html>