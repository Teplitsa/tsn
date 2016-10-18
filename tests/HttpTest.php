<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HttpTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */

    public function is_tenant_get_home_page()
    {
        $user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($user);

        $response = $this->call('GET', '/home');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function is_tenant_get_flats_page()
    {
        $user = factory(\App\Models\User::class, 'tenant')->create();
        $this->actingAs($user);

        $response = $this->call('GET', '/flats/attach');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function is_get_manager_home_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $response = $this->call('GET', '/home');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function is_manager_get_house_page()
    {
        $company = factory(\App\Company::class)->create();
        $user = factory(\App\Models\User::class, 'manager')->create();
        $this->actingAs($user);

        $response = $this->call('GET', '/houses/create');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
