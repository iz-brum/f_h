<?php
// Inicie a sessão
session_start();

// Inclua o arquivo de configuração para obter a conexão com o banco de dados
include __DIR__ . '/../../config/config.php'; 
// Inclua o arquivo do controlador do usuário
include __DIR__ . '/../controllers/UserController.php';

// Instancia a classe UserController
$userController = new UserController($conn);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $username = $_POST["username"];
    $useremail = $_POST["useremail"];
    $password = $_POST["userpassword"];

    // Chama a função de cadastro e armazena o resultado
    $cadastroResult = $userController->cadastrarUsuario($username, $useremail, $password);

    // Verifica o status do cadastro
    if ($cadastroResult['status'] === 'success') {
        // Redireciona para a página de sucesso
        header("Location: ./user_view.php");
        exit();
    } else {
        // Armazena a mensagem de erro na sessão
        $_SESSION['cadastro_error'] = $cadastroResult['message'];

        // Redireciona de volta para a página de cadastro em caso de erro
        header("Location: /../../index.php");
        exit();
    }
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./../../public/css/style.css">
    <link rel="stylesheet" href="./../../public/css/media-query.css">
    <link rel="stylesheet" href="./../../public/css/cadastro.css">
    <link rel="stylesheet" href="./../../public/css/media-query-cadastro.css">

    <style>
        /* Configuração para desktop */
        @media screen and (min-width: 992px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#icadastro {
                width: 980px;
                height: 400px;
            }

            section#icadastro>div#iimagem {
                float: left;
                width: 30%;
                height: 100%;
            }

            section#icadastro>div#iformulario {
                float: right;
                width: 70%;
                margin-top: 0; /* Removemos a margem superior para melhor ajuste */
            }

            form > div.c-campo {
                margin-bottom: 1px; /* ou qualquer valor desejado */
            }
        }

        /* Configuração para tablet */
        @media screen and (min-width: 768px) and (max-width: 991px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#icadastro {
                width: 600px;
                height: 349px;
            }

            section#icadastro>div#iimagem {
                float: right;
                width: 40%;
                height: 100%;
            }

            section#icadastro>div#iformulario {
                float: left;
                width: 60%;
                margin-top: 0; /* Removemos a margem superior para melhor ajuste */
            }

            /* Adicionamos uma nova regra para ajustar o formulário */
            section#icadastro>div#iformulario form {
                width: 100%; /* Ajustamos a largura do formulário para 100% */
            }

            form > div.c-campo {
                margin-bottom: 5px; /* ou qualquer valor desejado */
            }
        }

        /* Configuração para smartphone */
        @media screen and (max-width: 620px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#icadastro {
                width: 80%;
                max-width: 400px;
                height: 240px;
                margin: 0 auto;
                overflow: hidden;
            }

            section#icadastro>div#iimagem {
                float: left;
                width: 40%;
                height: 100%;
            }

            section#icadastro>div#iformulario {
                float: right;
                width: 60%;
                height: 290px;
                margin-top: 0;
            }

            section#icadastro>div#iformulario form {
                width: 295px;
                max-width: 300px;
                height: 130px;
                margin: 0 auto;
                margin-left: -41px;
                transform: scale(0.7);
            }

            div#iformulario h1 {
                font-size: 1.0em; /* Ajuste o tamanho do título conforme necessário */
            }

            div#iformulario p {
                font-size: 0.7em; /* Ajuste o tamanho do parágrafo conforme necessário */
                padding: 1px 0;
                margin-left: 3px;
            }

            form > div.c-campo {
                display: block;
                background-color: #5f2c82;
                height: 40px;
                width: 100%;
                margin: -11px 0px;
                border-radius: 7px;
                border: 1px solid #5f2c82;
                margin-bottom: 5px; /* ou qualquer valor desejado */
            }

            form > input {
                margin-top: -5px;
            }
        }
    </style>
</head>

<body>
    <main>
        <section id="icadastro">
            <div id="iimagem" style="background-image: url('./../../public/images/pexels-patrice-werner.jpg');">
            </div>
            <div id="iformulario">
                <h1>Cadastro de Usuário</h1>

                <form action="user_register.php" method="post">
                    <div class="c-campo">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="username" id="iusername" placeholder="seu nome de usuário" autocomplete="username" maxlength="30" required>
                        <label for="iusername">Nome de Usuário</label>
                    </div>
                    <div class="c-campo">
                        <span class="material-symbols-outlined">mail</span>
                        <input type="email" name="useremail" id="iuseremail" placeholder="seu e-mail" autocomplete="email" maxlength="30" required>
                        <label for="iuseremail">E-mail</label>
                    </div>
                    <div class="c-campo">
                        <span class="material-symbols-outlined">password</span>
                        <input type="password" name="userpassword" id="ipassword" placeholder="sua senha" autocomplete="new-password" minlength="8" maxlength="20" required>
                        <label for="ipassword">Senha:</label>
                    </div>
                    <input type="submit" value="Cadastrar">
                </form>
                
                <p id="possuiConta">Já possui uma conta? <a href="../../index.php">Faça login aqui</a>.</p>
            </div>
        </section>
    </main>
</body>

</html>
