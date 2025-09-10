<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $smartphones = Category::where('slug', 'smartphones')->first();
        $accessories = Category::where('slug', 'accessories')->first();
        $headphones = Category::where('slug', 'headphones')->first();
        $chargers = Category::where('slug', 'chargers')->first();
        $cases = Category::where('slug', 'cases')->first();

        $brands = [
            // Smartphone brands
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Apple Inc. smartphones',
                'category_id' => $smartphones->id,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Samsung Electronics smartphones',
                'category_id' => $smartphones->id,
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei',
                'description' => 'Huawei Technologies smartphones',
                'category_id' => $smartphones->id,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Xiaomi Corporation smartphones',
                'category_id' => $smartphones->id,
            ],
            [
                'name' => 'OnePlus',
                'slug' => 'oneplus',
                'description' => 'OnePlus smartphones',
                'category_id' => $smartphones->id,
            ],

            // Headphone brands
            [
                'name' => 'AirPods',
                'slug' => 'airpods',
                'description' => 'Apple AirPods',
                'category_id' => $headphones->id,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Sony headphones',
                'category_id' => $headphones->id,
            ],
            [
                'name' => 'Bose',
                'slug' => 'bose',
                'description' => 'Bose headphones',
                'category_id' => $headphones->id,
            ],

            // Charger brands
            [
                'name' => 'Apple Charger',
                'slug' => 'apple-charger',
                'description' => 'Apple charging accessories',
                'category_id' => $chargers->id,
            ],
            [
                'name' => 'Samsung Charger',
                'slug' => 'samsung-charger',
                'description' => 'Samsung charging accessories',
                'category_id' => $chargers->id,
            ],

            // Case brands
            [
                'name' => 'Spigen',
                'slug' => 'spigen',
                'description' => 'Spigen phone cases',
                'category_id' => $cases->id,
            ],
            [
                'name' => 'OtterBox',
                'slug' => 'otterbox',
                'description' => 'OtterBox protective cases',
                'category_id' => $cases->id,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
