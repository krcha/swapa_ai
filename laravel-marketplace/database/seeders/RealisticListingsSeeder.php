<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RealisticListingsSeeder extends Seeder
{
    public function run()
    {
        // Get existing users, brands, and categories
        $users = User::all();
        $brands = Brand::all()->keyBy('name');
        $categories = Category::all()->keyBy('name');

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run user seeder first.');
            return;
        }

        // Phone listings data
        $phoneListings = [
            // Apple iPhones
            [
                'title' => 'iPhone 15 Pro Max 256GB - Natural Titanium',
                'description' => 'Mint condition iPhone 15 Pro Max with original box, charger, and cable. Never dropped, always in case. Battery health 100%.',
                'price' => 1199.00,
                'condition' => 'like_new',
                'storage' => '256GB',
                'color' => 'Natural Titanium',
                'battery_health' => 100,
                'screen_condition' => 'perfect',
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'iPhone 14 Pro 128GB - Deep Purple',
                'description' => 'Excellent condition iPhone 14 Pro. Small scratch on back, screen perfect. Comes with original accessories.',
                'price' => 899.00,
                'condition' => 'excellent',
                'storage' => '128GB',
                'color' => 'Deep Purple',
                'battery_health' => 94,
                'screen_condition' => 'perfect',
                'body_condition' => 'excellent',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'iPhone 13 256GB - Pink (MTS)',
                'description' => 'Good condition iPhone 13 locked to MTS. Some wear on edges, screen has minor scratches. Still works perfectly.',
                'price' => 599.00,
                'condition' => 'good',
                'storage' => '256GB',
                'color' => 'Pink',
                'battery_health' => 87,
                'screen_condition' => 'good',
                'body_condition' => 'good',
                'carrier' => 'MTS',
                'brand' => 'Apple',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'iPhone 12 Pro 128GB - Pacific Blue',
                'description' => 'Fair condition iPhone 12 Pro. Screen has some scratches, body shows wear. Still functional for daily use.',
                'price' => 499.00,
                'condition' => 'fair',
                'storage' => '128GB',
                'color' => 'Pacific Blue',
                'battery_health' => 82,
                'screen_condition' => 'fair',
                'body_condition' => 'fair',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Mobilni telefoni'
            ],

            // Samsung Galaxy
            [
                'title' => 'Samsung Galaxy S24 Ultra 512GB - Titanium Black',
                'description' => 'Like new Galaxy S24 Ultra with S Pen. Original box and all accessories included. Perfect condition.',
                'price' => 1099.00,
                'condition' => 'like_new',
                'storage' => '512GB',
                'color' => 'Titanium Black',
                'battery_health' => 100,
                'screen_condition' => 'perfect',
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Samsung',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'Samsung Galaxy S23 Ultra 256GB - Phantom Black (Telenor)',
                'description' => 'Excellent condition S23 Ultra locked to Telenor. Minor scuffs on frame, screen perfect. S Pen included.',
                'price' => 799.00,
                'condition' => 'excellent',
                'storage' => '256GB',
                'color' => 'Phantom Black',
                'battery_health' => 91,
                'screen_condition' => 'perfect',
                'body_condition' => 'excellent',
                'carrier' => 'Telenor',
                'brand' => 'Samsung',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'Samsung Galaxy A54 128GB - Awesome Violet',
                'description' => 'Good condition Galaxy A54. Some wear on back, screen has minor scratches. Great budget option.',
                'price' => 299.00,
                'condition' => 'good',
                'storage' => '128GB',
                'color' => 'Awesome Violet',
                'battery_health' => 89,
                'screen_condition' => 'good',
                'body_condition' => 'good',
                'carrier' => null,
                'brand' => 'Samsung',
                'category' => 'Mobilni telefoni'
            ],

            // Google Pixel
            [
                'title' => 'Google Pixel 8 Pro 256GB - Obsidian',
                'description' => 'Mint condition Pixel 8 Pro with original box and accessories. Never used without case. Perfect for photography.',
                'price' => 899.00,
                'condition' => 'like_new',
                'storage' => '256GB',
                'color' => 'Obsidian',
                'battery_health' => 100,
                'screen_condition' => 'perfect',
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Google',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'Google Pixel 7 128GB - Snow (VIP)',
                'description' => 'Excellent condition Pixel 7 locked to VIP. Small scratch on back, screen perfect. Great camera phone.',
                'price' => 549.00,
                'condition' => 'excellent',
                'storage' => '128GB',
                'color' => 'Snow',
                'battery_health' => 93,
                'screen_condition' => 'perfect',
                'body_condition' => 'excellent',
                'carrier' => 'VIP',
                'brand' => 'Google',
                'category' => 'Mobilni telefoni'
            ],

            // OnePlus
            [
                'title' => 'OnePlus 12 256GB - Flowy Emerald',
                'description' => 'Like new OnePlus 12 with original box and charger. Fast charging and smooth performance.',
                'price' => 699.00,
                'condition' => 'like_new',
                'storage' => '256GB',
                'color' => 'Flowy Emerald',
                'battery_health' => 100,
                'screen_condition' => 'perfect',
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'OnePlus',
                'category' => 'Mobilni telefoni'
            ],

            // Xiaomi
            [
                'title' => 'Xiaomi 14 Pro 512GB - Black (Yettel)',
                'description' => 'Excellent condition Xiaomi 14 Pro locked to Yettel. Premium build quality, great performance.',
                'price' => 649.00,
                'condition' => 'excellent',
                'storage' => '512GB',
                'color' => 'Black',
                'battery_health' => 95,
                'screen_condition' => 'perfect',
                'body_condition' => 'excellent',
                'carrier' => 'Yettel',
                'brand' => 'Xiaomi',
                'category' => 'Mobilni telefoni'
            ],
            [
                'title' => 'Xiaomi Redmi Note 13 Pro 256GB - White',
                'description' => 'Good condition Redmi Note 13 Pro. Some wear on back, screen perfect. Great value for money.',
                'price' => 249.00,
                'condition' => 'good',
                'storage' => '256GB',
                'color' => 'White',
                'battery_health' => 88,
                'screen_condition' => 'perfect',
                'body_condition' => 'good',
                'carrier' => null,
                'brand' => 'Xiaomi',
                'category' => 'Mobilni telefoni'
            ]
        ];

        // Accessories listings data
        $accessoryListings = [
            // Chargers
            [
                'title' => 'Apple MagSafe Charger - Original',
                'description' => 'Original Apple MagSafe charger in perfect condition. Works with iPhone 12 and newer models.',
                'price' => 39.00,
                'condition' => 'like_new',
                'storage' => null,
                'color' => 'White',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Samsung 25W Super Fast Charger - USB-C',
                'description' => 'Samsung original 25W fast charger with USB-C cable. Compatible with all Samsung Galaxy phones.',
                'price' => 29.00,
                'condition' => 'excellent',
                'storage' => null,
                'color' => 'White',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'excellent',
                'carrier' => null,
                'brand' => 'Samsung',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Anker PowerCore 10000 Wireless PowerBank',
                'description' => 'Portable wireless power bank with 10000mAh capacity. Works with all Qi-compatible devices.',
                'price' => 49.00,
                'condition' => 'good',
                'storage' => null,
                'color' => 'Black',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'good',
                'carrier' => null,
                'brand' => 'Anker',
                'category' => 'Dodatci'
            ],

            // Earphones/Headphones
            [
                'title' => 'Apple AirPods Pro 2nd Gen - White',
                'description' => 'Like new AirPods Pro 2nd generation with active noise cancellation. Original box and all accessories included.',
                'price' => 199.00,
                'condition' => 'like_new',
                'storage' => null,
                'color' => 'White',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Samsung Galaxy Buds2 Pro - Graphite',
                'description' => 'Excellent condition Galaxy Buds2 Pro with noise cancellation. Perfect sound quality and comfort.',
                'price' => 149.00,
                'condition' => 'excellent',
                'storage' => null,
                'color' => 'Graphite',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'excellent',
                'carrier' => null,
                'brand' => 'Samsung',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Sony WH-1000XM4 Noise Cancelling Headphones',
                'description' => 'Premium Sony headphones with industry-leading noise cancellation. Some wear on headband, otherwise perfect.',
                'price' => 199.00,
                'condition' => 'good',
                'storage' => null,
                'color' => 'Black',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'good',
                'carrier' => null,
                'brand' => 'Sony',
                'category' => 'Dodatci'
            ],

            // Screen Protectors
            [
                'title' => 'Tempered Glass Screen Protector for iPhone 15 Pro Max',
                'description' => 'High-quality tempered glass screen protector with easy installation kit. Crystal clear protection.',
                'price' => 19.00,
                'condition' => 'like_new',
                'storage' => null,
                'color' => 'Clear',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Spigen',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Samsung Galaxy S24 Ultra Screen Protector - 3 Pack',
                'description' => '3-pack tempered glass screen protectors for Galaxy S24 Ultra. Includes installation tools and cleaning kit.',
                'price' => 24.00,
                'condition' => 'excellent',
                'storage' => null,
                'color' => 'Clear',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'excellent',
                'carrier' => null,
                'brand' => 'ESR',
                'category' => 'Dodatci'
            ],

            // Cases
            [
                'title' => 'Apple iPhone 15 Pro Clear Case with MagSafe',
                'description' => 'Original Apple clear case with MagSafe compatibility. Shows off your phone while providing protection.',
                'price' => 49.00,
                'condition' => 'like_new',
                'storage' => null,
                'color' => 'Clear',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'perfect',
                'carrier' => null,
                'brand' => 'Apple',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Samsung Galaxy S24 Ultra Leather Case - Brown',
                'description' => 'Premium leather case for Galaxy S24 Ultra. Some patina from use, adds character. S Pen slot included.',
                'price' => 39.00,
                'condition' => 'good',
                'storage' => null,
                'color' => 'Brown',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'good',
                'carrier' => null,
                'brand' => 'Samsung',
                'category' => 'Dodatci'
            ],
            [
                'title' => 'Spigen Rugged Armor Case for Google Pixel 8 Pro',
                'description' => 'Durable rugged case with military-grade protection. Some scratches from daily use, still protective.',
                'price' => 29.00,
                'condition' => 'fair',
                'storage' => null,
                'color' => 'Black',
                'battery_health' => null,
                'screen_condition' => null,
                'body_condition' => 'fair',
                'carrier' => null,
                'brand' => 'Spigen',
                'category' => 'Dodatci'
            ]
        ];

        // Combine all listings
        $allListings = array_merge($phoneListings, $accessoryListings);

        $this->command->info('Creating ' . count($allListings) . ' realistic listings...');

        foreach ($allListings as $listingData) {
            // Get random user
            $user = $users->random();
            
            // Get brand
            $brand = $brands->get($listingData['brand']);
            if (!$brand) {
                $this->command->warn("Brand '{$listingData['brand']}' not found, skipping...");
                continue;
            }

            // Get category
            $category = $categories->get($listingData['category']);
            if (!$category) {
                $this->command->warn("Category '{$listingData['category']}' not found, skipping...");
                continue;
            }

            // Create listing
            $listing = Listing::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'title' => $listingData['title'],
                'description' => $listingData['description'],
                'price' => $listingData['price'],
                'condition' => $listingData['condition'],
                'storage' => $listingData['storage'],
                'color' => $listingData['color'],
                'battery_health' => $listingData['battery_health'],
                'screen_condition' => $listingData['screen_condition'],
                'body_condition' => $listingData['body_condition'],
                'carrier' => $listingData['carrier'],
                'contact_preference' => 'both',
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
                'view_count' => rand(0, 50),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 7))
            ]);

            // Create mock images (2-4 images per listing)
            $imageCount = rand(2, 4);
            for ($i = 0; $i < $imageCount; $i++) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => 'listings/placeholder-' . rand(1, 10) . '.jpg',
                    'image_url' => asset('images/placeholder-' . rand(1, 10) . '.jpg'),
                    'alt_text' => $listing->title . ' - Image ' . ($i + 1),
                    'is_primary' => $i === 0,
                    'sort_order' => $i + 1,
                    'created_at' => $listing->created_at,
                    'updated_at' => $listing->updated_at
                ]);
            }

            $this->command->info("Created: {$listing->title} - \${$listing->price}");
        }

        $this->command->info('Realistic listings created successfully!');
        $this->command->info('Total listings: ' . Listing::count());
        $this->command->info('Total images: ' . ListingImage::count());
    }
}
