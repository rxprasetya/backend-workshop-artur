<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_index_page_can_be_rendered(): void
    {
        $this->seed(CategorySeeder::class);

        $response = $this->get(route('categories.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('categories/Index')
            ->has('categories', 4)
            ->where('categories.0.name', 'Buku & Alat Tulis')
        );
    }

    public function test_category_show_page_can_be_rendered(): void
    {
        $category = Category::create([
            'name' => 'Elektronik Khusus',
            'description' => 'Deskripsi pengujian',
        ]);

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('categories/Show')
            ->has('category')
            ->where('category.id', $category->id)
            ->where('category.name', 'Elektronik Khusus')
            ->where('category.description', 'Deskripsi pengujian')
        );
    }

    public function test_category_show_returns_404_for_random_uuid(): void
    {
        $randomUuid = (string) Str::uuid();

        $response = $this->get(route('categories.show', $randomUuid));

        $response->assertNotFound();
    }
}
