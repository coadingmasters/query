# PuurQuery

A Laravel-based blog and online tools platform.

PuurQuery combines an SEO-focused blog with a suite of free web tools. It is
server-rendered with Blade so that every page is fully indexable, which keeps
organic search traffic — the site's primary audience channel — as fast and
crawlable as possible.

**Stack:** Laravel 13 · PHP 8.3+ · MySQL/MariaDB · Tailwind CSS 4 · Alpine.js · Vite

---

## Requirements

| Requirement | Version | Notes |
|---|---|---|
| PHP | 8.3+ | 8.4 recommended |
| Composer | 2.x | |
| Node.js | 20+ | Includes npm |
| MySQL or MariaDB | 8.0+ / 10.4+ | |

### Required PHP extensions

```
pdo_mysql   mbstring   openssl   curl
gd          zip        fileinfo  bcmath    intl
```

Check what you have with `php -m`. On Debian/Ubuntu, install any missing ones with:

```bash
sudo apt install php8.4-{mysql,mbstring,curl,gd,zip,bcmath,intl}
```

### Optional system tools

These are only needed for specific file-processing tools, and the app runs
without them:

| Tool | Enables |
|---|---|
| `ffmpeg` | Video and audio conversion tools |
| `ghostscript` | PDF compression |

---

## Installation

### Quick start

Clone the repository, then run the setup script. It checks your environment,
creates the database, installs everything, and builds the assets:

```bash
git clone <repository-url> PuurQuery
cd PuurQuery
./scripts/setup.sh
```

If your database credentials differ from the defaults, copy `.env.example` to
`.env` and edit the `DB_*` values **before** running the script — it reads the
connection details from your `.env`.

### Manual installation

If you would rather run the steps yourself:

**1. Install PHP dependencies**

```bash
composer install
```

**2. Create your environment file**

```bash
cp .env.example .env
php artisan key:generate
```

**3. Create the database**

```bash
mysql -u root -e "CREATE DATABASE puurquery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Then set the connection details in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=puurquery
DB_USERNAME=root
DB_PASSWORD=
```

**4. Run the migrations**

```bash
php artisan migrate
```

**5. Link the storage directory**

Makes uploaded and generated files publicly reachable:

```bash
php artisan storage:link
```

**6. Build the frontend**

```bash
npm install
npm run build
```

---

## Running the app

```bash
composer dev
```

This starts the web server, queue worker, log viewer, and Vite dev server
together, and serves the site at <http://localhost:8000>.

To run pieces individually:

```bash
php artisan serve        # Web server only
npm run dev              # Vite with hot reload
php artisan queue:listen  # Queue worker
php artisan pail         # Live application logs
```

---

## Common commands

```bash
composer test            # Run the test suite
./vendor/bin/pint        # Format code to Laravel's style
php artisan migrate:fresh # Drop everything and re-migrate (destroys all data)
php artisan tinker       # Interactive REPL
php artisan about        # Show environment and config summary
```

---

## Troubleshooting

**`could not find driver`**
The `pdo_mysql` extension is missing. Install it and restart PHP.

**`Can't connect to local server through socket '/run/mysqld/mysqld.sock'`**
Your MySQL server is listening on TCP rather than that Unix socket — common
with XAMPP/LAMPP. Use `DB_HOST=127.0.0.1` rather than `localhost` in `.env`,
since `localhost` makes the driver prefer the socket.

**`SQLSTATE[HY000] [1045] Access denied`**
The `DB_USERNAME` / `DB_PASSWORD` in `.env` don't match your database user.

**Styles or scripts don't load**
Run `npm run build`, or start `npm run dev` if you want hot reloading.

**Changes to `.env` seem to be ignored**
Clear the cached config: `php artisan config:clear`.

---

## Project structure

```
app/
  Http/Controllers/   Request handling
  Models/             Eloquent models
resources/
  views/              Blade templates
  css/app.css         Tailwind entry point
  js/app.js           Alpine entry point
routes/web.php        Web routes
database/migrations/  Schema history
scripts/setup.sh      First-time setup
```
