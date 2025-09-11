<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApprovedPhoneModel;

class ApprovedPhoneModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // Apple iPhone
            'Apple iPhone' => [
                'iPhone X', 'iPhone XR', 'iPhone XS', 'iPhone XS Max', 
                'iPhone 11', 'iPhone 11 Pro', 'iPhone 11 Pro Max', 
                'iPhone 12 mini', 'iPhone 12', 'iPhone 12 Pro', 'iPhone 12 Pro Max', 
                'iPhone 13 mini', 'iPhone 13', 'iPhone 13 Pro', 'iPhone 13 Pro Max', 
                'iPhone 14', 'iPhone 14 Plus', 'iPhone 14 Pro', 'iPhone 14 Pro Max', 
                'iPhone 15', 'iPhone 15 Plus', 'iPhone 15 Pro', 'iPhone 15 Pro Max', 
                'iPhone 16', 'iPhone 16 Plus', 'iPhone 16 Pro', 'iPhone 16 Pro Max', 
                'iPhone 17', 'iPhone 17 Pro', 'iPhone 17 Pro Max', 'iPhone Air'
            ],

            // Samsung Galaxy (S/Note)
            'Samsung Galaxy (S/Note)' => [
                'Galaxy S8', 'S8+', 'Note8', 'Galaxy S9', 'S9+', 'Note9', 
                'Galaxy S10', 'S10+', 'S10e', 'Note10', 
                'Galaxy S20', 'S20+', 'S20 Ultra', 'Note20', 'Note20 Ultra', 
                'Galaxy S21', 'S21+', 'S21 Ultra', 
                'Galaxy S22', 'S22+', 'S22 Ultra', 
                'Galaxy S23', 'S23+', 'S23 Ultra', 
                'Galaxy S24', 'S24+', 'S24 Ultra', 
                'S25', 'S25+', 'S25 Ultra'
            ],

            // Huawei
            'Huawei' => [
                'P10', 'P10 Plus', 'Mate 10', 'Mate 10 Pro', 
                'P20', 'P20 Pro', 'Mate 20', 'Mate 20 Pro', 
                'P30', 'P30 Pro', 'Mate 30', 'Mate 30 Pro', 
                'P40', 'P40 Pro', 'Mate 40', 'Mate 40 Pro', 
                'P50', 'P50 Pro', 'Mate 50', 'Mate 50 Pro', 
                'P60', 'P60 Pro', 'Mate 60', 'Mate 60 Pro', 
                'Pura 70', 'Mate 70', 'Pura 80'
            ],

            // Xiaomi
            'Xiaomi' => [
                'Mi 6', 'Mi MIX 2', 'Mi 8', 'Mi MIX 3', 'Mi 9', 'Mi 10', 'Mi 11', 
                '11T', '12', '13', '14', '15', '15 Pro', '15 Ultra'
            ],

            // OPPO (Find series / flagship)
            'OPPO (Find series / flagship)' => [
                'Find X', 'Reno 10x Zoom', 'Find X2', 'Find X2 Pro', 
                'Find X3', 'Find X3 Pro', 'Find X5', 'Find X5 Pro', 
                'Find X6', 'Find X6 Pro', 'Find X7', 'Find X7 Ultra'
            ],

            // Google Pixel
            'Google Pixel' => [
                'Pixel 2', 'Pixel 2 XL', 'Pixel 3', 'Pixel 3 XL', 
                'Pixel 4', 'Pixel 4 XL', 'Pixel 5', 
                'Pixel 6', 'Pixel 6 Pro', 'Pixel 7', 'Pixel 7 Pro', 
                'Pixel 8', 'Pixel 8 Pro', 'Pixel 9', 'Pixel 9 Pro', 'Pixel 9 Pro XL', 
                'Pixel 10', 'Pixel 10 Pro'
            ]
        ];

        $sortOrder = 0;
        foreach ($models as $brand => $brandModels) {
            foreach ($brandModels as $modelName) {
                $modelCode = $this->generateModelCode($brand, $modelName);
                
                ApprovedPhoneModel::create([
                    'brand_name' => $brand,
                    'model_name' => $modelName,
                    'model_code' => $modelCode,
                    'is_active' => true,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    /**
     * Generate URL-friendly model code
     */
    private function generateModelCode($brand, $modelName)
    {
        // Convert brand and model to lowercase and replace spaces/special chars with hyphens
        $brandCode = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $brand));
        $modelCode = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $modelName));
        
        return $brandCode . '-' . $modelCode;
    }
}