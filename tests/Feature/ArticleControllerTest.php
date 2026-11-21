<?php

namespace Tests\Feature;

use App\Models\Article;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_page_displays_articles_from_the_database(): void
    {
        Article::factory()->create([
            'title' => 'Важная новость',
            'content' => 'Текст новости для списка.',
            'published_at' => now(),
        ]);

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertViewIs('articles.index')
            ->assertSee('Важная новость')
            ->assertSee('Текст новости для списка.');
    }

    public function test_news_page_paginates_articles(): void
    {
        foreach (range(1, 11) as $day) {
            Article::factory()->create([
                'title' => "Новость {$day}",
                'published_at' => now()->subDays($day),
            ]);
        }

        $this->get(route('articles.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Новость 11')
            ->assertDontSee('Новость 10')
            ->assertSee('Страница')
            ->assertSee('из 2')
            ->assertSee('Показаны записи 11–11')
            ->assertSee('из 11')
            ->assertDontSee('Showing 11 to 20 of 20 results');
    }

    public function test_database_seeder_creates_fake_news_articles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('articles', 20);
    }
}
