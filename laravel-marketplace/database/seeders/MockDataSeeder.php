<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Serbian names and data
        $serbianNames = [
            'Marko', 'Ana', 'Stefan', 'Milica', 'Nikola', 'Jovana', 'Petar', 'Sara',
            'Aleksandar', 'Marija', 'Miloš', 'Jelena', 'Nemanja', 'Tijana', 'Dušan', 'Katarina',
            'Vladimir', 'Milena', 'Mihailo', 'Tamara', 'Luka', 'Aleksandra', 'Filip', 'Andjela',
            'Đorđe', 'Jovana', 'Stefan', 'Milica', 'Nikola', 'Sara'
        ];

        $serbianLastNames = [
            'Petrović', 'Jovanović', 'Nikolić', 'Marković', 'Đorđević', 'Stojanović',
            'Ilić', 'Milošević', 'Radović', 'Kostić', 'Mitić', 'Pavlović',
            'Simić', 'Lazić', 'Popović', 'Stefanović', 'Tomić', 'Vuković',
            'Mladenović', 'Ristić', 'Đukić', 'Milojević', 'Antić', 'Stanković'
        ];

        $serbianCities = [
            'Beograd', 'Novi Sad', 'Niš', 'Kragujevac', 'Subotica', 'Zrenjanin',
            'Pančevo', 'Čačak', 'Kraljevo', 'Smederevo', 'Leskovac', 'Užice'
        ];

        $phoneModels = [
            // iPhones
            ['brand' => 'Apple', 'model' => 'iPhone 15 Pro Max', 'storage' => '256GB', 'price_range' => [1200, 1500]],
            ['brand' => 'Apple', 'model' => 'iPhone 15 Pro', 'storage' => '128GB', 'price_range' => [1000, 1300]],
            ['brand' => 'Apple', 'model' => 'iPhone 15', 'storage' => '128GB', 'price_range' => [800, 1000]],
            ['brand' => 'Apple', 'model' => 'iPhone 14 Pro Max', 'storage' => '256GB', 'price_range' => [900, 1200]],
            ['brand' => 'Apple', 'model' => 'iPhone 14 Pro', 'storage' => '128GB', 'price_range' => [700, 900]],
            ['brand' => 'Apple', 'model' => 'iPhone 14', 'storage' => '128GB', 'price_range' => [600, 800]],
            ['brand' => 'Apple', 'model' => 'iPhone 13 Pro Max', 'storage' => '256GB', 'price_range' => [700, 900]],
            ['brand' => 'Apple', 'model' => 'iPhone 13 Pro', 'storage' => '128GB', 'price_range' => [500, 700]],
            ['brand' => 'Apple', 'model' => 'iPhone 13', 'storage' => '128GB', 'price_range' => [400, 600]],
            
            // Samsung
            ['brand' => 'Samsung', 'model' => 'Galaxy S24 Ultra', 'storage' => '256GB', 'price_range' => [1000, 1300]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S24+', 'storage' => '256GB', 'price_range' => [800, 1000]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S24', 'storage' => '128GB', 'price_range' => [600, 800]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S23 Ultra', 'storage' => '256GB', 'price_range' => [700, 900]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S23+', 'storage' => '256GB', 'price_range' => [500, 700]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S23', 'storage' => '128GB', 'price_range' => [400, 600]],
            ['brand' => 'Samsung', 'model' => 'Galaxy A54', 'storage' => '128GB', 'price_range' => [250, 350]],
            ['brand' => 'Samsung', 'model' => 'Galaxy A34', 'storage' => '128GB', 'price_range' => [200, 300]],
            
            // Xiaomi
            ['brand' => 'Xiaomi', 'model' => 'Mi 13 Pro', 'storage' => '256GB', 'price_range' => [500, 700]],
            ['brand' => 'Xiaomi', 'model' => 'Mi 13', 'storage' => '128GB', 'price_range' => [400, 600]],
            ['brand' => 'Xiaomi', 'model' => 'Redmi Note 13 Pro', 'storage' => '256GB', 'price_range' => [250, 350]],
            ['brand' => 'Xiaomi', 'model' => 'Redmi Note 13', 'storage' => '128GB', 'price_range' => [200, 300]],
            ['brand' => 'Xiaomi', 'model' => 'Redmi Note 12 Pro', 'storage' => '128GB', 'price_range' => [180, 280]],
            
            // Huawei
            ['brand' => 'Huawei', 'model' => 'P60 Pro', 'storage' => '256GB', 'price_range' => [600, 800]],
            ['brand' => 'Huawei', 'model' => 'P50 Pro', 'storage' => '256GB', 'price_range' => [400, 600]],
            ['brand' => 'Huawei', 'model' => 'P50', 'storage' => '128GB', 'price_range' => [300, 500]],
            
            // OnePlus
            ['brand' => 'OnePlus', 'model' => '11', 'storage' => '256GB', 'price_range' => [500, 700]],
            ['brand' => 'OnePlus', 'model' => 'Nord 3', 'storage' => '128GB', 'price_range' => [300, 400]],
            ['brand' => 'OnePlus', 'model' => 'Nord 2T', 'storage' => '128GB', 'price_range' => [250, 350]],
        ];

        $colors = [
            'Space Gray', 'Gold', 'Silver', 'Blue', 'White', 'Black', 'Purple', 'Green',
            'Pink', 'Red', 'Midnight', 'Starlight', 'Graphite', 'Sierra Blue', 'Alpine Green'
        ];

        $conditions = ['like_new', 'good', 'fair'];
        $conditionSerbian = [
            'like_new' => 'Kao nov, malo korišten',
            'good' => 'Dobar, u odličnom stanju',
            'fair' => 'Prilično dobar, neki tragovi korišćenja'
        ];

        $descriptions = [
            'Odličan telefon, malo korišten. Nema ogrebotina na ekranu.',
            'Excellent condition, no scratches. Original box included.',
            'Prodajem zbog upgrade. Telefon je u odličnom stanju.',
            'Very good phone, battery health 95%. Original charger included.',
            'Koristio sam pažljivo, nema problema. Sve radi kako treba.',
            'Great phone, selling because I got a new one. No issues.',
            'Telefon je u odličnom stanju, originalno kućište uključeno.',
            'Perfect condition, like new. Original box and accessories.',
            'Malo korišten, odličan za svakodnevnu upotrebu.',
            'Excellent phone, selling due to upgrade. Everything works perfectly.'
        ];

        // Get categories and brands
        $phoneCategory = Category::where('slug', 'mobilni-telefoni')->first();
        $brands = Brand::all()->keyBy('name');

        // Get plans
        $freePlan = Plan::where('slug', 'free')->first();
        $tier1Plan = Plan::where('slug', 'tier-1')->first();
        $tier2Plan = Plan::where('slug', 'tier-2')->first();

        // Create users
        $this->command->info('Creating users...');
        for ($i = 0; $i < 25; $i++) {
            $firstName = $serbianNames[array_rand($serbianNames)];
            $lastName = $serbianLastNames[array_rand($serbianLastNames)];
            $email = strtolower($firstName . '.' . $lastName . rand(1, 99) . '@' . ['gmail.com', 'yahoo.com', 'hotmail.com'][array_rand([0, 1, 2])]);
            $phone = '+381' . rand(60, 69) . rand(1000000, 9999999);
            $city = $serbianCities[array_rand($serbianCities)];
            
            // Assign plans (mostly free, some paid)
            $plan = rand(1, 10) <= 7 ? $freePlan : (rand(1, 2) == 1 ? $tier1Plan : $tier2Plan);
            
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make('password123'),
                'is_sms_verified' => rand(0, 1),
                'is_email_verified' => rand(0, 1),
                'created_at' => Carbon::now()->subDays(rand(1, 180)),
            ]);

            // Create subscription for paid users
            if ($plan->slug !== 'free') {
                $user->subscriptions()->create([
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => Carbon::now()->subDays(rand(1, 30)),
                    'ends_at' => Carbon::now()->addDays(rand(1, 365)),
                ]);
            }
        }

        // Create listings
        $this->command->info('Creating listings...');
        $users = User::all();
        
        for ($i = 0; $i < 80; $i++) {
            $phoneData = $phoneModels[array_rand($phoneModels)];
            $brand = $brands[$phoneData['brand']];
            $user = $users->random();
            $color = $colors[array_rand($colors)];
            $condition = $conditions[array_rand($conditions)];
            $description = $descriptions[array_rand($descriptions)];
            $city = $serbianCities[array_rand($serbianCities)];
            
            $price = rand($phoneData['price_range'][0], $phoneData['price_range'][1]);
            
            $title = $phoneData['model'] . ' ' . $phoneData['storage'] . ' - ' . $color;
            
            $fullDescription = $description . "\n\n";
            $fullDescription .= "Model: " . $phoneData['model'] . "\n";
            $fullDescription .= "Memorija: " . $phoneData['storage'] . "\n";
            $fullDescription .= "Boja: " . $color . "\n";
            $fullDescription .= "Stanje: " . $conditionSerbian[$condition] . "\n";
            $fullDescription .= "Baterija: " . rand(80, 100) . "%\n";
            $fullDescription .= "Lokacija: " . $city . "\n";
            $fullDescription .= "Originalno kućište: " . (rand(0, 1) ? 'Da' : 'Ne') . "\n";
            $fullDescription .= "Originalni punjač: " . (rand(0, 1) ? 'Da' : 'Ne');

            $listing = Listing::create([
                'user_id' => $user->id,
                'category_id' => $phoneCategory->id,
                'brand_id' => $brand->id,
                'title' => $title,
                'description' => $fullDescription,
                'price' => $price,
                'condition' => $condition,
                'status' => rand(0, 10) <= 8 ? 'active' : 'pending', // 80% active
                'contact_preference' => ['phone', 'email', 'both'][array_rand([0, 1, 2])],
                'expires_at' => Carbon::now()->addDays(30),
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }

        $this->command->info('Mock data seeded successfully!');
        $this->command->info('Created: ' . User::count() . ' users');
        $this->command->info('Created: ' . Listing::count() . ' listings');
    }
}
