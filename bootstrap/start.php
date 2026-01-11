<?php
// bootstrap/start.php
if (!env('APP_KEY')) {
    echo "❌ APP_KEY não configurada!";
    exit(1);
}