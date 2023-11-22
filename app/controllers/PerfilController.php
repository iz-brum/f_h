<?php
// app/controllers/PerfilController.php

include __DIR__ . '/../../config/config.php';

class PerfilController {
    public function getAllPerfis() {
        global $conn;

        $sql = "SELECT * FROM perfil";
        $result = $conn->query($sql);

        $perfis = [];
        while ($row = $result->fetch_assoc()) {
            $perfis[] = $row;
        }

        return $perfis;
    }

    public function getPerfilById($perfilId) {
        global $conn;

        $perfilId = $conn->real_escape_string($perfilId);
        $sql = "SELECT * FROM perfil WHERE idperfil = '$perfilId'";
        $result = $conn->query($sql);

        return $result->fetch_assoc();
    }

    public function getPerfisByUserId($userId) {
        global $conn;
    
        // Certifique-se de realizar as verificações necessárias para evitar SQL injection
        $userId = $conn->real_escape_string($userId);
    
        // Consulta para obter os perfis do usuário pelo ID
        $sql = "SELECT * FROM perfil WHERE iduser = '$userId'";
        $result = $conn->query($sql);
    
        // Verifica se a consulta foi bem-sucedida
        if ($result) {
            // Inicializa um array para armazenar os perfis
            $perfis = array();
    
            // Loop através dos resultados e armazena cada perfil no array
            while ($row = $result->fetch_assoc()) {
                $perfis[] = $row;
            }
    
            // Retorna o array de perfis
            return $perfis;
        } else {
            // Se houver um erro na consulta, você pode lidar com isso adequadamente
            // Neste exemplo, apenas retornaremos um array vazio
            return array();
        }
    }

    public function perfilExiste($user_id)
    {
        global $conn;

        $user_id = $conn->real_escape_string($user_id);

        $sql = "SELECT idperfil FROM perfil WHERE iduser = '$user_id'";
        $result = $conn->query($sql);

        return $result->num_rows > 0;
    }

    public function adicionarPerfil($user_id, $descricao, $interesses, $disponibilidade)
    {
        global $conn;

       

        // Limpa e valida os dados
        $user_id = $conn->real_escape_string($user_id);
        $descricao = $conn->real_escape_string($descricao);
        $interesses = $conn->real_escape_string($interesses);
        $disponibilidade = $conn->real_escape_string($disponibilidade);

        // Insere o novo perfil no banco de dados
        $sql = "INSERT INTO perfil (iduser, descricao, interesses, disponibilidade) 
                VALUES ('$user_id', '$descricao', '$interesses', '$disponibilidade')";

        if ($conn->query($sql)) {
            return ['status' => 'success', 'message' => 'Perfil adicionado com sucesso.'];
        } else {
            return ['status' => 'error', 'message' => 'Erro ao adicionar o perfil: ' . $conn->error];
        }
    }
}
?>
