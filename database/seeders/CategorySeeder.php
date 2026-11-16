<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Perangkat dan gawai elektronik modern untuk produktivitas dan hiburan.',
            ],
            [
                'name' => 'Pakaian & Mode',
                'description' => 'Koleksi pakaian pria dan wanita dengan bahan berkualitas dan desain terkini.',
            ],
            [
                'name' => 'Makanan & Minuman',
                'description' => 'Produk kuliner segar, camilan lezat, dan aneka minuman bernutrisi.',
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'description' => 'Buku bacaan edukatif, novel inspiratif, dan perlengkapan kantor.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
