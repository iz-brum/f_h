<?php
// get_mensagens.php

// Inclua o arquivo do controlador do usuário
include __DIR__ . '/../../config/config.php';
include __DIR__ . '/../controllers/UserController.php';

// Verifique a autenticação do usuário
session_start();

// Certifique-se de que o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirecione para a página de login ou tome outra ação apropriada
    header('Location: /caminho/para/a/pagina/de/login.php');
    exit();
}

// Crie uma instância do controlador da mensagem
$mensagemController = new UserController($conn);

// IDs dos usuários (você precisará recuperar esses IDs)
// Obtenha o ID do usuário logado (remetente)
$idRemetente = $_SESSION['user_id'];
$idDestinatario = isset($_GET['iddestinatario']) ? $_GET['iddestinatario'] : null;


// Obtém as mensagens entre os usuários
$mensagens = $mensagemController->getMensagensEntreUsuarios($idRemetente, $idDestinatario);

// Retorna as mensagens como JSON
header('Content-Type: application/json');
// echo json_encode($mensagens);
// Garante que nada mais seja executado após a saída do JSON
// exit;
?>
