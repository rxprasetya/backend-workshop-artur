<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_page_can_be_rendered(): void
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
}
