#!/usr/bin/env bash
#
# One-command deploy. This is the bootstrap half: it pulls the latest code,
# then hands over to scripts/publish.sh from the freshly pulled tree.
#
# It is installed to ~/deploy-purrquery.sh, OUTSIDE the checkout, on purpose.
# Bash reads a script as it executes it, so a script that git-resets its own
# file mid-run can end up executing spliced garbage.
#
# Run it from your machine:
#   ssh -p 65002 u783099422@145.79.4.158 'bash ~/deploy-purrquery.sh'
#
#   --branch X   deploy branch X instead of main
#   --migrate    also run database migrations
#
set -euo pipefail

REPO="https://github.com/coadingmasters/query.git"
SRC="$HOME/src/puurquery"

BRANCH=main
PUBLISH_ARGS=()
while [ $# -gt 0 ]; do
  case "$1" in
    --branch)  BRANCH="$2"; shift 2 ;;
    --migrate) PUBLISH_ARGS+=(--migrate); shift ;;
    *) echo "unknown flag: $1" >&2; exit 2 ;;
  esac
done

say() { printf '\n\033[1;36m==> %s\033[0m\n' "$1"; }

if [ ! -d "$SRC/.git" ]; then
  say "First run — cloning $REPO"
  mkdir -p "$(dirname "$SRC")"
  git clone "$REPO" "$SRC"
fi

say "Pulling $BRANCH"
cd "$SRC"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"
git clean -fd
echo "now at: $(git log -1 --format='%h %s')"

say "Handing over to publish.sh"
exec bash "$SRC/scripts/publish.sh" "${PUBLISH_ARGS[@]+"${PUBLISH_ARGS[@]}"}"
