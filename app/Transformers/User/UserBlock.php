<?php

namespace App\Transformers\User;

use App\Models\User;

class UserBlock
{
    public function transform(User $user)
    {
        return [
            'fullName' => $user->full_name,
            'id' => $user->id,
            'email' => $user->email,
            'duty' => $user->role_name,
            'avatarUrl' => $user->avatar_url
        ];
    }
}
