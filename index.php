<!-- index.php -->

<?php 
// Inicie a sessão
session_start();

// Inclua o arquivo do controlador do usuário
include __DIR__ . '/app/controllers/UserController.php';
// Inclua o arquivo de configuração para obter a conexão com o banco de dados
include __DIR__ . '/config/config.php';

// Instancia a classe UserController
$userController = new UserController($conn);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $login = $_POST["login"];
    $senha = $_POST["senha"];

    // Chama a função de login e armazena o resultado
    $loginResult = $userController->login($login, $senha);

    // Verifica o status do login
    if ($loginResult['status'] === 'success') {
        // Obtém informações do usuário após o login
        $userData = $userController->getUserById($_SESSION['user_id']);

        // Armazena informações do usuário na sessão
        $_SESSION['username'] = $userData['username']; // Certifique-se de ajustar conforme a estrutura real do seu banco de dados

        // Redireciona para a página de sucesso
        header("Location: ./app/views/user_view.php");
        exit();

    } else {
        // Armazena a mensagem de erro na sessão
        $_SESSION['login_error'] = $loginResult['message'];
        
        // Redireciona de volta para a página de login em caso de erro
        header("Location: ./index.php");
        exit();
    }
    
}

// Recupere a mensagem de erro, se houver
$errorMessage = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';

// Limpe a variável de sessão
unset($_SESSION['login_error']);

// Instancia a classe UserController
$userController = new UserController($conn);

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $useremail = $_POST["useremail"];
    $novaSenha = $_POST["novaSenha"];

    // Chama a função de resetar senha e armazena o resultado
    $resetSenhaResult = $userController->resetarSenha($useremail, $novaSenha);

    // Verifica o status do reset de senha
    if ($resetSenhaResult['status'] === 'success') {
        // Redireciona para a página de sucesso ou exibe uma mensagem
        header("Location: /app/views/user_view.php");
        exit();
    } else {
        // Armazena a mensagem de erro na sessão
        $_SESSION['reset_senha_error'] = $resetSenhaResult['message'];

        // Redireciona de volta para a página de reset de senha em caso de erro
        header("Location: /index.php");
        exit();
    }
}

// Recupere a mensagem de erro do login, se houver
$loginErrorMessage = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';

// Limpe a variável de sessão
unset($_SESSION['login_error']);

// Recupere as mensagens de reset de senha, se houver
$resetSenhaSuccessMessage = isset($_SESSION['reset_senha_success']) ? $_SESSION['reset_senha_success'] : '';
$resetSenhaErrorMessage = isset($_SESSION['reset_senha_error']) ? $_SESSION['reset_senha_error'] : '';

// Limpe as variáveis de sessão
unset($_SESSION['reset_senha_success']);
unset($_SESSION['reset_senha_error']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/media-query.css">

    <style>
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

        /* Configuração para desktop */
        @media screen and (min-width: 992px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#ilogin {
                width: 1030px;
                height: 350px;
            }

            section#ilogin>div#iimagem {
                float: left;
                width: 30%;
                height: 100%;
            }

            section#ilogin>div#iformulario {
                float: right;
                width: 70%;
                margin-top: 0; /* Removemos a margem superior para melhor ajuste */
            }

            form > div.c-campo{
                margin-bottom: 1px; /* ou qualquer valor desejado */
            }
            
            form > input{
                margin-top: 10px;
            }
        }
        
         /* Configuração para tablet */
         @media screen and (min-width: 768px) and (max-width: 991px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#ilogin {
                width: 600px;
                max-width: 400px;
                height: 240px;
                margin: 0 auto;
                overflow: hidden;
            }

            section#ilogin > div#iimagem {
                float: right;
                width: 40%;
                height: 100%;
            }

            section#ilogin>div#iformulario {
                float: left;
                width: 60%;
                height: 290px;
                margin-top: 0;
            }

            section#ilogin>div#iformulario form {
                width: 295px;
                max-width: 300px;
                height: 130px;
                margin: 0 auto;
                margin-left: -41px;
                transform: scale(0.7);
            }

            div#iformulario > h1 {
                font-size: 1.0em; /* Ajuste o tamanho do título conforme necessário */
            }

            div#iformulario p {
                font-size: 0.7em; /* Ajuste o tamanho do parágrafo conforme necessário */
                padding: 1px 0;
                margin-left: 3px;
            }

            form > div.c-campo{
                display: block;
                background-color: #5f2c82;
                height: 40px;
                width: 100%;
                margin: -11px 0px;
                border-radius: 7px;
                border: 1px solid #5f2c82;
                margin-bottom: 16px; /* ou qualquer valor desejado */
            }

            form > input{
                margin-top: -10px;
            }
        }

        /* Configuração para smartphone */
        @media screen and (max-width: 714px) {
            body {
                background-image: linear-gradient(to top, #49a09d, #5f2c82);
            }

            section#ilogin {
                width: 400px;
                max-width: 400px;
                height: 240px;
                margin: 0 auto;
                overflow: hidden;
            }

            section#ilogin > div#iimagem {
                float: right;
                width: 40%;
                height: 100%;
            }

            section#ilogin>div#iformulario {
                float: left;
                width: 60%;
                height: 290px;
                margin-top: 0;
            }

            section#ilogin>div#iformulario form {
                width: 295px;
                max-width: 300px;
                height: 130px;
                margin: 0 auto;
                margin-left: -41px;
                transform: scale(0.7);
            }

            div#iformulario > h1 {
                font-size: 1.0em; /* Ajuste o tamanho do título conforme necessário */
            }

            div#iformulario p {
                font-size: 0.7em; /* Ajuste o tamanho do parágrafo conforme necessário */
                padding: 1px 0;
                margin-left: 3px;
            }

            form > div.c-campo{
                display: block;
                background-color: #5f2c82;
                height: 40px;
                width: 100%;
                margin: -11px 0px;
                border-radius: 7px;
                border: 1px solid #5f2c82;
                margin-bottom: 16px; /* ou qualquer valor desejado */
            }
        }
    </style>
    
    
</head>
<body>
    <main>
        
        <section id="ilogin">
            <div style="display: block; background: #5f2c82 url('public/images/pexels-patrice-werner.jpg') center center no-repeat; background-size: cover; height: 350px;" id="iimagem">
            </div>
            <div id="iformulario">
                <h1>Login</h1>
                <p>Seja bem-vindo(a). Faça login para acessar a sua conta.</p>
                
                <!-- Adicione a o trecho de código para exibir as mensagens de login -->
                <?php if ($errorMessage): ?>
                    <div class="error-message">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <!-- Adicione o trecho de código para exibir as mensagens de reset de senha -->
                <?php if ($resetSenhaSuccessMessage): ?>
                    <div class="success-message">
                        <?php echo $resetSenhaSuccessMessage; ?>
                    </div>
                <?php endif; ?>

                <?php if ($resetSenhaErrorMessage): ?>
                    <div class="error-message">
                        <?php echo $resetSenhaErrorMessage; ?>
                    </div>
                <?php endif; ?>

                <form action="index.php" method="post" id="loginForm">
                    <div class="c-campo">
                        <span class="material-symbols-outlined">person</span>
                        <input type="email" name="login" id="ilogin" placeholder="seu e-mail" autocomplete="email" maxlength="30" required>
                        <label for="ilogin"></label>
                    </div>
                    <div class="c-campo">
                        <span class="material-symbols-outlined">password</span>
                        <input type="password" name="senha" id="isenha" placeholder="sua senha" autocomplete="current-password" minlength="4" maxlength="20" required>                        
                        <label for="isenha"></label>
                    </div>
                    <input type="submit" value="Entrar">
                    <a href="./app/views/reset_senha.php" class="c-botao">
                        Esqueci a senha <span class="material-symbols-outlined">mail</span>
                    </a>
                    <!-- Botão de login -->
                    <a href="./app/views/user_register.php" class="c-botao">
                        Cadastre-se <span class="material-symbols-outlined">person_add</span>
                    </a>
                </form>
            </div>
        </section>
    </main>

</body>
</html>
