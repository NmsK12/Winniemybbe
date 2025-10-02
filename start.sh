#!/bin/bash

# Verificar configuración de PHP
echo "=== Configuración de PHP ==="
php -i | grep -E "(upload_max_filesize|post_max_size|memory_limit|max_execution_time)"

# Crear directorio uploads si no existe
mkdir -p uploads
chmod 777 uploads

# Iniciar servidor PHP
echo "=== Iniciando servidor PHP ==="
php -c php.ini -S 0.0.0.0:$PORT -t .
