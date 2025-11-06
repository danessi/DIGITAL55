#!/bin/bash
set -e

echo "=== DIGITAL55 Laravel Startup ==="

if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "vendor/ no encontrado → instalando Composer..."
    composer install --no-dev --optimize-autoloader --no-interaction --no-progress
else
    echo "vendor/ ya existe → saltando instalación"
fi

echo "Iniciando Laravel en http://localhost:8000"
exec "$@"
