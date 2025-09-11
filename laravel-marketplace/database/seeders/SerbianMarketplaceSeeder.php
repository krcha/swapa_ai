<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ListingImage;
use Illuminate\Support\Facades\DB;

class SerbianMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing listings
        DB::table('listings')->truncate();
        DB::table('listing_images')->truncate();

        // Get users, brands, and categories
        $users = User::all();
        $brands = Brand::all();
        $categories = Category::all();

        if ($users->isEmpty() || $brands->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Please run UserSeeder, BrandSeeder, and CategorySeeder first!');
            return;
        }

        // Serbian carriers
        $serbianCarriers = ['mts', 'telenor', 'vip', 'yettel', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];
        
        // Phone models with realistic data
        $phoneModels = [
            // Apple iPhones
            [
                'brand' => 'Apple',
                'models' => [
                    ['name' => 'iPhone 15 Pro Max', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Natural Titanium', 'Blue Titanium', 'White Titanium', 'Black Titanium'], 'price_range' => [1200, 1600]],
                    ['name' => 'iPhone 15 Pro', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Natural Titanium', 'Blue Titanium', 'White Titanium', 'Black Titanium'], 'price_range' => [1000, 1400]],
                    ['name' => 'iPhone 15', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Pink', 'Yellow', 'Green', 'Blue', 'Black'], 'price_range' => [800, 1100]],
                    ['name' => 'iPhone 14 Pro Max', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Deep Purple', 'Gold', 'Silver', 'Space Black'], 'price_range' => [900, 1300]],
                    ['name' => 'iPhone 14 Pro', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Deep Purple', 'Gold', 'Silver', 'Space Black'], 'price_range' => [800, 1200]],
                    ['name' => 'iPhone 14', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Blue', 'Purple', 'Yellow', 'Midnight', 'Starlight', 'Red'], 'price_range' => [600, 900]],
                    ['name' => 'iPhone 13 Pro Max', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Graphite', 'Gold', 'Silver', 'Sierra Blue', 'Alpine Green'], 'price_range' => [700, 1100]],
                    ['name' => 'iPhone 13 Pro', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Graphite', 'Gold', 'Silver', 'Sierra Blue', 'Alpine Green'], 'price_range' => [600, 1000]],
                    ['name' => 'iPhone 13', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Pink', 'Blue', 'Midnight', 'Starlight', 'Red'], 'price_range' => [500, 800]],
                    ['name' => 'iPhone 12 Pro Max', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Graphite', 'Silver', 'Gold', 'Pacific Blue'], 'price_range' => [400, 700]],
                    ['name' => 'iPhone 12 Pro', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Graphite', 'Silver', 'Gold', 'Pacific Blue'], 'price_range' => [350, 600]],
                    ['name' => 'iPhone 12', 'storage' => ['64GB', '128GB', '256GB'], 'colors' => ['Black', 'White', 'Red', 'Green', 'Blue', 'Purple'], 'price_range' => [300, 500]],
                    ['name' => 'iPhone 11 Pro Max', 'storage' => ['64GB', '256GB', '512GB'], 'colors' => ['Space Gray', 'Silver', 'Gold', 'Midnight Green'], 'price_range' => [250, 450]],
                    ['name' => 'iPhone 11 Pro', 'storage' => ['64GB', '256GB', '512GB'], 'colors' => ['Space Gray', 'Silver', 'Gold', 'Midnight Green'], 'price_range' => [200, 400]],
                    ['name' => 'iPhone 11', 'storage' => ['64GB', '128GB', '256GB'], 'colors' => ['Black', 'Green', 'Yellow', 'Purple', 'Red', 'White'], 'price_range' => [150, 350]],
                ]
            ],
            // Samsung Galaxy
            [
                'brand' => 'Samsung',
                'models' => [
                    ['name' => 'Galaxy S24 Ultra', 'storage' => ['256GB', '512GB', '1TB'], 'colors' => ['Titanium Black', 'Titanium Gray', 'Titanium Violet', 'Titanium Yellow'], 'price_range' => [1000, 1400]],
                    ['name' => 'Galaxy S24+', 'storage' => ['256GB', '512GB'], 'colors' => ['Onyx Black', 'Marble Gray', 'Cobalt Violet', 'Amber Yellow'], 'price_range' => [800, 1100]],
                    ['name' => 'Galaxy S24', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Onyx Black', 'Marble Gray', 'Cobalt Violet', 'Amber Yellow'], 'price_range' => [600, 900]],
                    ['name' => 'Galaxy S23 Ultra', 'storage' => ['256GB', '512GB', '1TB'], 'colors' => ['Phantom Black', 'Cream', 'Green', 'Lavender'], 'price_range' => [700, 1100]],
                    ['name' => 'Galaxy S23+', 'storage' => ['256GB', '512GB'], 'colors' => ['Phantom Black', 'Cream', 'Green', 'Lavender'], 'price_range' => [600, 900]],
                    ['name' => 'Galaxy S23', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Phantom Black', 'Cream', 'Green', 'Lavender'], 'price_range' => [500, 800]],
                    ['name' => 'Galaxy S22 Ultra', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Phantom Black', 'Phantom White', 'Burgundy', 'Green'], 'price_range' => [400, 700]],
                    ['name' => 'Galaxy S22+', 'storage' => ['128GB', '256GB'], 'colors' => ['Phantom Black', 'Phantom White', 'Burgundy', 'Green'], 'price_range' => [350, 600]],
                    ['name' => 'Galaxy S22', 'storage' => ['128GB', '256GB'], 'colors' => ['Phantom Black', 'Phantom White', 'Burgundy', 'Green'], 'price_range' => [300, 500]],
                    ['name' => 'Galaxy Note 20 Ultra', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Mystic Bronze', 'Mystic Black', 'Mystic White'], 'price_range' => [250, 450]],
                    ['name' => 'Galaxy A54', 'storage' => ['128GB', '256GB'], 'colors' => ['Awesome Black', 'Awesome White', 'Awesome Violet', 'Awesome Lime'], 'price_range' => [200, 350]],
                    ['name' => 'Galaxy A34', 'storage' => ['128GB', '256GB'], 'colors' => ['Awesome Black', 'Awesome White', 'Awesome Violet', 'Awesome Lime'], 'price_range' => [150, 300]],
                ]
            ],
            // Xiaomi
            [
                'brand' => 'Xiaomi',
                'models' => [
                    ['name' => 'Xiaomi 14 Pro', 'storage' => ['256GB', '512GB', '1TB'], 'colors' => ['Black', 'White', 'Green'], 'price_range' => [600, 900]],
                    ['name' => 'Xiaomi 14', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Black', 'White', 'Green'], 'price_range' => [500, 800]],
                    ['name' => 'Xiaomi 13 Pro', 'storage' => ['256GB', '512GB'], 'colors' => ['Black', 'White', 'Blue'], 'price_range' => [400, 700]],
                    ['name' => 'Xiaomi 13', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Black', 'White', 'Blue'], 'price_range' => [350, 600]],
                    ['name' => 'Xiaomi 12 Pro', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Black', 'Blue', 'Purple'], 'price_range' => [250, 450]],
                    ['name' => 'Xiaomi 12', 'storage' => ['128GB', '256GB'], 'colors' => ['Black', 'Blue', 'Purple'], 'price_range' => [200, 400]],
                    ['name' => 'Redmi Note 13 Pro', 'storage' => ['128GB', '256GB'], 'colors' => ['Black', 'White', 'Blue'], 'price_range' => [150, 300]],
                    ['name' => 'Redmi Note 13', 'storage' => ['64GB', '128GB', '256GB'], 'colors' => ['Black', 'White', 'Blue'], 'price_range' => [100, 250]],
                ]
            ],
            // Google Pixel
            [
                'brand' => 'Google',
                'models' => [
                    ['name' => 'Pixel 8 Pro', 'storage' => ['128GB', '256GB', '512GB', '1TB'], 'colors' => ['Obsidian', 'Porcelain', 'Bay'], 'price_range' => [800, 1200]],
                    ['name' => 'Pixel 8', 'storage' => ['128GB', '256GB'], 'colors' => ['Obsidian', 'Hazel', 'Rose'], 'price_range' => [600, 900]],
                    ['name' => 'Pixel 7 Pro', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Obsidian', 'Snow', 'Hazel'], 'price_range' => [400, 700]],
                    ['name' => 'Pixel 7', 'storage' => ['128GB', '256GB'], 'colors' => ['Obsidian', 'Snow', 'Lemongrass'], 'price_range' => [350, 600]],
                    ['name' => 'Pixel 6 Pro', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Stormy Black', 'Cloudy White', 'Sorta Sunny'], 'price_range' => [250, 450]],
                    ['name' => 'Pixel 6', 'storage' => ['128GB', '256GB'], 'colors' => ['Stormy Black', 'Sorta Seafoam', 'Kinda Coral'], 'price_range' => [200, 400]],
                ]
            ],
            // OnePlus
            [
                'brand' => 'OnePlus',
                'models' => [
                    ['name' => 'OnePlus 12', 'storage' => ['256GB', '512GB'], 'colors' => ['Silky Black', 'Flowy Emerald'], 'price_range' => [600, 900]],
                    ['name' => 'OnePlus 11', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Titan Black', 'Eternal Green'], 'price_range' => [400, 700]],
                    ['name' => 'OnePlus 10 Pro', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['Volcanic Black', 'Emerald Forest'], 'price_range' => [300, 500]],
                    ['name' => 'OnePlus 9 Pro', 'storage' => ['128GB', '256GB'], 'colors' => ['Morning Mist', 'Pine Green', 'Stellar Black'], 'price_range' => [200, 400]],
                    ['name' => 'OnePlus 9', 'storage' => ['128GB', '256GB'], 'colors' => ['Winter Mist', 'Arctic Sky', 'Astral Black'], 'price_range' => [150, 350]],
                ]
            ],
            // Other brands
            [
                'brand' => 'Other',
                'models' => [
                    ['name' => 'Huawei P60 Pro', 'storage' => ['256GB', '512GB'], 'colors' => ['Rococo Pearl', 'Quartz', 'Elk Brown'], 'price_range' => [500, 800]],
                    ['name' => 'Huawei P50 Pro', 'storage' => ['256GB', '512GB'], 'colors' => ['Golden Black', 'Cocoa Gold'], 'price_range' => [300, 600]],
                    ['name' => 'Sony Xperia 1 V', 'storage' => ['256GB', '512GB'], 'colors' => ['Black', 'White', 'Khaki Green'], 'price_range' => [800, 1200]],
                    ['name' => 'Sony Xperia 5 V', 'storage' => ['128GB', '256GB'], 'colors' => ['Black', 'White', 'Blue'], 'price_range' => [600, 900]],
                    ['name' => 'Nothing Phone 2', 'storage' => ['128GB', '256GB', '512GB'], 'colors' => ['White', 'Dark Gray'], 'price_range' => [400, 700]],
                    ['name' => 'Nothing Phone 1', 'storage' => ['128GB', '256GB'], 'colors' => ['White', 'Black'], 'price_range' => [250, 450]],
                ]
            ]
        ];

        // Conditions with realistic distribution (using valid enum values)
        $conditions = [
            'like_new' => 0.15,  // 15% like new condition
            'excellent' => 0.25, // 25% excellent condition
            'good' => 0.35,      // 35% good condition
            'fair' => 0.25,      // 25% fair condition
        ];

        $listings = [];
        $listingCount = 0;

        // Generate 500 realistic listings
        for ($i = 0; $i < 500; $i++) {
            // Select random brand and model
            $brandData = $phoneModels[array_rand($phoneModels)];
            $modelData = $brandData['models'][array_rand($brandData['models'])];
            
            // Find brand in database
            $brand = $brands->where('name', $brandData['brand'])->first();
            if (!$brand) continue;

            // Select random storage and color
            $storage = $modelData['storage'][array_rand($modelData['storage'])];
            $color = $modelData['colors'][array_rand($modelData['colors'])];
            
            // Select random carrier (20% locked, 80% unlocked)
            $carrier = $serbianCarriers[array_rand($serbianCarriers)];
            
            // Select random condition based on distribution
            $rand = mt_rand() / mt_getrandmax();
            $cumulative = 0;
            $condition = 'good'; // default
            foreach ($conditions as $cond => $prob) {
                $cumulative += $prob;
                if ($rand <= $cumulative) {
                    $condition = $cond;
                    break;
                }
            }
            
            // Calculate price based on model, storage, and condition
            $basePrice = $modelData['price_range'][0] + 
                        (($modelData['price_range'][1] - $modelData['price_range'][0]) * 
                         (array_search($storage, $modelData['storage']) / (count($modelData['storage']) - 1)));
            
            // Apply condition discount
            $conditionMultipliers = [
                'like_new' => 0.95,
                'excellent' => 0.85,
                'good' => 0.75,
                'fair' => 0.60,
            ];
            
            $price = round($basePrice * $conditionMultipliers[$condition]);
            
            // Add some random variation (±10%)
            $variation = mt_rand(90, 110) / 100;
            $price = round($price * $variation);
            
            // Ensure minimum price
            $price = max($price, 50);
            
            // Create title
            $title = $modelData['name'] . ' ' . $storage . ' - ' . $color;
            if ($carrier) {
                $title .= ' (' . strtoupper($carrier) . ')';
            }
            
            // Create description
            $descriptions = [
                "Excellent condition {$modelData['name']} with {$storage} storage in {$color}. " . 
                ($carrier ? "Locked to {$carrier} network. " : "Unlocked for all carriers. ") . 
                "Well maintained, no major scratches or damage.",
                
                "Great deal on {$modelData['name']} {$storage} in {$color}. " . 
                ($carrier ? "Carrier locked to {$carrier}. " : "Fully unlocked. ") . 
                "Minor wear consistent with normal use.",
                
                "{$modelData['name']} {$storage} {$color} in good working condition. " . 
                ($carrier ? "Locked to {$carrier} network. " : "Unlocked device. ") . 
                "All functions working properly.",
                
                "Quality {$modelData['name']} with {$storage} storage, {$color} color. " . 
                ($carrier ? "Network locked to {$carrier}. " : "Unlocked for all networks. ") . 
                "Good value for money.",
            ];
            
            $description = $descriptions[array_rand($descriptions)];
            
            // Select random user
            $user = $users->random();
            
            // Select random category (phones)
            $category = $categories->where('name', 'Mobilni telefoni')->first();
            
            // Generate additional required fields
            $batteryHealth = mt_rand(70, 100);
            $screenCondition = $condition === 'like_new' ? 'perfect' : 
                              ($condition === 'excellent' ? 'excellent' : 
                              ($condition === 'good' ? 'good' : 'fair'));
            $bodyCondition = $condition === 'like_new' ? 'perfect' : 
                            ($condition === 'excellent' ? 'excellent' : 
                            ($condition === 'good' ? 'good' : 'fair'));
            $contactPreferences = ['phone', 'email', 'both'];
            $contactPreference = $contactPreferences[array_rand($contactPreferences)];
            
            // Create listing
            $listing = Listing::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'condition' => $condition,
                'carrier' => $carrier,
                'storage' => $storage,
                'color' => $color,
                'battery_health' => $batteryHealth,
                'screen_condition' => $screenCondition,
                'body_condition' => $bodyCondition,
                'contact_preference' => $contactPreference,
                'status' => 'active',
                'expires_at' => now()->addDays(30),
                'view_count' => mt_rand(0, 50),
                'created_at' => now()->subDays(mt_rand(0, 90)),
                'updated_at' => now()->subDays(mt_rand(0, 30)),
            ]);
            
            $listings[] = $listing;
            $listingCount++;
            
            // Create placeholder images (2-4 images per listing)
            $imageCount = mt_rand(2, 4);
            for ($j = 0; $j < $imageCount; $j++) {
                $imagePath = 'listings/placeholder-' . mt_rand(1, 10) . '.jpg';
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $imagePath,
                    'image_url' => asset('storage/' . $imagePath),
                    'alt_text' => $listing->title . ' - Image ' . ($j + 1),
                    'sort_order' => $j,
                    'is_primary' => $j === 0,
                    'created_at' => $listing->created_at,
                    'updated_at' => $listing->updated_at,
                ]);
            }
        }

        $this->command->info("Generated {$listingCount} realistic Serbian marketplace listings!");
        $this->command->info("Distribution:");
        $this->command->info("- Unlocked phones: " . collect($listings)->whereNull('carrier')->count());
        $this->command->info("- Locked phones: " . collect($listings)->whereNotNull('carrier')->count());
        
        $carrierBreakdown = collect($listings)->whereNotNull('carrier')->groupBy('carrier')->map->count();
        foreach ($carrierBreakdown as $carrier => $count) {
            $this->command->info("- {$carrier}: {$count} listings");
        }
    }
}
