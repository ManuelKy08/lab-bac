#!/usr/bin/env bash
# ============================================================
# LAB BAC - Peluncur (Tugas Kuliah) — kikikokok
# Cara pakai:
#   ./jalankan.sh            # import DB + nyalakan server :8090
#   ./jalankan.sh stop       # matikan server
# ============================================================
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"
PORT=8090

if [ "$1" = "stop" ]; then
  if command -v fuser >/dev/null 2>&1; then
    fuser -k "${PORT}/tcp" 2>/dev/null || true
  else
    pkill -f "php -S 127.0.0.1:${PORT}" 2>/dev/null || true
  fi
  echo "[lab-bac] server mati."
  exit 0
fi

echo "[lab-bac] import database lab_bac ..."
mariadb -uroot < "$DIR/database/lab_bac.sql"

echo "[lab-bac] jalankan http://127.0.0.1:$PORT ..."
nohup setsid php -S "127.0.0.1:$PORT" -t "$DIR" > "$DIR/php-server.log" 2>&1 < /dev/null &
sleep 2
curl -s -o /dev/null -w "[lab-bac] cek server: HTTP %{http_code}\n" "http://127.0.0.1:$PORT/index.php"
echo "[lab-bac] selesai. Buka http://127.0.0.1:$PORT"