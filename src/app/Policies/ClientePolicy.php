<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cliente;

class ClientePolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasRole('Ventas');
    }

    public function view(User $user, Cliente $cliente)
    {
        return $user->hasRole('Ventas');
    }

    public function create(User $user)
    {
        return $user->hasRole('Ventas');
    }

    public function update(User $user, Cliente $cliente)
    {
        return $user->hasRole('Ventas');
    }

    public function delete(User $user, Cliente $cliente)
    {
        return $user->hasRole('Ventas');
    }
}
