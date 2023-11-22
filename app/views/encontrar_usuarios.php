<!-- encontrar_usuarios.php -->

<?php
// Inclua o arquivo do controlador do usuário
include __DIR__ . '/../controllers/UserController.php';
include __DIR__ . '/../../config/config.php';

// Inicie a sessão
session_start();

// Crie uma instância do controlador do usuário
$userController = new UserController($conn);

// Obtenha todos os usuários com perfis
$users = $userController->getAllUsersWithProfiles();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outros Usuários</title>
    <!-- Adicione os estilos do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicione seus estilos CSS personalizados aqui -->
    <style>
        body {
            background-image: url('https://images.pexels.com/photos/7457629/pexels-photo-7457629.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            background-size: cover;
            background-position: center top;
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-attachment: fixed; /* Mantém a imagem de fundo fixa ao rolar */
        }

        h1 {
            color: #343a40;
        }

        p {
            color: black;
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
            border-radius: 5px;
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

        /* Adicione um estilo para o botão ficar no centro */
        .center-btn {
            display: flex;
            justify-content: center;
            margin-top: 15px;
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
                        <a class="nav-link" href="#">Outros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutBtn" onclick="logout()">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mb-4">Encontrar Usuários</h1>

        <?php if ($users): ?>
            <!-- Exibir a lista de usuários -->
            <ul class="list-group">
                <?php foreach ($users as $user): ?>
                    <li class="list-group-item">
                        <strong>Nome:</strong> <?php echo $user['username']; ?><br>
                        <strong>Descrição:</strong> <?php echo $user['descricao'] ?? 'N/A'; ?><br>
                        <strong>Interesses:</strong> <?php echo $user['interesses'] ?? 'N/A'; ?><br>
                        <strong>Disponibilidade:</strong> <?php echo $user['disponibilidade'] ?? 'N/A'; ?><br>
                        <!-- Adicione mais informações conforme necessário -->
                        <a href="./conversa.php?iddestinatario=<?php echo $user['iduser']; ?>">Conversar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <!-- Exiba uma mensagem indicando que nenhum usuário foi encontrado -->
            <p class="lead">Nenhum usuário encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Adicione os scripts do Bootstrap (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para logout
        function logout() {
            // Use AJAX para chamar o arquivo de logout.php no lado do servidor
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './../../scripts/logout.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Redirecione para a página de login após o logout
                    window.location.href = './../../index.php';
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>
