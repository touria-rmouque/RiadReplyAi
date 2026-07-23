<?php

namespace App\Actions\Establishment;

use App\Models\User;

class SaveEstablishmentAction
{
    public function execute(User $user, array $data): void
    {
        $user->establishment()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            $data
        );
    }
}