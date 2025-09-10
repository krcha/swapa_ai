<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'description' => 'Mobile phones and smartphones',
                'sort_order' => 1,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Phone accessories and peripherals',
                'sort_order' => 2,
            ],
            [
                'name' => 'Tablets',
                'slug' => 'tablets',
                'description' => 'Tablets and iPads',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create subcategories for accessories
        $accessories = Category::where('slug', 'accessories')->first();
        
        $subcategories = [
            [
                'name' => 'Headphones',
                'slug' => 'headphones',
                'description' => 'Headphones and earbuds',
                'parent_id' => $accessories->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Chargers',
                'slug' => 'chargers',
                'description' => 'Charging cables and adapters',
                'parent_id' => $accessories->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'Cases',
                'slug' => 'cases',
                'description' => 'Phone cases and protection',
                'parent_id' => $accessories->id,
                'sort_order' => 3,
            ],
        ];

        foreach ($subcategories as $subcategory) {
            Category::create($subcategory);
        }
    }
}
