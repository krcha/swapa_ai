<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovedPhoneModelsSeeder extends Seeder
{
    public function run(): void
    {
        $approvedModels = [
            // iPhone Models
            ['brand' => 'Apple', 'model_name' => 'iPhone X', 'model_slug' => 'iphone-x'],
            ['brand' => 'Apple', 'model_name' => 'iPhone XR', 'model_slug' => 'iphone-xr'],
            ['brand' => 'Apple', 'model_name' => 'iPhone XS', 'model_slug' => 'iphone-xs'],
            ['brand' => 'Apple', 'model_name' => 'iPhone XS Max', 'model_slug' => 'iphone-xs-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 11', 'model_slug' => 'iphone-11'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 11 Pro', 'model_slug' => 'iphone-11-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 11 Pro Max', 'model_slug' => 'iphone-11-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 12 mini', 'model_slug' => 'iphone-12-mini'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 12', 'model_slug' => 'iphone-12'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 12 Pro', 'model_slug' => 'iphone-12-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 12 Pro Max', 'model_slug' => 'iphone-12-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 13 mini', 'model_slug' => 'iphone-13-mini'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 13', 'model_slug' => 'iphone-13'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 13 Pro', 'model_slug' => 'iphone-13-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 13 Pro Max', 'model_slug' => 'iphone-13-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 14', 'model_slug' => 'iphone-14'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 14 Plus', 'model_slug' => 'iphone-14-plus'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 14 Pro', 'model_slug' => 'iphone-14-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 14 Pro Max', 'model_slug' => 'iphone-14-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 15', 'model_slug' => 'iphone-15'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 15 Plus', 'model_slug' => 'iphone-15-plus'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 15 Pro', 'model_slug' => 'iphone-15-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 15 Pro Max', 'model_slug' => 'iphone-15-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 16', 'model_slug' => 'iphone-16'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 16 Plus', 'model_slug' => 'iphone-16-plus'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 16 Pro', 'model_slug' => 'iphone-16-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 16 Pro Max', 'model_slug' => 'iphone-16-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 17', 'model_slug' => 'iphone-17'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 17 Pro', 'model_slug' => 'iphone-17-pro'],
            ['brand' => 'Apple', 'model_name' => 'iPhone 17 Pro Max', 'model_slug' => 'iphone-17-pro-max'],
            ['brand' => 'Apple', 'model_name' => 'iPhone Air', 'model_slug' => 'iphone-air'],
        ];

        foreach ($approvedModels as $model) {
            DB::table('approved_phone_models')->insert(array_merge($model, [
                'category' => 'smartphone',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
