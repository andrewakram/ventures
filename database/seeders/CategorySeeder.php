<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Category1',
            ],
            [
                'name' => 'Category2',
            ],
            [
                'name' => 'Category3',
            ],
        ];
        foreach ($data as $get) {
            Category::updateOrCreate($get);
        }
    }
}
