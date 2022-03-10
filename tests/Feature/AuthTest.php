<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $data = User::factory()->test_definition();
        $response = $this->postJson('api/auth/register', $data);
        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);
    }

    public function test_logout()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $response = $this->postJson('api/logout');
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'logged out'
            ]);
    }

    public function test_login()
    {
        $user = User::latest()->firstOrFail();
        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' =>  'password'
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);
    }

}
