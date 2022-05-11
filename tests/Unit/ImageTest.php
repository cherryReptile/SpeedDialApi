<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Dial;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Category $category;
    protected Dial $dial;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = $this->user->category()->create([
            'name' => $this->faker->sentence
        ]);
        $this->dial = $this->category->dial()->create([
            'url' => $this->faker->url,
            'title' => $this->faker->sentence,
            'description' => $this->faker->text,
            'active' => $this->faker->boolean
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_image()
    {
        $image = $this->dial->images()->create([
            'img_source' => $this->faker->url
        ]);
        $this->assertEquals(1, Image::count());
        $this->assertIsObject($image);
    }

    public function test_get_image()
    {
        $this->test_create_image();
        $getImage = $this->dial->images()->firstOrFail();
        $this->assertIsObject($getImage);
    }
}
