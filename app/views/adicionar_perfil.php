<?php
// Inclua o arquivo do controlador do perfil
include __DIR__ . '/../controllers/PerfilController.php';

// Inicie a sessão
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

// Obtém o ID do usuário autenticado
$user_id = $_SESSION['user_id'];

// Consulta o perfil do usuário
$userController = new PerfilController();
$perfil = $userController->getPerfilById($user_id);

// Verifica se o formulário de adicionar perfil foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $descricao = $_POST["descricao"];
    $interesses = $_POST["interesses"];
    $disponibilidade = $_POST["disponibilidade"];

    // Chama a função para adicionar o perfil e armazena o resultado
    $addPerfilResult = $userController->adicionarPerfil($user_id, $descricao, $interesses, $disponibilidade);

    // Verifica o status da adição do perfil
    if ($addPerfilResult['status'] === 'success') {
        // Redireciona para a página de perfil ou exibe uma mensagem de sucesso
        header("Location: perfil_view.php");
        exit();
    } else {
        // Armazena a mensagem de erro na sessão
        $_SESSION['add_perfil_error'] = $addPerfilResult['message'];
    }
}

// Recupere a mensagem de erro, se houver
$addPerfilErrorMessage = isset($_SESSION['add_perfil_error']) ? $_SESSION['add_perfil_error'] : '';

// Limpe a variável de sessão
unset($_SESSION['add_perfil_error']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Perfil</title>
    <!-- Adicione os estilos do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicione seus estilos CSS personalizados aqui -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h1 {
            color: #343a40;
        }

        p {
            color: #6c757d;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .navbar {
            background-color: #343a40;
            color: white;
        }

        .navbar a {
            color: black !important;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Navbar usando Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="./user_view.php">Friendly Hand</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./user_view.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./perfil_view.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./encontrar_usuarios.php">Outros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Adicionar Perfil</h1>

        <!-- Exiba a mensagem de erro, se houver -->
        <?php if ($addPerfilErrorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $addPerfilErrorMessage; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de adicionar perfil -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="interesses" class="form-label">Interesses</label>
                <input type="text" class="form-control" id="interesses" name="interesses" required>
            </div>
            <div class="mb-3">
                <label for="disponibilidade" class="form-label">Disponibilidade</label>
                <input type="text" class="form-control" id="disponibilidade" name="disponibilidade" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Perfil</button>
        </form>
    </div>

    <!-- Adicione os scripts do Bootstrap (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
