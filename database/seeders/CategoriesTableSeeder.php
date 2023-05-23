<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Log::info("Categories seeding finished...");

        $categories = [
            'business',
            'entertainment',
            'general',
            'health',
            'science',
            'sports',
            'technology'
        ];

        collect($categories)->each(function ($category) {
            Category::updateOrCreate([
                'name' => $category
            ]);
        });

        Log::info("Categories seeding finished...");
    }
}
