<?php
// app/controllers/UserController.php

include __DIR__ . '/../../config/config.php';

class UserController {
    private $db; // Adicione a propriedade $db

    // Construtor para receber a instância do banco de dados
    public function __construct($db) {
        $this->db = $db;
    }

    public function login($login, $senha) {
        global $conn;  // Estabelece a conexão global com o banco de dados
        
        // Consulta o banco de dados para verificar o login
        $stmt = $conn->prepare("SELECT iduser, userpassword FROM user WHERE useremail = ?");
        $stmt->bind_param("s", $login);  // Associa o parâmetro ao email fornecido
        $stmt->execute();  // Executa a consulta preparada
        $result = $stmt->get_result();  // Obtém o resultado da consulta
        
        // Verifica se o usuário existe
        if ($result->num_rows > 0) {
            // Obtém os dados do usuário a partir do resultado da consulta
            $row = $result->fetch_assoc();
            $hashed_password = $row['userpassword'];
        
            // Verifica se a senha está correta utilizando a função password_verify
            if (password_verify($senha, $hashed_password)) {
                // Inicia a sessão e define o ID do usuário na sessão PHP
                $_SESSION['user_id'] = $row['iduser'];
                return ['status' => 'success'];  // Retorna sucesso caso a senha seja correta
            } else {
                // Retorna um array indicando erro e uma mensagem correspondente
                return ['status' => 'error', 'message' => 'Senha incorreta!'];
            }
        } else {
            // Retorna um array indicando erro e uma mensagem correspondente, pois o usuário não foi encontrado
            return ['status' => 'error', 'message' => 'Usuário não encontrado!'];
        }
    }
    

    // Função para cadastrar um novo usuário no banco de dados
    public function cadastrarUsuario($username, $useremail, $password) {
        global $conn;

        // Hash da senha (use a função de hash adequada ao seu contexto)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Utilização de Prepared Statements para evitar SQL Injection
        $stmt = $conn->prepare("INSERT INTO user (username, useremail, userpassword) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $useremail, $hashed_password);

        // Executa a consulta preparada
        if ($stmt->execute()) {
            // Retorna um array indicando sucesso e uma mensagem correspondente
            return ['status' => 'success', 'message' => 'Usuário cadastrado com sucesso!'];
        } else {
            // Retorna um array indicando erro e uma mensagem correspondente, incluindo detalhes do erro SQL
            return ['status' => 'error', 'message' => 'Erro ao cadastrar usuário: ' . $stmt->error];
        }
    }


    // Função para resetar a senha de um usuário no banco de dados
    public function resetarSenha($useremail, $novaSenha) {
        global $conn;

        // Hash da nova senha
        $hashed_password = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Utilização de Prepared Statements para evitar SQL Injection
        $stmt = $conn->prepare("UPDATE user SET userpassword = ? WHERE useremail = ?");
        $stmt->bind_param("ss", $hashed_password, $useremail);

        // Executa a consulta preparada
        if ($stmt->execute()) {
            // Fecha a conexão com o banco de dados para liberar recursos
            $stmt->close();
            $conn->close();

            // Retorna um array indicando sucesso e uma mensagem correspondente
            return ['status' => 'success', 'message' => 'Senha resetada com sucesso!'];
        } else {
            // Fecha a conexão com o banco de dados em caso de erro
            $stmt->close();
            $conn->close();

            // Retorna um array indicando erro e uma mensagem correspondente, incluindo detalhes do erro SQL
            return ['status' => 'error', 'message' => 'Erro ao resetar a senha: ' . $stmt->error];
        }
    }   

    // Função para obter todos os usuários do banco de dados
    public function getAllUsers() {
        global $conn;
    
        $sql = "SELECT iduser, username, useremail FROM user";
        $result = $conn->query($sql);
    
        $users = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
    
        return $users;
    }

    public function getAllUsersWithProfiles() {
        global $conn;
    
        $sql = "SELECT u.iduser, u.username, u.useremail, p.descricao, p.interesses, p.disponibilidade 
                FROM user u 
                LEFT JOIN perfil p ON u.iduser = p.iduser";
    
        $result = $conn->query($sql);
    
        $users = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
    
        return $users;
    }


    // Função para obter o nome de usuário com base no ID do usuário
    public function getUsernameById($id) {
        
        // Obtém os dados do usuário com base no ID usando a função getUserById
        $user = $this->getUserById($id);

        // Retorna o nome de usuário se encontrado, caso contrário, retorna uma mensagem indicando que o nome não foi encontrado
        return isset($user['username']) ? $user['username'] : 'Nome não encontrado';
    }


    // Função para obter dados do usuário com base no ID do usuário
    public function getUserById($userId) {
        global $conn;

        // Evita SQL injection escapando o ID do usuário
        $userId = $conn->real_escape_string($userId);

        // Consulta SQL para selecionar um usuário com base no ID
        $sql = "SELECT * FROM user WHERE iduser = '$userId'";
        $result = $conn->query($sql);

        // Retorna os dados do usuário como um array associativo
        return $result->fetch_assoc();
    }


    // Função para cadastrar um perfil no banco de dados
    public function cadastrarPerfil($user_id, $descricao, $interesses, $disponibilidade) {
        global $conn;

        // Prepara a declaração SQL para inserir os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO schema_mao_amiga.perfil (iduser, descricao, interesses, disponibilidade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $descricao, $interesses, $disponibilidade);

        // Executa a declaração preparada
        if ($stmt->execute()) {
            // Fecha a declaração para liberar recursos
            $stmt->close();
            // Retorna verdadeiro em caso de sucesso
            return true;
        } else {
            // Fecha a declaração em caso de erro
            $stmt->close();
            // Retorna falso em caso de falha no cadastro
            return false;
        }
    }
    
    // Função para obter mensagens entre dois usuários
    public function getMensagensEntreUsuarios($idRemetente, $idDestinatario)
    {
        global $conn; // Certifique-se de ter a conexão com o banco de dados disponível

        // Verifica se os IDs de usuário são válidos
        if (!$this->isValidUserId($idRemetente) || !$this->isValidUserId($idDestinatario)) {
            // IDs de usuário inválidos
            return [];
        }

        // Consulta o banco de dados para obter as mensagens usando prepared statements
        $sql = "SELECT m.*, u.iduser as idremetente, u.username
                FROM mensagens m
                JOIN user u ON (m.idremetente = u.iduser)
                WHERE (m.idremetente = ? AND m.iddestinatario = ?)
                OR (m.idremetente = ? AND m.iddestinatario = ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $idRemetente, $idDestinatario, $idDestinatario, $idRemetente);
        $stmt->execute();
        $result = $stmt->get_result();

        $mensagens = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mensagens[] = [
                    'idremetente' => $row['idremetente'],
                    'username' => $row['username'],
                    'mensagem' => $row['mensagem'],
                    'data_envio' => $row['data_envio'],
                    // Adicione mais campos conforme necessário
                ];
            }
        }

        return $mensagens;
    }


    function isValidUserId($userId)
    {
        // Verifica se o ID do usuário é um número inteiro positivo
        if (!is_numeric($userId) || $userId <= 0 || intval($userId) != $userId) {
            return false;
        }

        // Consulta o banco de dados para verificar se o usuário existe
        global $conn; // Certifique-se de ter a conexão com o banco de dados disponível

        $userId = $conn->real_escape_string($userId);
        $sql = "SELECT COUNT(*) as count FROM user WHERE iduser = '$userId'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'] > 0; // Retorna true se o usuário existe na tabela
        }

        return false; // Retorna false por padrão ou em caso de erro na consulta
    }

    // Função para enviar mensagem
    public function enviarMensagem($idRemetente, $idDestinatario, $mensagem) {
        // Certifique-se de validar os IDs e a mensagem antes de prosseguir
        if (!$this->isValidUserId($idRemetente) || !$this->isValidUserId($idDestinatario) || empty($mensagem)) {
            // Trate de forma adequada, como lançar uma exceção ou retornar um código de erro
            return false;
        }

        // Aqui você deve realizar a inserção da mensagem na sua tabela de mensagens
        // Substitua os detalhes da inserção com base na estrutura real da sua tabela de mensagens
        $sql = "INSERT INTO mensagens (idremetente, iddestinatario, mensagem) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis', $idRemetente, $idDestinatario, $mensagem);
        $result = $stmt->execute();
        
        // Verifique se a inserção foi bem-sucedida
        return $result;
    }

}
?>
