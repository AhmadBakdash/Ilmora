# Ilmora — Project Setup & Dependency Audit Report

**Date:** 2026-04-15  
**Auditor:** Claude (Cowork)

---

## Summary Table

### Phase 1 — PHP / Composer

| Package / Item | Status Before | Status After | Notes |
|---|---|---|---|
| `laravel/breeze` | ❌ Missing | ⏳ Added to composer.json | Run install commands below |
| `laravel/sanctum` | ❌ Missing | ⏳ Added to composer.json | Ships with L11; explicit for clarity |
| `spatie/laravel-permission` | ❌ Missing | ⏳ Added to composer.json | Needs migrate + publish after install |
| `laravel/scout` | ❌ Missing | ⏳ Added to composer.json | Configure driver separately |
| `spatie/laravel-activitylog` | ❌ Missing | ⏳ Added to composer.json | Needs config:publish after install |
| `spatie/laravel-csp` | ❌ Missing | ⏳ Added to composer.json | Needs config:publish after install |
| `spatie/laravel-backup` | ❌ Missing | ⏳ Added to composer.json | Needs config:publish after install |
| `barryvdh/laravel-dompdf` | ❌ Missing | ⏳ Added to composer.json | Needs config:publish after install |
| `nunomaduro/larastan` | ❌ Missing | ⏳ Added to composer.json (dev) | `phpstan.neon` created |
| `pestphp/pest` | ❌ Missing | ⏳ Added to composer.json (dev) | With `pest-plugin-laravel` |
| `laravel/pint` | ✅ Present | ✅ No change | Dev dependency |
| `laravel/sail` | ✅ Present | ✅ No change | Dev dependency |
| `laravel/pail` | ✅ Present | ✅ No change | Dev dependency |
| `laravel/tinker` | ✅ Present | ✅ No change | — |
| `livewire/livewire` | ✅ Present | ✅ No change | ^3.0 |
| `config/queue.php` | ✅ Present | ✅ No change | Default = database |
| Eloquent Soft Deletes | ✅ Built-in | ✅ No change | Laravel built-in |

### Phase 2 — NPM / Frontend

| Package | Status Before | Status After | Notes |
|---|---|---|---|
| `alpinejs` | ✅ Present | ✅ No change | ^3.15.11 |
| `tailwindcss` | ✅ Present | ✅ No change | ^3.4.19 devDep |
| `@tailwindcss/forms` | ✅ Present | ✅ No change | devDep |
| `laravel-vite-plugin` | ✅ Present | ✅ No change | devDep |
| `vite` | ✅ Present | ✅ No change | devDep |
| `autoprefixer` | ✅ Present | ✅ No change | devDep |
| `postcss` | ✅ Present | ✅ No change | devDep |
| `axios` | ✅ Present | ✅ No change | devDep |
| `@tailwindcss/typography` | ❌ Missing | ⏳ Added to package.json | devDep |
| `chart.js` | ❌ Missing | ⏳ Added to package.json | dep |
| `@alpinejs/sort` | ❌ Missing | ⏳ Added to package.json | dep |
| `@alpinejs/focus` | ❌ Missing | ⏳ Added to package.json | dep |
| `@alpinejs/collapse` | ❌ Missing | ⏳ Added to package.json | dep |

### Phase 3 — Configuration Files

| File | Status Before | Status After | Notes |
|---|---|---|---|
| `tailwind.config.js` — typography | ❌ Missing plugin | ✅ Added | `@tailwindcss/typography` added |
| `tailwind.config.js` — content paths | ✅ Correct | ✅ No change | All 3 paths present |
| `tailwind.config.js` — forms plugin | ✅ Present | ✅ No change | — |
| `vite.config.js` | ✅ Correct | ✅ No change | CSS + JS entry points configured |
| `.env.example` — APP_LOCALE | ❌ Missing | ✅ Added (`ar`) | — |
| `.env.example` — APP_FALLBACK_LOCALE | ❌ Missing | ✅ Added (`en`) | — |
| `.env.example` — APP_FAKER_LOCALE | ❌ Missing | ✅ Added (`ar_SA`) | — |
| `.env.example` — CACHE_STORE | ❌ Missing | ✅ Added (`file`) | — |
| `.env.example` — QUEUE_CONNECTION | ⚠️ Was `sync` | ✅ Fixed to `database` | — |
| `.env.example` — MAIL_* vars | ❌ Missing | ✅ Added | All 7 mail vars added |
| `config/app.php` — locale | ⚠️ Was `en` | ✅ Fixed to `ar` | — |
| `config/app.php` — faker_locale | ⚠️ Was `en_US` | ✅ Fixed to `ar_SA` | — |
| `config/app.php` — timezone | ✅ UTC | ✅ No change | — |

### Phase 4 — Directory Structure

| Directory | Status |
|---|---|
| `app/Livewire/Schedule/` | ✅ Created |
| `app/Livewire/Halaqah/` | ✅ Created |
| `app/Livewire/Assignment/` | ✅ Created |
| `app/Livewire/Student/` | ✅ Created |
| `app/Livewire/Notification/` | ✅ Created |
| `app/Models/Concerns/` | ✅ Created |
| `app/Notifications/` | ✅ Created |
| `app/Policies/` | ✅ Created |
| `app/Services/` | ✅ Created |
| `app/Console/Commands/` | ✅ Created |
| `resources/views/layouts/` | ✅ Created |
| `resources/views/livewire/` | ✅ Already existed |
| `resources/views/components/` | ✅ Already existed |
| `resources/views/emails/` | ✅ Created |
| `database/seeders/` | ✅ Already existed |
| `database/factories/` | ✅ Already existed |
| `tests/Feature/Auth/` | ✅ Created |
| `tests/Feature/Halaqah/` | ✅ Created |
| `tests/Feature/Schedule/` | ✅ Created |
| `tests/Feature/Assignment/` | ✅ Created |
| `tests/Unit/` | ✅ Already existed |
| `docs/` | ✅ Already existed |
| `lang/` | ✅ Created |

### Phase 5 — Foundation Files

| File | Status |
|---|---|
| `lang/ar.json` | ✅ Created |
| `lang/en.json` | ✅ Created |
| `lang/de.json` | ✅ Created |
| `lang/tr.json` | ✅ Created |
| `lang/ur.json` | ✅ Created |
| `lang/ms.json` | ✅ Created |
| `lang/fr.json` | ✅ Created |
| `app/Models/Concerns/BelongsToTenant.php` | ✅ Created |
| `app/Http/Middleware/SetLocale.php` | ✅ Created |
| `phpstan.neon` | ✅ Created |
| `CLAUDE.md` | ✅ Created |

---

## Commands to Run in Your Terminal

Open a terminal in `C:\Users\yk358\Ilmora` and run these in order:

### Step 1 — Install all Composer packages

```bash
composer install
```

### Step 2 — Install Breeze (Blade stack)

```bash
php artisan breeze:install blade
```

### Step 3 — Publish Spatie Permission config + migrate

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Step 4 — Publish Spatie Activity Log config

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan migrate
```

### Step 5 — Publish Spatie CSP config

```bash
php artisan vendor:publish --provider="Spatie\Csp\CspServiceProvider" --tag="csp-config"
```

### Step 6 — Publish Spatie Backup config

```bash
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

### Step 7 — Publish DomPDF config

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Step 8 — Publish Scout config

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

### Step 9 — Queue and notification migrations

```bash
php artisan queue:table
php artisan notifications:table
php artisan migrate
```

### Step 10 — Publish lang files (if needed)

```bash
php artisan lang:publish
```

### Step 11 — Install NPM packages

```bash
npm install
```

### Step 12 — Register SetLocale middleware

Add `\App\Http\Middleware\SetLocale::class` to your `bootstrap/app.php` middleware stack:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
})
```

### Step 13 — Pest initialisation (optional, replaces PHPUnit)

```bash
./vendor/bin/pest --init
```

### Step 14 — Final verification

```bash
composer validate
composer audit
npm audit
php artisan about
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan test
./vendor/bin/pint --test
./vendor/bin/phpstan analyse
```

---

## Manual Steps Required After Installation

1. **SetLocale Middleware** — Register it in `bootstrap/app.php` (see Step 12 above).
2. **Spatie Permission** — Add `HasRoles` trait to `App\Models\User`.
3. **Activity Log** — Add `LogsActivity` trait to models you want to audit.
4. **BelongsToTenant** — Add `BelongsToTenant` trait to all tenant-scoped models and ensure they have a `tenant_id` column in their migrations.
5. **Scout Driver** — Set `SCOUT_DRIVER` in `.env` when you choose a search backend (e.g., `meilisearch`, `algolia`, or `database`).
6. **CSP Policy** — Extend `Spatie\Csp\Policies\Basic` to create your custom policy class and reference it in `config/csp.php`.
7. **Backup config** — Set `BACKUP_DISK` and configure notification channels in `config/backup.php`.
