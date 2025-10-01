<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class SetupProject extends Command
{
    protected $signature = 'app:setup-project';
    protected $description = 'Automates project setup (env, key, migrations, storage link, seeds).';

    public function handle()
    {
        $this->info('🚀 Starting Laravel project setup...');

        // Ensure dependencies installed
        if (!file_exists(base_path('vendor/autoload.php'))) {
            $this->error('Dependencies not installed. Run `composer install` first and try again.');
            return 1;
        }

        // Copy .env.example -> .env if missing
        if (!file_exists(base_path('.env'))) {
            if (!file_exists(base_path('.env.example'))) {
                $this->error('.env.example not found. Create .env manually or add .env.example.');
                return 1;
            }
            copy(base_path('.env.example'), base_path('.env'));
            $this->info('✅ .env created from .env.example');
        }

        // Ask for DB details
        $dbName = $this->ask('Database name', env('DB_DATABASE', 'laravel'));
        $dbUser = $this->ask('Database user', env('DB_USERNAME', 'root'));
        $dbPass = $this->secret('Database password (leave empty for none)');


        // Update .env values
        $this->setEnvValue('DB_DATABASE', $dbName);
        $this->setEnvValue('DB_USERNAME', $dbUser);
        if ($dbPass !== null) {
            $this->setEnvValue('DB_PASSWORD', $dbPass);
        }


        config(['database.connections.mysql.database' => $dbName]);
        config(['database.connections.mysql.username' => $dbUser]);
        config(['database.connections.mysql.password' => $dbPass]);
        // DB::purge('mysql');
        // DB::reconnect('mysql');

        // Try to create the database if it doesn't exist
        try {
            $this->info("🛢️  Checking if database '{$dbName}' exists...");

            $pdo = new PDO("mysql:host=127.0.0.1;port=3306", $dbUser, $dbPass ?: null);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            $this->info("✅ Database '{$dbName}' is ready.");
        } catch (\Exception $e) {
            $this->error("❌ Could not create database: " . $e->getMessage());
            return 1;
        }




        // Generate key
        $this->info('🔑 Generating app key...');
        Artisan::call('key:generate');
        $this->line(Artisan::output());


        $this->info('🧹 Clearing config cache...');
        Artisan::call('config:clear');

        // Run migrations
        $this->info('📂 Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());



        $this->info('🌱 Running seeders...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->line(Artisan::output());

        // Storage link
        $this->info('🔗 Creating storage link...');
        Artisan::call('storage:link');
        $this->line(Artisan::output());

        $this->info('🧹 Clearing config cache...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        $this->info('🎉 Setup finished!');
        return 0;
    }

    protected function setEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $contents = file_get_contents($envPath);
        $pattern = "/^{$key}=.*$/m";
        if (preg_match($pattern, $contents)) {
            $contents = preg_replace($pattern, "{$key}={$value}", $contents);
        } else {
            $contents .= PHP_EOL . "{$key}={$value}" . PHP_EOL;
        }
        file_put_contents($envPath, $contents);
    }
}
