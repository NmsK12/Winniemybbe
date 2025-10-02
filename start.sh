#!/bin/bash

# Configurar variables de entorno para PHP
export PHP_INI_SCAN_DIR="/app"

# Verificar configuración de PHP
echo "=== Configuración de PHP ==="
php -i | grep -E "(upload_max_filesize|post_max_size|memory_limit|max_execution_time)"

# Crear directorio uploads si no existe
mkdir -p uploads
chmod 777 uploads

# Iniciar servidor PHP con configuración forzada
echo "=== Iniciando servidor PHP ==="
php -d upload_max_filesize=20M -d post_max_size=20M -d max_execution_time=300 -d memory_limit=256M -d max_input_time=300 -S 0.0.0.0:$PORT -t .
