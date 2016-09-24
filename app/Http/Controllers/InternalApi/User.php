<?php

namespace App\Http\Controllers\InternalApi;

use App\Transformers\User\UserBlock;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class User extends Controller
{
    public function me(Request $request)
    {
        if (null === ($user = $request->user())) {
            return [];
        } else {
            return fractal()
                ->item($request->user())
                ->transformWith(new UserBlock())
                ->toArray();
        }
    }

    public function getAvatar($email = '')
    {
        return [
            'data' => [
                'gravatar' => \Gravatar::src($email ?: 'guest', 200)
            ],
        ];
    }
}
