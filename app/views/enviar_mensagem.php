<?php
// Inclua o arquivo do controlador do usuário
include __DIR__ . '/../controllers/UserController.php';
include __DIR__ . '/../../config/config.php';


// Verifique a autenticação do usuário
session_start();

// Crie uma instância do controlador da mensagem e do usuário
$mensagemController = new UserController($conn);
$userController = new UserController($conn);

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    // IDs dos usuários (você precisará recuperar esses IDs)
    // Obtenha o ID do usuário logado (remetente)
    $idRemetente = $_SESSION['user_id'];
    $idDestinatario = $_POST['iddestinatario'];
    $mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : null;

    // Se 'iddestinatario' ou 'mensagem' não estiverem definidos, redirecione ou trate de outra forma
    if (!$idDestinatario || !$mensagem || !$userController->isValidUserId($idDestinatario)) {
        // Redirecione para uma página de erro ou faça outra ação adequada
        header("Location: enviar_mensagem.php");
        echo 'erro 1';
        exit();
    }

    // Insira a mensagem no banco de dados
    $mensagemController->enviarMensagem($idRemetente, $idDestinatario, $mensagem);

    // Redirecione de volta para a página de conversa
    header("Location: conversa.php?iddestinatario=$idDestinatario");
    exit();
} else {
    // Se o formulário não foi enviado por POST, redirecione ou trate de outra forma
    header("Location: enviar_mensagem.php");
    echo 'erro 2';
    exit();
}
