<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

/**
 * TODO: Нет бэд - тестов на валидацию
 */
class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected string $password;


    protected function setUp(): void
    {
        parent::setUp();

        $this->password = 'tipira21';
    }

    /**
     * A basic feature test example.
     *
     * @return string
     */
    public function test_register(): string
    {
        $data = [
            'name' => $this->faker->firstName,
            'email' => $this->faker->email,
            'password' => $this->password
        ];

        $response = $this->postJson('api/auth/register', $data);
        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);

        $this->assertEquals(1, User::count()); //Check database row created
        $this->securityRequest($response->json('token')); //Check work generated token for api

        return $response->json('token');
    }

    public function test_validation_of_create()
    {
        $response = $this->postJson('api/auth/register');
        $response
            ->assertStatus(422)
            ->assertInvalid(['name', 'email', 'password']);
    }

    public function test_logout()
    {
        $token = $this->test_register();

        $response = $this->postJson('api/logout', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'logged out'
            ]);
    }

    public function securityRequest(string $token)
    {
        $response = $this->get('api/user', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_login()
    {
        $this->test_register();
        $user = User::latest()->firstOrFail();

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => $this->password
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);
    }

}
