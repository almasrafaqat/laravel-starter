<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-database
        {name : Database name}
        {--user= : Database user}
        {--password= : Database password}
        {--host=127.0.0.1 : Database host}
        {--port=3306 : Database port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the MySQL database if it does not exist and runs migrations + seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbName = $this->argument('name') ?? config('database.connections.mysql.database');
        $dbUser = $this->option('user') ?? config('database.connections.mysql.username');
        $dbPass = $this->option('password') ?? config('database.connections.mysql.password');
        $dbHost = $this->option('host') ?? config('database.connections.mysql.host');
        $dbPort = $this->option('port') ?? config('database.connections.mysql.port');

        try {
            $this->info("🛢️  Checking if database '{$dbName}' exists...");
            $pdo = new PDO("mysql:host={$dbHost};port={$dbPort}", $dbUser, $dbPass ?: null);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $this->info("✅ Database '{$dbName}' is ready.");

            // Update Laravel DB config to use the new database
            config(['database.connections.mysql.database' => $dbName]);
            config(['database.connections.mysql.username' => $dbUser]);
            config(['database.connections.mysql.password' => $dbPass]);
            config(['database.connections.mysql.host' => $dbHost]);
            config(['database.connections.mysql.port' => $dbPort]);

            // Reconnect so migrations use the correct DB
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Clear cache to reload config
            Artisan::call('config:clear');
            // Artisan::call('cache:clear');

            // Run fresh migrations
            $this->info('📂 Running fresh migrations...');
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->line(Artisan::output());

            // Run seeders
            $this->info('🌱 Running seeders...');
            Artisan::call('db:seed', ['--force' => true]);
            $this->line(Artisan::output());

            $this->info('🎉 Database setup complete!');
        } catch (\Exception $e) {
            $this->error("❌ Could not create database: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
