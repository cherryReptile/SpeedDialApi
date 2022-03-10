<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create()
    {
        Sanctum::actingAs(User::latest()->firstOrFail());
        $data = Category::factory()->definition();
        $response = $this->postJson('api/category', $data);
        $response
            ->assertStatus(201)
            ->assertJson([]);
    }

    public function test_show()
    {
        Sanctum::actingAs(User::latest()->firstOrFail());
        $category = Category::latest()->firstOrFail();
        $response = $this->getJson('api/category/' . $category->id);
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('id', $category->id)
                ->where('name', $category->name)
                ->where('user_id', $category->user_id)
                ->etc());
    }

    public function test_all()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $categories = Category::whereUserId($user->id)->get();
        $response = $this->getJson('api/category/');
        $response->assertStatus(200);
        foreach ($categories as $category) {
            $response->assertJson(fn(AssertableJson $json) => $json
                ->has(count($categories))
                ->first(fn($json) => $json
                    ->where('id', $category->id)
                    ->where('name', $category->name)
                    ->where('user_id', $category->user_id)
                    ->etc()
                )
            );
        }
    }

    public function test_update()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $category = Category::latest()->firstOrFail();
        $response = $this->patchJson('api/category/' . $category->id, Category::factory()->definition());
        $categoryAfterUpdate = Category::latest()->firstOrFail();
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('id', $categoryAfterUpdate->id)
                ->where('name', $categoryAfterUpdate->name)
                ->where('user_id', $categoryAfterUpdate->user_id)
                ->etc()
            );
    }

    public function test_delete()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $category = Category::latest()->firstOrFail();
        $response = $this->deleteJson('api/category/' . $category->id);
        $response
            ->assertStatus(204);
    }
}
