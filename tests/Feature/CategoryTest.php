<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Facade;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\Exception;
use PHPUnit\Runner\Filter\Factory;
use Tests\TestCase;

/**
 * TODO: Нет удаления категории
 */
class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     * TODO: Не проверяется наличие созданной категории в бд
     * @return string
     */

    /**
     * @throws \Throwable
     */
    public function test_category_create(): string
    {
        $userTests = new AuthTest();
        $userTests->setUp();

        $token = $userTests->test_register();

        $data = [
            'name' => $this->faker->title
        ];
        $response = $this->postJson('api/category', $data, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([]);
        $this->assertEquals(1, Category::count());

        return $token;
    }

    /**
     * @throws \Throwable
     */
    public function test_validation_of_create()
    {
        $response = $this->postJson('api/category', [
            'Authorization' => "Bearer {$this->test_category_create()}"
        ]);
        $response
            ->assertStatus(422)
            ->assertInvalid(['name']);
    }

    /**
     * @throws \Throwable
     */
    public function test_category_show()
    {
        $token = $this->test_category_create();
        $category = Category::latest()->firstOrFail();
        $response = $this->getJson('api/category/' . $category->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('id', $category->id)
                    ->where('name', $category->name)
                    ->where('user_id', $category->user_id)
                    ->etc()
            );
    }

    /**
     * @throws \Throwable
     */
    public function test_category_all()
    {
        $token = $this->test_category_create();
        $categories = Category::whereUserId(User::latest()->firstOrFail()->id)->get();
        $response = $this->getJson('api/category/', [
            'Authorization' => "Bearer $token"
        ]);
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

    /**
     * @throws \Throwable
     */
    public function test_category_update()
    {
        $token = $this->test_category_create();
        $category = Category::latest()->firstOrFail();
        $response = $this->patchJson('api/category/' . $category->id, [
            'name' => $this->faker->title
        ], [
            'Authorization' => "Bearer $token"
        ]);
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

    /**
     * @throws \Throwable
     */
    public function test_validation_of_update()
    {
        $token = $this->test_category_create();
        $response = $this->patchJson('api/category/' . Category::latest()->firstOrFail()->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(422)
            ->assertInvalid(['name']);
    }

    /**
     * @throws \Throwable
     */
    public function test_category_delete()
    {
        $token = $this->test_category_create();
        $category = Category::latest()->firstOrFail();
        $response = $this->deleteJson('api/category/' . $category->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(204);
        $this->assertEquals(0, Category::count());
    }
}
