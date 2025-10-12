#!/usr/bin/env bash
set -euo pipefail

echo "🚀 Build: install PHP dependencies (prod)…"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "✅ Build done (no artisan at build time)"
