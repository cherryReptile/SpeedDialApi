<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DialTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_dial_create()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $category = Category::latest()->firstOrFail();
        $response = $this->postJson('api/category/' . $category->id . '/dial', Dial::factory()->definition());
        $response
            ->assertStatus(201)
            ->assertJson([]);
    }

    public function test_dial_show()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $dial = Dial::latest()->firstOrFail();
        $response = $this->getJson('api/dial/' . $dial->id);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $dial->id,
                'title' => $dial->title,
                'description' => $dial->description,
                'category_id' => $dial->category_id,
                'active' => $dial->active,
                'created_at' => $dial->created_at,
                'updated_at' => $dial->updated_at
            ]);
    }

    public function test_dial_all()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $dials = $user->dialThroughUser()->get();
        $response = $this->getJson('api/dial')->assertStatus(200);
        foreach ($dials as $dial) {
            $response
                ->assertJson(fn(AssertableJson $json) => $json
                    ->has(count($dials))
                    ->first(fn($json) => $json
                        ->where('id', $dial->id)
                        ->where('title', $dial->title)
                        ->where('description', $dial->description)
                        ->where('category_id', $dial->category_id)
                        ->etc()
                    )
                );
        }
    }

    public function test_dial_update()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $dial = Dial::latest()->firstOrFail();
        $response = $this->patchJson('api/dial/' . $dial->id, Dial::factory()->definition());
        $dialAfterUpdate = Dial::latest()->firstOrFail();
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $dialAfterUpdate->id,
                'title' => $dialAfterUpdate->title,
                'description' => $dialAfterUpdate->description,
                'category_id' => $dialAfterUpdate->category_id,
                'active' => $dialAfterUpdate->active,
                'created_at' => $dialAfterUpdate->created_at,
                'updated_at' => $dialAfterUpdate->updated_at
            ]);
    }

    public function test_dial_delete()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $dial = Dial::latest()->firstOrFail();
        $response = $this->deleteJson('api/dial/' . $dial->id);
        $response
            ->assertStatus(204);
    }

    public function test_category_delete()
    {
        $user = User::latest()->firstOrFail();
        Sanctum::actingAs($user);
        $category = Category::latest()->firstOrFail();
        $response = $this->deleteJson('api/category/' . $category->id);
        $response
            ->assertStatus(204);
    }
}
