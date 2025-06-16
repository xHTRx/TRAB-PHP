<?php 
/**
 * home.php
 * Página principal do sistema após o login.
 * Contém o formulário para agendamento de novos clientes.
 * Esta página é protegida e só pode ser acessada por usuários autenticados.
 */

// Inicia a sessão para poder usar $_SESSION e funções de sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/funcoes.php'; // Inclui as funções utilitárias (para proteger_pagina e get_mensagem)

proteger_pagina(); // Chama a função para proteger esta página. Se não estiver logado, redireciona.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica de Estética - Agendamento</title>
    <!-- Inclui seu CSS personalizado -->
    <link rel="stylesheet" href="css/style.css"> 
    <!-- CORREÇÃO AQUI: Removidos os colchetes [] do link do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Agenda de Datas e Horas - Clínica Camil</h1>
            <p>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_login'] ?? 'Usuário'); ?>!</p>
            <a href="clientes.php" class="btn btn-info mt-3">Ver Meus Agendamentos</a>
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        </div>

        <?php 
        // Exibe qualquer mensagem de sucesso ou erro que possa ter sido definida
        echo get_mensagem(); 
        ?>

        <form action="salvarDB.php" method="post" class="p-4 border rounded shadow-sm">
            <h2 class="mb-4">Novo Agendamento</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Cliente:</label>
                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo do Cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF:</label>
                        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00">
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone:</label>
                        <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="(00)00000-0000" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="procedimento" class="form-label">Procedimento Estético:</label>
                        <input type="text" class="form-control" name="procedimento" id="procedimento" required>
                    </div>
                    <div class="mb-3">
                        <label for="data" class="form-label">Data:</label>
                        <input type="date" class="form-control" name="data" id="data" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora:</label>
                        <input type="time" class="form-control" name="hora" id="hora" required>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary">Agendar</button>
                <button type="reset" class="btn btn-secondary">Apagar</button>
            </div>
        </form>
    </div>

    <!-- CORREÇÃO AQUI: Removidos os colchetes [] do link do Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>