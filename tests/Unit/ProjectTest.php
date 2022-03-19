<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Dial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function base_eloquent(): Dial
    {
        $user = User::create(User::factory()->test_definition());
        $category = $user->category()->create(Category::factory()->definition());
        return $category->dial()->create(Dial::factory()->definition());
    }
    /**
     *
     * @return void
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function test_update_url_info()
    {
        $this->assertTrue($this->base_eloquent()->updateUrlInfo('https://youtube.com'));
        $this->assertTrue($this->base_eloquent()->updateUrlInfo('https://losdvksdvmsk.ru')); // Invalid HTML
    }

    public function test_update_title_or_description()
    {
        $this->assertTrue($this->base_eloquent()->updateTitleOrDescription());
    }
}
