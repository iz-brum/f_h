<?php
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

// Crie uma instância do controlador do usuário
$userController = new UserController($conn);


// IDs dos usuários (você precisará recuperar esses IDs)
// Obtenha o ID do usuário logado (remetente)
$idRemetente = $_SESSION['user_id'];
$idDestinatario = $_GET['iddestinatario']; // Substitua pelo ID do destinatário da consulta ou de outra forma


// Lógica para obter mensagens entre os usuários
$mensagens = $userController->getMensagensEntreUsuarios($idRemetente, $idDestinatario);

$nomeDestinatario = $userController->getUsernameById($idDestinatario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>

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
            color: black;
        }

        h1{
            margin-top: 20px;
        }

        .container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 600px;
            margin: auto;
            margin-top: 105px;
            height: 660px;
        }

        #chat-messages {
            max-height: 550px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 10px;
            margin-right: 10px;
            padding: 10px;
            border-radius: 8px;
            background-color: #f0f0f0;
            position: relative;
        }

        .message strong {
            color: #007bff;
        }

        .message span {
            font-size: 12px;
            color: #666;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        #formMensagem {
            display: flex;
            flex-direction: column;
        }

        #formMensagem textarea {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        #formMensagem button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        #chat-messages {
            overflow-y: scroll;
            max-height: 380px; /* ajuste conforme necessário */
        }
    </style>
</head>
<!-- Cabeçalho e estilos... -->
<body>
      <!-- Navbar usando Bootstrap -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="./user_view.php">Friendly Hand</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./user_view.php">Página Inicial</a>
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

    <div class="container">
        <h1 class="mb-1">Chat com <?php echo $nomeDestinatario; ?></h1>
        <div id="chat-messages">
            <?php foreach ($mensagens as $mensagem) : ?>
                <div class="message">
                    <strong><?php echo $mensagem['username']; ?>:</strong>
                    <?php echo $mensagem['mensagem']; ?>
                    <span class="float-right"><?php echo $mensagem['data_envio']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulário para enviar nova mensagem -->
        <form id="formMensagem" method="post" action="enviar_mensagem.php">
            <!-- Seus campos existentes -->
            <input type="hidden" name="iddestinatario" value="<?php echo $idDestinatario; ?>">
            <textarea id="mensagem" name="mensagem" rows="3" style="height: 100px; resize: none;" placeholder="Digite sua mensagem"></textarea>
            <button type="submit">Enviar</button>
        </form>
        
    </div>
    <!-- Adicione os scripts do Bootstrap e outros (opcional) -->
    
    <script>

        // Função para obter o último ID de mensagem exibido
        function obterUltimaMensagemID(idRemetente, idDestinatario) {
            return fetch(`obterUltimaMensagemID.php?idremetente=${idRemetente}&iddestinatario=${idDestinatario}`)
                .then(response => response.json())
                .then(data => data.ultimaMensagemID)
                .catch(error => {
                    console.error('Erro ID de mensagem:', error);
                    return 0;
                });
        }

        // Função para buscar e exibir novas mensagens
        async function buscarNovasMensagens(idRemetente, idDestinatario, ultimoIDExibido) {
            return fetch(`get_mensagens.php?idremetente=5&iddestinatario=3&ultimoid=10`)
                .then(response => response.json())
                .catch(error => {
                    console.error('Erro ao buscar novas mensagens:', error);
                    return [];
                });
        }

        function atualizarChat() {
            // IDs dos usuários
            const idRemetente = '<?php echo $idRemetente; ?>';
            const idDestinatario = '<?php echo $idDestinatario; ?>';

            // Busca novas mensagens
            fetch(`get_mensagens.php?idremetente=${idRemetente}&iddestinatario=${idDestinatario}`)
                .then(response => response.text())  // Alteração aqui para obter o corpo da resposta como texto
                .then(data => {
                    console.log('Resposta do servidor:', data);  // Adiciona um log para verificar a resposta do servidor

                    // Tentativa de analisar o JSON
                    try {
                        const mensagens = JSON.parse(data);
                        mensagens.reverse();

                        document.getElementById('chat-messages').innerHTML = '';

                        mensagens.forEach(mensagem => {
                            const div = document.createElement('div');
                            div.className = 'message';
                            div.innerHTML = `<strong>${mensagem.username}:</strong> ${mensagem.mensagem} <span class="float-right">${mensagem.data_envio}</span>`;
                            document.getElementById('chat-messages').appendChild(div);
                        });

                        // Role automaticamente para o final do chat
                        rolarParaFinal();
                    } catch (error) {
                        console.error('Erro ao analisar JSON:', error);
                    }
                })
                .catch(error => console.error('Erro ao buscar novas mensagens:', error));
        }

        // Atualiza o chat a cada 5 segundos (ajuste conforme necessário)
        setInterval(atualizarChat, 2000);

         // Role automaticamente para o final do chat
        function rolarParaFinal() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Chame a função de rolagem para o final ao carregar a página
        window.onload = rolarParaFinal;

        // Captura o evento de pressionar a tecla Enter no campo de texto
        document.getElementById('mensagem').addEventListener('keydown', function (e) {
            // Verifica se a tecla pressionada é Enter (código 13)
            if (e.keyCode === 13) {
                // Impede a quebra de linha padrão (não adiciona uma nova linha)
                e.preventDefault();
                // Submete o formulário
                document.getElementById('formMensagem').submit();
            }
        });

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
