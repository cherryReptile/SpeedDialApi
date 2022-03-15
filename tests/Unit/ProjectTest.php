<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Dial;
use App\Models\User;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_update_url_info()
    {
        $user = User::create(User::factory()->test_definition());
        $category = $user->category()->create(Category::factory()->definition());
        $dial = $category->dial()->create(Dial::factory()->definition());
        $this->assertTrue($dial->updateUrlInfo('https://youtube.com'));
        $this->assertTrue($dial->updateUrlInfo('https://losdvksdvmsk.ru')); // Invalid HTML
    }

    public function test_update_title_or_description()
    {
        $dial = Dial::latest()->firstOrFail();
        $this->assertTrue($dial->updateTitleOrDescription());
    }
}
