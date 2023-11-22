<!-- mao_amiga\app\views\perfil_view.php -->

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
$perfis = $userController->getPerfisByUserId($user_id);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
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
    <script>
        // Adicione um ouvinte de eventos para o clique no botão de logout
        document.getElementById('logoutBtn').addEventListener('click', function () {
            // Use AJAX para chamar o arquivo de logout.php no lado do servidor
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './../../scripts/logout.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Redirecione para a página de login após o logout
                    window.location.href = 'index.php';
                }
            };
            xhr.send();
        });
    </script>
</head>

<body>
    <!-- Navbar usando Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Friendly Hand</a>
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
                        <a class="nav-link" href="#">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./encontrar_usuarios.php">Outros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutBtn" onclick="logout()">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if ($perfis): ?>
            <h1 class="mb-4">Meus Perfis Profissionais</h1>

            <div class="row">
                <?php $index = 1; ?>
                <?php foreach ($perfis as $perfil): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <?php exibirPerfil($perfil, $index); ?>
                        </div>
                    </div>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p class="lead">Nenhum perfil encontrado.</p>
        <?php endif; ?>

        <!-- Adicione um botão ou link para adicionar um novo perfil -->
        <div class="text-center mt-4">
            <a href="./adicionar_perfil.php" class="btn btn-primary">Adicionar Novo Perfil</a>
        </div>
    </div>

<?php
    function exibirPerfil($perfil, $index)
    {
        ?>
        <div class="card-body">
            <h5 class="card-title">Perfil Profissional <?php echo $index; ?></h5>
            <p class="card-text"><strong>Descrição:</strong> <?php echo $perfil['descricao']; ?></p>
            <p class="card-text"><strong>Interesses:</strong> <?php echo $perfil['interesses']; ?></p>
            <p class="card-text"><strong>Disponibilidade:</strong> <?php echo $perfil['disponibilidade']; ?></p>
        </div>
        <?php
    }
?>
    
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
