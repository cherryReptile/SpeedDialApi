<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

}
