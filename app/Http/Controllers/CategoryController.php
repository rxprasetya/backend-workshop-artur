<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(): Response
    {
        $categories = Category::query()->orderBy('name')->get();

        return Inertia::render('categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): Response
    {
        return Inertia::render('categories/Show', [
            'category' => $category->only(
                'id', 'name', 'description', 'created_at'
            ),
        ]);
    }
}
