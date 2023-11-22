<!-- app/views/reset_senha.php -->
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
    $useremail = $_POST["useremail"];
    $novaSenha = $_POST["novaSenha"];

    // Chama a função para resetar a senha e armazena o resultado
    $resetSenhaResult = $userController->resetarSenha($useremail, $novaSenha);

    // Verifica o status do reset de senha
    if ($resetSenhaResult['status'] === 'success') {
        $_SESSION['reset_senha_success'] = $resetSenhaResult['message'];
        // Redireciona para a página de sucesso (você pode criar uma página específica para isso)
        header("Location: ./../../index.php");
        exit();
    } else {
        // Armazena a mensagem de erro na sessão
        $_SESSION['reset_senha_error'] = $resetSenhaResult['message'];
        
        // Redireciona de volta para a página de resetar senha em caso de erro
        header("Location: ./reset_senha.php");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetar Senha</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./../../public/css/style.css">
    <link rel="stylesheet" href="./../../public/css/media-query.css">
    <style>
        /* Adapte o estilo conforme necessário */
        body {
            background-image: linear-gradient(to top, #49a09d, #5f2c82);
        }

        section#ireset {
            width: 80%;
            max-width: 400px;
            height: 240px;
            margin: 0 auto;
            overflow: hidden;
            background-color: white;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        section#ireset > div#iimagem {
            float: right;
            width: 40%;
            height: 100%;
        }

        section#ireset > div#iformulario {
            float: left;
            width: 60%;
            height: 290px;
            margin-top: 0;
            padding: 20px;
        }

        section#ireset > div#iformulario h1 {
            color: #5f2c82;
        }

        form {
            width: 100%;
        }

        form > div.c-campo {
            display: block;
            background-color: #5f2c82;
            height: 40px;
            width: 100%;
            margin: 10px 0px;
            border-radius: 7px;
            border: 1px solid #5f2c82;
        }

        div.c-campo > input{
            background-color: #68d9d5;
            font-size: 1em;
            width: calc(100% - 45px);
            height: 90%;
            border: 0px;
            border-radius: 5px;
            padding: 4px;
            transform: translateY(-11.5px); /* Por questões de compatibilidade com os navegadores */
        }

        div.c-campo > input:focus-within{
            background-color: white;
        }

        form > div.c-campo{
            display: block;
            background-color: #5f2c82;
            height: 40px;
            width: 100%;
            margin: 5px 0px;
            border-radius: 7px;
            border: 1px solid #5f2c82;
        }

        form > div.c-campo > label{
            display: none;
        }

        form > div.c-campo > span{
            /* background-color: black; */
            color: white;
            font-size: 2em;
            width: 40px;
            padding: 5px;
        }

        form > input[type=submit]{
            display: block; 
            font-size: 1em;
            width: 100%;
            height: 30px;
            background-color: #49a09d;
            color: whitesmoke;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form > input[type=submit]:hover{
            background-color: #5f2c82;
        }

        form > a.c-botao{
            display: block;
            text-align: center;
            font-size: 1em;
            color: black;
            width: 100%;
            height: 30px;
            background-color: white;
            border: 1px solid #49a09d;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            padding-top: 3px;
            
        }

        form > a.c-botao:hover{
            background-color: #5f2c82;
            color: white;
        }

        form > a.c-botao > span{
            font-size: .8em;
            vertical-align: middle;
        }

    </style>
</head>
<body>
<main>
        <section id="ilogin" style="height: 350px; ">
            <div style="display: block; background: #5f2c82 url('./../../public/images/pexels-patrice-werner.jpg') center center no-repeat; background-size: cover; height: 350px;" id="iimagem">
            </div>
            <div id="iformulario">
                <h1>Resetar Senha</h1>
                <p>Esqueceu sua senha? Resete ela agora.</p>
                
                <!-- Adicione o formulário para resetar a senha -->
                <form action="reset_senha.php" method="post" id="resetSenhaForm">
                    <div class="c-campo">
                        <span class="material-symbols-outlined">mail</span>
                        <input type="email" name="useremail" id="iemail" placeholder="seu e-mail" autocomplete="email" maxlength="30" required>
                    </div>
                    <div class="c-campo">
                        <span class="material-symbols-outlined">password</span>
                        <input type="password" name="novaSenha" id="inovaSenha" placeholder="nova senha" autocomplete="new-password" minlength="4" maxlength="20" required>
                    </div>
                    <input type="submit" value="Resetar Senha">
                    <!-- Adicione links ou outros elementos conforme necessário -->
                </form>
                <p id="possuiConta">Já possui uma conta? <a href="../../index.php">Faça login aqui</a>.</p>
            </div>
        </section>
    </main>
</body>
</html>

