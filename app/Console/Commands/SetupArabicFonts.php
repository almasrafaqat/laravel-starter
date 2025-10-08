<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupArabicFonts extends Command
{
    protected $signature = 'setup:arabic-fonts';
    protected $description = 'Setup Arabic fonts for PDF generation';

    public function handle()
    {
        // Use storage path instead of public path
        $fontsPath = storage_path('fonts');

        if (!File::exists($fontsPath)) {
            File::makeDirectory($fontsPath, 0755, true);
            $this->info("Created fonts directory at: {$fontsPath}");
        }

        // List of Arabic fonts to copy
        $fonts = [
            'XB-Riyaz.ttf',
            'Almarai-Regular.ttf',
            'Almarai-Bold.ttf',
            'Jersey15-Regular.ttf'
        ];

        $copiedFonts = [];

        foreach ($fonts as $font) {
            $sourcePath = resource_path("fonts/{$font}");
            $destinationPath = storage_path("fonts/{$font}");

            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destinationPath);
                // Make sure the individual font file has correct permissions
                chmod($destinationPath, 0644);
                $copiedFonts[] = $font;
            } else {
                $this->warn("Font file {$font} not found in resources/fonts.");
            }
        }

        // Set proper permissions on the fonts directory
        $this->info('Setting proper permissions on fonts directory...');

        // More robust permission setting that works in both environments
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows doesn't use the same permission system
            $this->info('Windows detected - skipping Unix permissions');
        } else {
            // Unix/Linux/MacOS
            $this->executeCommand("chmod -R 755 {$fontsPath}");

            // Try to detect web server user
            $webUser = $this->detectWebServerUser();
            if ($webUser) {
                $this->info("Detected web server user: {$webUser}");
                $this->executeCommand("chown -R {$webUser} {$fontsPath}");
            }
        }

        if (!empty($copiedFonts)) {
            $this->info('The following Arabic fonts have been set up successfully: ' . implode(', ', $copiedFonts));
            $this->info('Fonts installed to: ' . $fontsPath);

            // Clear any cached font data
            $this->info('Clearing font cache...');
            $this->clearFontCache($fontsPath);
        } else {
            $this->error('No fonts were copied. Please ensure the font files exist in resources/fonts.');
        }

        // Remind about config
        $this->info('Remember to publish and update the DomPDF config if you haven\'t already:');
        $this->info('php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"');
    }

    /**
     * Execute a shell command safely
     */
    protected function executeCommand($command)
    {
        try {
            $process = proc_open($command, [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ], $pipes);

            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);

            foreach ($pipes as $pipe) {
                fclose($pipe);
            }

            proc_close($process);

            if ($error) {
                $this->warn("Command error: {$error}");
            }

            return $output;
        } catch (\Exception $e) {
            $this->warn("Failed to execute command: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Try to detect the web server user
     */
    protected function detectWebServerUser()
    {
        // Common web server users by OS
        $possibleUsers = [
            'www-data',    // Debian/Ubuntu
            'apache',      // CentOS/RHEL
            'nginx',       // Some nginx configs
            'http',        // Arch Linux
            'nobody',      // Common fallback
            get_current_user() // Current user (might be correct for local dev)
        ];

        foreach ($possibleUsers as $user) {
            if ($this->userExists($user)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Check if a user exists on the system
     */
    protected function userExists($user)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return false;
        }

        $result = $this->executeCommand("id {$user} 2>/dev/null");
        return !empty($result);
    }

    /**
     * Clear any cached font data
     */
    protected function clearFontCache($fontsPath)
    {
        // Clear any .ufm files which might be causing issues
        $ufmFiles = glob("{$fontsPath}/*.ufm");
        foreach ($ufmFiles as $file) {
            if (File::exists($file)) {
                File::delete($file);
                $this->info("Deleted cached font file: " . basename($file));
            }
        }
    }

}
