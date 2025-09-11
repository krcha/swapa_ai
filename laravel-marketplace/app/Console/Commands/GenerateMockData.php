<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\MockDataSeeder;

class GenerateMockData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock:generate {--users=25 : Number of users to create} {--listings=80 : Number of listings to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate realistic mock data for the marketplace';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generating mock data for Laravel Marketplace...');
        
        $users = $this->option('users');
        $listings = $this->option('listings');
        
        $this->info("📊 Creating {$users} users and {$listings} listings...");
        
        // Run the mock data seeder
        $this->call('db:seed', ['--class' => MockDataSeeder::class]);
        
        $this->info('✅ Mock data generated successfully!');
        $this->info('🎉 Your marketplace now has realistic Serbian content!');
        
        return Command::SUCCESS;
    }
}