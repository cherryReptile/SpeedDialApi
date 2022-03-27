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
    use RefreshDatabase;
    use WithFaker;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->has(
            Category::factory(10)
        )->create();

        $this->token = User::latest()->firstOrFail()->createToken('test')->plainTextToken;
    }

    /**
     * A basic feature test example.
     * TODO: Не проверяешь физическое наличие в бд
     * @return void
     * @throws \Throwable
     */
    public function test_dial_create()
    {
        $token = $this->token;

        $data = [
            'url' => 'https://youtube.com'
        ];
        $category = Category::latest()->firstOrFail();
        $response = $this->postJson('api/category/' . $category->id . '/dial', $data, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([]);
        $this->assertEquals(1, Dial::count());
    }

    /**
     * @throws \Throwable
     */
    public function test_validation_of_create()
    {
        $token = $this->token;
        $response = $this->postJson('api/category/' . Category::latest()->firstOrFail()->id . '/dial', [], [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(422)
            ->assertInvalid(['url']);
    }

    /**
     * @throws \Throwable
     */
    public function test_dial_show()
    {
        $token = $this->token;
        $this->test_dial_create();

        $dial = Dial::latest()->firstOrFail();
        $response = $this->getJson('api/dial/' . $dial->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $dial->id,
                'url' => $dial->url,
                'img_source' => $dial->img_source,
                'title' => $dial->title,
                'description' => $dial->description,
                'category_id' => $dial->category_id,
                'active' => $dial->active,
                'created_at' => $dial->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $dial->updated_at->format('Y-m-d H:i:s')
            ]);
    }

    /**
     * @throws \Throwable
     */
    public function test_dial_all()
    {
        $token = $this->token;
        $this->test_dial_create();

        $user = User::latest()->firstOrFail();
        $dials = $user->dialThroughUser()->get();
        $response = $this->getJson('api/dial', [
            'Authorization' => "Bearer $token"
        ])->assertStatus(200);
        foreach ($dials as $dial) {
            $response
                ->assertJson(fn(AssertableJson $json) => $json
                    ->has(count($dials))
                    ->first(fn($json) => $json
                        ->where('id', $dial->id)
                        ->where('url', $dial->url)
                        ->where('img_source', $dial->img_source)
                        ->where('title', $dial->title)
                        ->where('description', $dial->description)
                        ->where('category_id', $dial->category_id)
                        ->etc()
                    )
                );
        }
    }

    /**
     * @throws \Throwable
     */
    public function test_dial_update()
    {
        $token = $this->token;
        $this->test_dial_create();

        $dial = Dial::latest()->firstOrFail();
        $data = [
            'url' => $this->faker->url
        ];
        $response = $this->patchJson('api/dial/' . $dial->id, $data, [
            'Authorization' => "Bearer $token"
        ]);
        $dialAfterUpdate = Dial::latest()->firstOrFail();
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $dialAfterUpdate->id,
                'url' => $dialAfterUpdate->url,
                'img_source' => $dialAfterUpdate->img_source,
                'title' => $dialAfterUpdate->title,
                'description' => $dialAfterUpdate->description,
                'category_id' => $dialAfterUpdate->category_id,
                'active' => $dialAfterUpdate->active,
                'created_at' => $dialAfterUpdate->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $dialAfterUpdate->updated_at->format('Y-m-d H:i:s')
            ]);
    }

    /**
     * @throws \Throwable
     */
    public function test_validation_of_update()
    {
        $token = $this->token;
        $this->test_dial_create();

        $response = $this->patchJson('api/dial/' . Dial::latest()->firstOrFail()->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(422)
            ->assertInvalid(['url']);
    }

    /**
     * TODO: НЕ проверяешь удалилось ли в бд
     * @return void
     * @throws \Throwable
     */
    public function test_dial_delete()
    {
        $token = $this->token;
        $this->test_dial_create();

        $dial = Dial::latest()->firstOrFail();
        $response = $this->deleteJson('api/dial/' . $dial->id, [
            'Authorization' => "Bearer $token"
        ]);
        $response
            ->assertStatus(204);
        $this->assertEquals(0, Dial::count());
    }

}
