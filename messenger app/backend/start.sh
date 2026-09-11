#!/usr/bin/env sh
# Start-Skript für Produktion
export NODE_ENV=production
if [ -f ./.env ]; then
  # lade lokale env falls vorhanden
  set -a
  . ./.env
  set +a
fi
node index.js
