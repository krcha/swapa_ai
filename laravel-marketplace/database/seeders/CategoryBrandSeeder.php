<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;

class CategoryBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Mobilni telefoni',
                'slug' => 'mobilni-telefoni',
                'description' => 'Pametni telefoni i mobilni uređaji',
                'is_active' => true,
            ],
            [
                'name' => 'Tableti',
                'slug' => 'tableti',
                'description' => 'iPad, Samsung Tab i drugi tableti',
                'is_active' => true,
            ],
            [
                'name' => 'Dodatci',
                'slug' => 'dodatci',
                'description' => 'Kućišta, punjači, slušalice i ostali dodaci',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Get categories
        $phoneCategory = Category::where('slug', 'mobilni-telefoni')->first();
        $tabletCategory = Category::where('slug', 'tableti')->first();
        $accessoryCategory = Category::where('slug', 'dodatci')->first();

        // Create Brands
        $brands = [
            // Phone Brands
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'iPhone i iPad uređaji',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Galaxy telefoni i tableti',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Redmi i Mi serija telefona',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei',
                'description' => 'P i Mate serija telefona',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'OnePlus',
                'slug' => 'oneplus',
                'description' => 'Nord i flagship telefoni',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Google',
                'slug' => 'google',
                'description' => 'Pixel telefoni',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Xperia telefoni',
                'category_id' => $phoneCategory->id,
                'is_active' => true,
            ],
            // Tablet Brands
            [
                'name' => 'Apple iPad',
                'slug' => 'apple-ipad',
                'description' => 'iPad tableti',
                'category_id' => $tabletCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Tab',
                'slug' => 'samsung-tab',
                'description' => 'Galaxy Tab tableti',
                'category_id' => $tabletCategory->id,
                'is_active' => true,
            ],
            // Accessory Brands
            [
                'name' => 'Spigen',
                'slug' => 'spigen',
                'description' => 'Kućišta i zaštitni dodaci',
                'category_id' => $accessoryCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'Anker',
                'slug' => 'anker',
                'description' => 'Punjači i power bank-ovi',
                'category_id' => $accessoryCategory->id,
                'is_active' => true,
            ],
            [
                'name' => 'JBL',
                'slug' => 'jbl',
                'description' => 'Slušalice i zvučnici',
                'category_id' => $accessoryCategory->id,
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(
                ['slug' => $brandData['slug']],
                $brandData
            );
        }

        $this->command->info('Categories and brands seeded successfully!');
    }
}
