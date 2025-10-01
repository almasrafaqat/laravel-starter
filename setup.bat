@echo off
echo 🚀 Running Laravel project setup...

:: Step 1: Install PHP dependencies
composer install
if %errorlevel% neq 0 (
  echo ❌ composer install failed. Make sure composer is installed and in PATH.
  pause
  exit /b %errorlevel%
)

:: Step 2: Run Laravel setup command
php artisan app:setup-project
if %errorlevel% neq 0 (
  echo ❌ artisan setup failed.
  pause
  exit /b %errorlevel%
)

:: Step 3: Install node modules (optional if frontend exists)
if exist package.json (
  echo 📦 Installing npm dependencies...
  npm install
)

echo 🎉 Project setup completed!
pause
