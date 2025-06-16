<?php 
/**
 * clientes.php
 * Página que lista os agendamentos (itens) cadastrados pelo usuário autenticado.
 * Esta página é protegida e só pode ser acessada por usuários autenticados.
 */

// Inicia a sessão para poder usar $_SESSION e funções de sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/conexao.php'; // Inclui o arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui as funções utilitárias

proteger_pagina(); // Protege esta página. Se não estiver logado, redireciona.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos</title>
    <!-- CORREÇÃO AQUI: Removidos os colchetes [] do link do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body> 
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Meus Agendamentos</h1>
            <p>
                <a href="home.php" class="btn btn-secondary">Voltar para Agendamento</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </p>
        </div>

        <?php 
        // Exibe qualquer mensagem de sucesso ou erro (ex: agendamento excluído)
        echo get_mensagem(); 

        $conn = conectar_banco(); // Conecta ao banco de dados
        $usuario_id = $_SESSION['usuario_id']; // Obtém o ID do usuário logado

        // Prepara a consulta para buscar SOMENTE os agendamentos do usuário logado
        $query = "SELECT id, nome, cpf, telefone, procedimento, dta, hora FROM tb_horarios WHERE usuario_id = ?";
        $stmt = mysqli_prepare($conn, $query);

        // Verifica se a preparação da consulta falhou
        if (!$stmt) {
            set_mensagem('erro', 'Erro ao preparar a consulta de agendamentos: ' . mysqli_error($conn));
            error_log("Erro ao preparar select de agendamentos: " . mysqli_error($conn));
            // Não 'exit' aqui, apenas não exibe a tabela
        } else {
            mysqli_stmt_bind_param($stmt, "i", $usuario_id); // Vincula o ID do usuário
            mysqli_stmt_execute($stmt); // Executa a consulta
            $resultado = mysqli_stmt_get_result($stmt); // Obtém o resultado da consulta

            // Verifica se não há resultados
            if (!mysqli_num_rows($resultado) > 0) {
                echo "<h3>Não há agendamentos cadastrados por você.</h3>";
            } else {
                // Se houver resultados, constrói a tabela
                echo '<table class="table table-striped table-hover">';
                echo "<thead><tr>";
                echo "<th>ID #</th>";
                echo "<th>Nome</th>";
                echo "<th>CPF</th>";
                echo "<th>Fone</th>";
                echo "<th>Procedimento Estético</th>";
                echo "<th>Data</th>";
                echo "<th>Hora</th>";
                echo "<th>Ações</th>";
                echo "</tr></thead><tbody>";
                
                // Itera sobre cada linha de resultado e exibe na tabela
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($linha['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['cpf']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['telefone']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['procedimento']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['dta']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['hora']) . "</td>";
                    echo '<td>
                        <a href="editar.php?id=' . htmlspecialchars($linha['id']) . '" class="btn btn-sm btn-warning">Editar</a>
                        <a href="excluir.php?id=' . htmlspecialchars($linha['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este agendamento?\');">Excluir</a>
                    </td>';
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
            mysqli_stmt_close($stmt); // Fecha o statement
        }
        mysqli_close($conn); // Fecha a conexão com o banco de dados
        ?>
    </div>

    <!-- CORREÇÃO AQUI: Removidos os colchetes [] do link do Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>