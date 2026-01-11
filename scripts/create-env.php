<?php
// scripts/create-env.php

$envPath = __DIR__ . '/../.env';

// Conteúdo do .env baseado nas variáveis de ambiente
$content = <<<ENV
APP_NAME="Controle Financeiro"
APP_ENV=production
APP_DEBUG=true
APP_KEY={$_SERVER['APP_KEY']}
APP_URL=https://controle-financeiro-1-jc96.onrender.com

DB_CONNECTION=pgsql
DB_HOST={$_SERVER['DB_HOST']}
DB_PORT=5432
DB_DATABASE={$_SERVER['DB_DATABASE']}
DB_USERNAME={$_SERVER['DB_USERNAME']}
DB_PASSWORD={$_SERVER['DB_PASSWORD']}

PGSSLMODE=require
LOG_CHANNEL=stderr
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ENV;

file_put_contents($envPath, $content);
echo "✅ .env criado em: $envPath\n";