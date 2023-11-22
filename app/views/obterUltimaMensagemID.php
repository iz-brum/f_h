<?php
// Inclua o arquivo de configuração do banco de dados e outras dependências
include __DIR__ . '/../../config/config.php';

// Função para obter o último ID de mensagem exibido
function obterUltimaMensagemID($idRemetente, $idDestinatario, $conn) {
    // Substitua 'mensagens' pelo nome real da sua tabela de mensagens
    $sql = "SELECT MAX(id) AS ultimaMensagemID FROM mensagens 
            WHERE (idremetente = ? AND iddestinatario = ?) 
            OR (idremetente = ? AND iddestinatario = ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $idRemetente, $idDestinatario, $idDestinatario, $idRemetente);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['ultimaMensagemID'];
    }

    return 0; // Retorna 0 se não houver mensagens ou em caso de erro
}

// Obtém os parâmetros do GET
$idRemetente = $_GET['idremetente'];
$idDestinatario = $_GET['iddestinatario'];

// Chama a função e retorna o resultado como JSON
header('Content-Type: application/json');
echo json_encode(['ultimaMensagemID' => obterUltimaMensagemID($idRemetente, $idDestinatario, $conn)]);
?>
