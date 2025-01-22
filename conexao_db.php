<?php
// Verifica se a função já está definida
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

// Definindo as variáveis de ambiente para as credenciais do banco de dados
$host = getenv('DB_HOST') ?: 'localhost'; // Host do banco de dados
$db   = getenv('DB_NAME') ?: 'mcb'; // Nome do banco de dados
$user = getenv('DB_USER') ?: 'root'; // Usuário do banco de dados
$pass = getenv('DB_PASS') ?: ''; // Senha do banco de dados
$charset = 'utf8mb4'; // Charset

// DSN (Data Source Name) para a conexão
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções de conexão
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false, // Desativa a emulação de prepared statements
];

try {
    // Tentativa de conexão ao banco de dados
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET NAMES '$charset'"); // Define o charset após a conexão
} catch (\PDOException $e) {
    // Registra o erro em um arquivo de log
    error_log($e->getMessage(), 3, '/var/log/my_app_errors.log'); // Altere o caminho conforme necessário
    die('Erro ao conectar ao banco de dados.'); // Mensagem genérica ao usuário
}
?>