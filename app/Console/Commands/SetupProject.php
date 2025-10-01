<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automates project setup after clone (composer install, env setup, migrations, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Laravel project setup...');

        // 1. Run composer install
        $this->info('📦 Installing composer dependencies...');
        exec('composer install');

        // 2. Copy .env.example if .env doesn’t exist
        if (!file_exists(base_path('.env'))) {
            $this->info('⚙️ Creating .env file from .env.example...');
            copy(base_path('.env.example'), base_path('.env'));
        }

        // 3. Generate app key
        $this->info('🔑 Generating application key...');
        Artisan::call('key:generate');
        $this->info(Artisan::output());

        // 4. Ask for database name
        $dbName = $this->ask('🗄️  Enter the database name you want to use');
        
        // Update .env with new DB_DATABASE
        $envPath = base_path('.env');
        file_put_contents($envPath, preg_replace(
            '/DB_DATABASE=.*/',
            'DB_DATABASE=' . $dbName,
            file_get_contents($envPath)
        ));

        $this->info("✅ Database name set to: $dbName");

        // 5. Run migrations
        $this->info('📂 Running migrations...');
        Artisan::call('migrate:fresh', ['--seed' => true]); // optional: with seed
        $this->info(Artisan::output());

        // 6. Create storage symlink
        $this->info('🔗 Creating storage symlink...');
        Artisan::call('storage:link');
        $this->info(Artisan::output());

        $this->info('🎉 Project setup completed successfully!');
    }
}
