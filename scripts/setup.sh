#!/usr/bin/env bash
#
# PuurQuery first-time setup.
# Creates the database, installs dependencies, and prepares the app to run.
#
# Usage:  ./scripts/setup.sh
#
set -euo pipefail

cd "$(dirname "$0")/.."

info() { printf '\n\033[1;34m==>\033[0m %s\n' "$1"; }
fail() { printf '\n\033[1;31mERROR:\033[0m %s\n' "$1" >&2; exit 1; }

# --- 1. Check required tooling -----------------------------------------------
info "Checking requirements"
for bin in php composer node npm; do
    command -v "$bin" >/dev/null || fail "'$bin' is not installed or not on your PATH."
done

php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);' \
    || fail "PHP 8.3+ is required. Found $(php -r 'echo PHP_VERSION;')."

for ext in pdo_mysql mbstring openssl curl gd zip fileinfo bcmath intl; do
    php -m | grep -qix "$ext" || fail "Missing required PHP extension: $ext"
done
echo "PHP $(php -r 'echo PHP_VERSION;'), Node $(node -v), all extensions present."

# --- 2. Create the .env file -------------------------------------------------
info "Preparing .env"
if [ -f .env ]; then
    echo ".env already exists, leaving it untouched."
else
    cp .env.example .env
    echo "Created .env from .env.example."
fi

# Read DB settings out of .env so this script and Laravel always agree.
env_get() { grep -E "^${1}=" .env | head -1 | cut -d= -f2- | tr -d '"'"'"'' ; }
DB_HOST=$(env_get DB_HOST); DB_PORT=$(env_get DB_PORT)
DB_NAME=$(env_get DB_DATABASE); DB_USER=$(env_get DB_USERNAME); DB_PASS=$(env_get DB_PASSWORD)

# --- 3. Create the database --------------------------------------------------
info "Creating database '${DB_NAME}'"
command -v mysql >/dev/null || fail "The 'mysql' client is not installed; create the database manually and re-run."

MYSQL_ARGS=(-h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER")
[ -n "$DB_PASS" ] && MYSQL_ARGS+=("-p${DB_PASS}")

mysql "${MYSQL_ARGS[@]}" -e \
    "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
    || fail "Could not connect to MySQL at ${DB_HOST}:${DB_PORT}. Is the server running, and are the DB_* values in .env correct?"
echo "Database ready."

# --- 4. Install dependencies and build --------------------------------------
info "Installing PHP dependencies"
composer install --no-interaction

info "Generating application key"
grep -qE '^APP_KEY=base64:' .env || php artisan key:generate

info "Running migrations"
php artisan migrate --no-interaction

info "Linking storage directory"
php artisan storage:link 2>/dev/null || true

info "Installing frontend dependencies"
npm install

info "Building frontend assets"
npm run build

printf '\n\033[1;32mSetup complete.\033[0m Start the dev server with:\n\n    composer dev\n\n'
