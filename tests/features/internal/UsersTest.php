<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeaturesInternalUsersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_loads_correct_info()
    {
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $this->json('get', '/internal-api/user')
            ->seeJson([
                'id'        => $user->id,
                'fullName'  => $user->full_name,
                'email'     => $user->email,
                'duty'      => $user->duty,
                'avatarUrl' => $user->avatar_url,
            ]);
    }

    public function test_empty_on_unauth_user()
    {
        $this->json('get', '/internal-api/user')
            ->seeJson(['data' => []]);
    }
}
