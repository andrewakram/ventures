<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::get();

        foreach ($categories as $category) {
            for ($i=1 ;$i < 4 ;$i++){
                SubCategory::updateOrCreate(
                    [
                        'name' => 'Sub'.$i.'_Category'.$category->id,
                        'category_id' => $category->id,
                    ]
                );
            }

        }
    }
}
