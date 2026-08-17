#!/usr/bin/env bash
#
# Publishes the checked-out code to the live document root. Runs on the
# server and is invoked by ~/deploy-purrquery.sh after it has pulled, so by
# the time this runs the working tree is already at the target commit.
#
#   --migrate   also run database migrations (off by default: the launch
#               site uses file-based cache/session and needs no database)
#
set -euo pipefail

SRC="$HOME/src/puurquery"
APP="$HOME/domains/purrquery.com/public_html"

# The account default is PHP 8.3 and there is no CLI selector on this plan,
# so the binary is addressed directly to match the handler in .htaccess.
PHP=/opt/alt/php84/usr/bin/php
COMPOSER=/usr/local/bin/composer

MIGRATE=0
[ "${1:-}" = "--migrate" ] && MIGRATE=1

say() { printf '\n\033[1;36m==> %s\033[0m\n' "$1"; }

# --- preflight ------------------------------------------------------------
# The whole app sits inside public_html, so .htaccess is what routes requests
# into public/ and blocks .env. Publishing without it would expose the app
# internals, so stop rather than ship a broken, leaky site.
[ -f "$SRC/.htaccess" ] || { echo "FATAL: .htaccess missing from the repo — refusing to publish." >&2; exit 1; }

# Assets are built on the developer's machine and committed, because Vite 8
# uses Rolldown and CloudLinux's process limits stop it starting a thread
# pool here. Without the manifest every page would render unstyled.
[ -f "$SRC/public/build/manifest.json" ] || { echo "FATAL: public/build/manifest.json missing — run 'npm run build' and commit it." >&2; exit 1; }

[ -f "$APP/.env" ] || { echo "FATAL: $APP/.env is missing — create it before deploying." >&2; exit 1; }

# --- publish --------------------------------------------------------------
# The excludes keep live-only state safe: the .env, composer's vendor/,
# runtime caches and logs, and anything uploaded under public/storage.
say "Publishing to $APP"
rsync -a --delete --info=stats1 \
  --filter='P .htaccess' \
  --exclude '.git' --exclude '.github' --exclude 'node_modules' \
  --exclude '.env' --exclude '.env.*' --exclude 'vendor' --exclude 'tests' \
  --exclude 'scripts' \
  --exclude 'storage/logs/*' \
  --exclude 'storage/framework/cache/*' \
  --exclude 'storage/framework/sessions/*' \
  --exclude 'storage/framework/views/*' \
  --exclude 'storage/app/public/*' \
  --exclude 'public/storage' --exclude 'public/hot' \
  "$SRC/" "$APP/"

cd "$APP"

say "composer install"
$PHP $COMPOSER install --no-dev --optimize-autoloader --no-interaction --prefer-dist

say "runtime directories"
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs storage/app/public
chmod -R 775 storage bootstrap/cache

# Uploaded images are served through this symlink. rsync excludes it (so a
# deploy never deletes uploads), which also means it is never created by a
# deploy — without this, every uploaded image 404s.
if [ ! -L public/storage ]; then
    say "linking public/storage"
    $PHP artisan storage:link
fi

if [ "$MIGRATE" = "1" ]; then
    say "migrations"
    $PHP artisan migrate --force
fi

# config:cache must come after the .env is in place; route/view caches keep
# per-request work down.
say "caches"
$PHP artisan optimize:clear
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

say "Live -> https://purrquery.com"
