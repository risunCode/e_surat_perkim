# Development Workflow E-Surat Perkim

## Quick Start

### 1. Clone & Install
```bash
git clone <repository-url>
cd e-surat-perkim

composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
php artisan signature:generate
```

### 3. Database Setup
```bash
# Buat database MySQL terlebih dahulu
# CREATE DATABASE e_surat_perkim;

php artisan migrate
php artisan db:seed
```

### 4. Storage Link
```bash
php artisan storage:link
```

### 5. Build & Run
```bash
# Development (dengan hot reload)
composer dev

# Atau manual:
php artisan serve
npm run dev
```

---

## Composer Scripts

| Script | Command | Deskripsi |
|--------|---------|-----------|
| `setup` | `composer setup` | Full setup (install, env, migrate, build) |
| `dev` | `composer dev` | Run dev server + queue + logs + vite |
| `serve` | `composer serve` | Run Laravel server only |
| `test` | `composer test` | Run PHPUnit tests |

### composer dev
Menjalankan 4 proses secara bersamaan:
- `php artisan serve` - Laravel server
- `php artisan queue:listen` - Queue worker
- `php artisan pail` - Real-time logs
- `npm run dev` - Vite dev server

---

## Artisan Commands

### Built-in Commands
```bash
# Migration
php artisan migrate                    # Run migrations
php artisan migrate:fresh              # Drop all & re-migrate
php artisan migrate:fresh --seed       # Drop, migrate, seed

# Seeding
php artisan db:seed                    # Run all seeders
php artisan db:seed --class=UserSeeder # Run specific seeder

# Cache
php artisan optimize                   # Cache config, routes, views
php artisan optimize:clear             # Clear all caches
php artisan config:clear               # Clear config cache
php artisan route:clear                # Clear route cache
php artisan view:clear                 # Clear view cache

# Storage
php artisan storage:link               # Create storage symlink
```

### Custom Commands
```bash
# Generate SIGNATURE_KEY
php artisan signature:generate

# Sync signatures untuk surat tanpa signature
php artisan letters:sync-signatures

# Force regenerate semua signatures
php artisan letters:sync-signatures --force
```

---

## Database Seeding

### Default Seeders
```bash
php artisan db:seed
```

Menjalankan:
1. `ClassificationSeeder` - Klasifikasi surat default
2. `LetterStatusSeeder` - Status disposisi default
3. `ConfigSeeder` - Konfigurasi aplikasi default
4. `UploadConfigSeeder` - Konfigurasi upload

### SQL Files (Alternative)
```bash
# Full database dengan data sample
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_full.sql

# Admin user only
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_firstadmin.sql

# Reference codes
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_references.sql
```

---

## Testing

### Run Tests
```bash
# All tests
php artisan test

# Specific test file
php artisan test --filter=ExampleTest

# With coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/           # Feature/integration tests
│   └── ExampleTest.php
├── Unit/              # Unit tests
│   └── ExampleTest.php
└── TestCase.php       # Base test class
```

---

## Code Style

### Laravel Pint
```bash
# Check code style
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint
```

---

## Debugging

### Laravel Pail (Real-time Logs)
```bash
php artisan pail
php artisan pail --timeout=0  # No timeout
```

### Log Files
```
storage/logs/laravel.log
```

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

## Production Deployment

### 1. Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Optimize
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan optimize
```

### 3. Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Queue Worker (Supervisor)
```ini
[program:e-surat-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
```

---

## Ngrok Deployment

### 1. Update .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-subdomain.ngrok-free.dev

SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

### 2. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Run
```bash
# Terminal 1
php artisan serve --host=127.0.0.1 --port=8000

# Terminal 2
ngrok http 8000 --subdomain=your-subdomain
```

---

## LAN Hosting

### 1. Find IP
```bash
# Windows
ipconfig

# Linux/Mac
ip addr
```

### 2. Run Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 3. Firewall (Windows)
```powershell
netsh advfirewall firewall add rule name="Laravel" dir=in action=allow protocol=tcp localport=8000
```

### 4. Access
```
http://192.168.x.x:8000
```

---

## Troubleshooting

### Storage Permission Error
```bash
chmod -R 775 storage bootstrap/cache
```

### Class Not Found
```bash
composer dump-autoload
```

### View Not Found
```bash
php artisan view:clear
```

### Session Issues
```bash
php artisan session:table
php artisan migrate
```

### CSRF Token Mismatch
```bash
php artisan cache:clear
php artisan config:clear
```
