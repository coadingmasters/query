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

## Deploying to production

`.env.example` holds **local development** defaults. On a production server the
following values must be changed, and several of them directly affect SEO:

```dotenv
APP_ENV=production
APP_DEBUG=false                      # never true in production: leaks stack traces
APP_URL=https://yourdomain.com       # exact canonical origin, https, no trailing slash
SESSION_SECURE_COOKIE=true           # cookies over HTTPS only
LOG_STACK=daily
LOG_LEVEL=warning
MAIL_MAILER=smtp                     # plus real MAIL_HOST / MAIL_USERNAME / MAIL_PASSWORD
DB_USERNAME=                         # a dedicated user, not root
DB_PASSWORD=                         # a strong password
```

`APP_URL` is the single most important of these. Canonical tags, sitemap
entries and feed links are all generated from it, so it must be the exact
origin you want indexed — matching your choice of `www` vs non-`www`, over
`https`, with no trailing slash. A mismatch makes search engines treat the
same page as several different URLs and splits its ranking signals.

Then cache the configuration for speed and run the migrations:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
```

Re-run the `*:cache` commands after any config change — cached config ignores
edits to `.env`.

### Deploying purrquery.com

The live site runs on Hostinger shared hosting. Deploy with one command:

```bash
ssh -p 65002 u783099422@145.79.4.158 'bash ~/deploy-purrquery.sh'
```

That pulls `main`, then hands over to `scripts/publish.sh`, which rsyncs the
tree into the document root, installs Composer dependencies and rebuilds the
caches. Pushing to `main` runs the same thing through
`.github/workflows/deploy.yml`.

Three details of this host are worth knowing before changing anything:

- **The app lives inside `public_html`.** The document root cannot be moved to
  `public/`, so the root `.htaccess` is what rewrites requests into `public/`
  and blocks `.env`, `artisan` and the Composer files. Deleting it exposes the
  application internals.
- **Assets are committed to git.** Vite 8 bundles with Rolldown, and
  CloudLinux's process limits stop it from starting a thread pool, so
  `npm run build` cannot run on the server. Build locally (or let CI do it)
  and commit `public/build`. `scripts/publish.sh` refuses to deploy without a
  manifest rather than publishing an unstyled site.
- **The launch page needs no database.** Cache, session and queue all run on
  the filesystem, so migrations are skipped unless you pass `--migrate`.

Deploys authenticate with a dedicated SSH key rather than the account
password, so rotating the password does not break them.

### Behaviour that changes automatically in production

Setting `APP_ENV=production` switches on three safeguards, defined in
`app/Providers/AppServiceProvider.php`:

| Safeguard | Effect |
|---|---|
| Forced HTTPS URLs | All generated URLs use `https://`, so canonicals and sitemaps never emit `http://` duplicates |
| Destructive commands blocked | `migrate:fresh`, `db:wipe` and similar refuse to run against live content |
| Relaxed Eloquent strictness | Strict mode stays on outside production, catching N+1 queries during development |

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
