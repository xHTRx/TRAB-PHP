<?php
/**
 * cadastro_usuario.php
 * Formulário para o cadastro de novos usuários no sistema.
 * Exibe mensagens de feedback ao usuário.
 */

// Inicia a sessão para poder usar $_SESSION e funções de mensagem
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/funcoes.php'; // Inclui as funções utilitárias (para get_mensagem)
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Clínica Camil</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Cadastre-se na Clínica Camil</h2>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Exibe qualquer mensagem de sucesso ou erro (ex: senhas não batem, usuário já existe)
                        echo get_mensagem();
                        ?>
                        <form action="processa_cadastro_usuario.php" method="post">
                            <div class="mb-3">
                                <label for="login" class="form-label">Nome de Usuário (Login):</label>
                                <input type="text" class="form-control" name="login" id="login" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" class="form-control" name="senha" id="senha" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                                <input type="password" class="form-control" name="confirmar_senha" id="confirmar_senha" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Cadastrar</button>
                                <a href="index.php" class="btn btn-secondary">Voltar para Login</a>
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