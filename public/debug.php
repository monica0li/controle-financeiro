<?php
// Ativar todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Debug do Sistema</h1>";

// Testar APP_KEY
echo "<h3>1. Variáveis de Ambiente:</h3>";
echo "APP_KEY: " . (getenv('APP_KEY') ? '✅ Configurada' : '❌ FALTANDO') . "<br>";
echo "APP_DEBUG: " . getenv('APP_DEBUG') . "<br>";
echo "DB_HOST: " . getenv('DB_HOST') . "<br>";

// Testar banco de dados
echo "<h3>2. Teste de Banco de Dados:</h3>";
try {
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    
    echo "Tentando conectar: pgsql:host=$host;dbname=$dbname<br>";
    
    $pdo = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Conexão bem-sucedida!<br>";
    echo "Versão do PostgreSQL: " . $pdo->query('SELECT version()')->fetchColumn() . "<br>";
    
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
}

// Testar extensões PHP
echo "<h3>3. Extensões PHP:</h3>";
echo "PDO: " . (extension_loaded('pdo') ? '✅' : '❌') . "<br>";
echo "PDO_PGSQL: " . (extension_loaded('pdo_pgsql') ? '✅' : '❌') . "<br>";
echo "pgsql: " . (extension_loaded('pgsql') ? '✅' : '❌') . "<br>";

// Testar arquivos Laravel
echo "<h3>4. Arquivos do Laravel:</h3>";
echo ".env: " . (file_exists(__DIR__ . '/../.env') ? '✅' : '❌') . "<br>";
echo "storage writable: " . (is_writable(__DIR__ . '/../storage') ? '✅' : '❌') . "<br>";
echo "vendor/autoload.php: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? '✅' : '❌') . "<br>";
echo "bootstrap/app.php: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? '✅' : '❌') . "<br>";