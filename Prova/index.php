<?php 
/**
 * index.php
 * Página inicial do sistema, contendo o formulário de login.
 * Exibe mensagens de feedback ao usuário (sucesso/erro).
 */

// Inicia a sessão para que as funções de mensagem (get_mensagem) funcionem
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de verificações, que agora é responsável apenas por exibir mensagens.
// Certifique-se de que 'verificacoes.php' esteja na mesma pasta que 'index.php'
require_once 'verificacoes.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica Camil</title>
    <!-- CORREÇÃO AQUI: Removidos os colchetes [] do link do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Bem-vindo à Clínica de Estética</h2>
                        <h3>Faça Login para Continuar</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Chama a função para tratar e exibir mensagens de erro/sucesso da sessão
                        tratar_erros(); 
                        ?>
                        
                        <form action="processa_login.php" method="post">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuário:</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" class="form-control" name="senha" id="senha" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a href="cadastro_usuario.php" class="btn btn-secondary">Cadastre-se</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>