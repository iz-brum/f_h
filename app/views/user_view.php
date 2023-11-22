<?php 
// Inicie a sessão
session_start();

// Verifica se o ID do usuário está presente na sessão e se a chave 'username' está definida
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Se não houver ID do usuário ou 'username', redireciona de volta para o login
    header("Location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>

    <!-- Adicione as linhas Bootstrap no head -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-image: url('https://images.pexels.com/photos/7457629/pexels-photo-7457629.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            background-size: cover;
            background-position: center top;
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        .navbar {
            background-color: #343a40;
            color: white;
            border-radius: 5px;
        }

        .navbar a {
            color: black !important;
        }

        h1, p {
            color: #9a26e9;
            font-weight: bolder;
        }

        h1{
            margin-top: 250px;
            font-size: 100px;
        }
    </style>
</head>
<body>

    <!-- Navbar usando Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Friendly Hand</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./perfil_view.php">Perfil</a>
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

    <!-- Conteúdo da Página -->
    <div class="container mt-4">
        <h1 class="text-center">Bem-vindo(a), <?php echo $_SESSION['username']; ?>!</h1>
    </div>

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
