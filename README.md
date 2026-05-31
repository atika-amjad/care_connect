# Care Connect — Laravel Mental Wellness Platform

A bilingual (English / Roman Urdu) mental wellness web app built with **Laravel 12**, **SQLite**, **Tailwind CSS 4**, and **Alpine.js**. Converted from the original single-file HTML prototype with server-side persistence and production-ready hosting.

## Features

- **Dashboard** — streak, mood averages, journal & meditation stats, weekly chart
- **Mood Tracker** — emoji moods, energy/anxiety sliders, tags, notes
- **Journal** — guided prompts, word count, entry history
- **Therapists** — filterable directory, appointment booking (SQLite)
- **Meditation** — 4-7-8 breathing exercise + 6 guided sessions
- **Support Groups** — join/leave groups (persisted per user)
- **Crisis Help** — Pakistan helplines + coping shortcuts
- **Bilingual UI** — EN / اردو toggle with per-user language preference

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ & npm
- SQLite extension enabled in PHP

## Quick Setup (Local)

```bash
# 1. Install PHP dependencies
composer install

# 2. Environment & app key
copy .env.example .env        # Windows
# cp .env.example .env        # Linux/Mac
php artisan key:generate

# 3. Create SQLite database
type nul > database\database.sqlite   # Windows
# touch database/database.sqlite      # Linux/Mac

# 4. Migrate & seed
php artisan migrate --seed

# 5. Frontend assets
npm install
npm run build

# 6. Run
php artisan serve
```

Visit **http://127.0.0.1:8000** — enter your name on the setup screen to begin.

### One-command setup (Composer script)

```bash
composer setup
```

## Production Deployment

1. Upload project files to your server (exclude `node_modules`, keep `vendor` or run `composer install --no-dev` on server).
2. Set document root to **`public/`**.
3. Configure `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=sqlite
```

4. Run on server:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force --seed
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

5. Ensure `storage/` and `bootstrap/cache/` are writable by the web server.

### Apache (.htaccess included in `public/`)

Point your virtual host `DocumentRoot` to the `public` directory.

### Shared hosting

- Upload all files; some hosts require `public_html` — move `public/*` there and adjust `index.php` paths, or use a subdomain pointed at `public/`.

## Project Structure

```
app/
  Http/Controllers/     # Feature controllers
  Http/Middleware/      # User session + locale
  Models/               # Eloquent models
  Services/             # Dashboard stats logic
database/
  migrations/           # SQLite schema
  seeders/              # Therapists & support groups
lang/en|ur/app.php      # Translations
resources/views/        # Blade + Tailwind UI
routes/web.php          # All routes
```

## Database (SQLite)

| Table | Purpose |
|-------|---------|
| `users` | Name, language, meditation count |
| `mood_entries` | Daily mood logs |
| `journal_entries` | Journal content |
| `therapists` | Seeded therapist directory |
| `support_groups` | Seeded peer groups |
| `group_memberships` | User ↔ group joins |
| `appointments` | Therapist bookings |

## Legacy Prototype

The original single-file HTML app is preserved in `legacy/Care_Connect.html`.

## License

MIT — built for mental wellness awareness and education.
