<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News as ModelsNews;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class News extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();

        foreach ($categories as $category) {
            foreach ($subcategories as $subcategory) {
                for ($i = 1; $i <= 5; $i++) {
                    ModelsNews::create([
                        'title' => "News Title $i",
                        'content' => "News Content $i",
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'start_date' => now(),
                        'end_date' => now(),
                    ]);
                }
            }
        }
    }
}