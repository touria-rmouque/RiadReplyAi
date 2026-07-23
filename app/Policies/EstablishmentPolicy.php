<?php

namespace App\Policies;

use App\Models\Establishment;
use App\Models\User;

class EstablishmentPolicy
{
    public function view(User $user, Establishment $establishment): bool
    {
        return $establishment->user_id === $user->id;
    }

    public function update(User $user, Establishment $establishment): bool
    {
        return $establishment->user_id === $user->id;
    }

    public function delete(User $user, Establishment $establishment): bool
    {
        return $establishment->user_id === $user->id;
    }
}