#!/bin/bash

# Jalankan migrasi database (opsional, karena sudah dijalankan di Procfile/railway.toml)
# php artisan migrate --force

# Buat symlink storage
php artisan storage:link

# Ubah permission folder storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo "Post-deployment tasks completed!" 