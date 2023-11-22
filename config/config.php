
<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Informações de conexão com o banco de dados.
$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];
$port = $_ENV['DB_PORT'];

// Estabelece uma conexão com o servidor MySQL usando a classe mysqli. 
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verifica se a conexão foi bem-sucedida e se não houve erros.
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error); // Em caso de erro na conexão, exibe uma mensagem de erro e encerra o script.
} else {
    // Se a conexão foi bem-sucedida, mostramos um log no console do navegador.
    // echo '<script>console.log("Conexão bem-sucedida config!");</script>';
}

// Retorna a conexão
return $conn;
?>
