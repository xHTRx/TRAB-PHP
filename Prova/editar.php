<?php 
/**
 * editar.php
 * Formulário para editar um agendamento existente.
 * Garante que apenas o proprietário do agendamento possa editá-lo.
 * Esta página é protegida e exige autenticação.
 */

// Inicia a sessão para poder usar $_SESSION e funções de sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/conexao.php'; // Inclui o arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui as funções utilitárias

proteger_pagina(); // Protege esta página. Se não estiver logado, redireciona.

// Obtém o ID do usuário logado da sessão
$usuario_id_logado = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Agendamento</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 id="h1_titulo">Editar Agendamento</h1>
            <p>
                <a href="home.php" class="btn btn-secondary">Voltar para Agendamento</a>
                <a href="clientes.php" class="btn btn-info">Ver Meus Agendamentos</a>
            </p>
        </div>

        <?php 
        echo get_mensagem(); // Exibe mensagens

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $nome = $_POST['nome'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            $procedimento = $_POST['procedimento'] ?? '';
            $data = $_POST['data'] ?? '';
            $hora = $_POST['hora'] ?? '';

            $campos_obrigatorios_agendamento = ['nome', 'telefone', 'procedimento', 'data', 'hora'];
            if (ha_campos_em_branco($_POST, $campos_obrigatorios_agendamento)) {
                set_mensagem('erro', 'Por favor, preencha todos os campos obrigatórios do agendamento.');
                // Redireciona de volta para a própria página de edição com o ID
                header('Location: editar.php?id=' . $id);
                exit();
            }

            $conn = conectar_banco();

            // VERIFICAÇÃO DE SEGURANÇA: Garante que o agendamento pertence ao usuário logado
            $check_owner_sql = "SELECT usuario_id FROM tb_horarios WHERE id = ?";
            $stmt_owner = mysqli_prepare($conn, $check_owner_sql);
            mysqli_stmt_bind_param($stmt_owner, "i", $id);
            mysqli_stmt_execute($stmt_owner);
            mysqli_stmt_bind_result($stmt_owner, $owner_id);
            mysqli_stmt_fetch($stmt_owner);
            mysqli_stmt_close($stmt_owner);

            if ($owner_id !== $usuario_id_logado) {
                set_mensagem('erro', 'Você não tem permissão para editar este agendamento.');
                header('Location: clientes.php'); // Redireciona para a lista
                exit();
            }

            $sql = "UPDATE tb_horarios SET nome = ?, cpf = ?, telefone = ?, procedimento = ?, dta = ?, hora = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                set_mensagem('erro', 'Erro na preparação da consulta de edição: ' . mysqli_error($conn));
                error_log("Erro na preparação do update de agendamento: " . mysqli_error($conn));
            } else {
                mysqli_stmt_bind_param($stmt, "ssssssi", $nome, $cpf, $telefone, $procedimento, $data, $hora, $id);

                if (mysqli_stmt_execute($stmt)) {
                    set_mensagem('sucesso', 'Agendamento editado com sucesso!');
                    header('Location: clientes.php'); // Redireciona para a lista
                    exit();
                } else {
                    set_mensagem('erro', 'Erro ao editar agendamento: ' . mysqli_stmt_error($stmt));
                    error_log("Erro ao executar update de agendamento: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_close($conn);
            // Redireciona para evitar reenvio do formulário ao recarregar a página
            header('Location: clientes.php');
            exit();

        } else { // Requisição GET para exibir o formulário de edição
            if (!isset($_GET['id'])) {
                set_mensagem('erro', 'ID do agendamento não informado.');
                header('Location: clientes.php');
                exit();
            }

            $id = (int) $_GET['id'];
            $conn = conectar_banco();

            // VERIFICAÇÃO DE SEGURANÇA: Garante que o agendamento pertence ao usuário logado
            $query_agendamento = "SELECT id, nome, cpf, telefone, procedimento, dta, hora FROM tb_horarios WHERE id = ? AND usuario_id = ?";
            $stmt_agendamento = mysqli_prepare($conn, $query_agendamento);
            
            if (!$stmt_agendamento) {
                set_mensagem('erro', 'Erro ao preparar a consulta para carregar agendamento: ' . mysqli_error($conn));
                error_log("Erro ao preparar select para edição de agendamento: " . mysqli_error($conn));
                mysqli_close($conn);
                exit();
            }

            mysqli_stmt_bind_param($stmt_agendamento, "ii", $id, $usuario_id_logado);
            mysqli_stmt_execute($stmt_agendamento);
            $resultado_agendamento = mysqli_stmt_get_result($stmt_agendamento);

            if (!mysqli_num_rows($resultado_agendamento) > 0) {
                set_mensagem('erro', 'Agendamento não localizado ou você não tem permissão para editá-lo.');
                mysqli_stmt_close($stmt_agendamento);
                mysqli_close($conn);
                header('Location: clientes.php');
                exit();
            }

            $agendamento = mysqli_fetch_assoc($resultado_agendamento);
            mysqli_stmt_close($stmt_agendamento);
            mysqli_close($conn);
            ?>

            <form action="editar.php" method="post" class="p-4 border rounded shadow-sm">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome: </label>
                    <input type="text" class="form-control" name="nome" id="nome" value="<?php echo htmlspecialchars($agendamento['nome']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF: </label>
                    <input type="text" class="form-control" name="cpf" id="cpf" value="<?= htmlspecialchars($agendamento['cpf']); ?>">
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone: </label>
                    <input type="tel" class="form-control" name="telefone" id="telefone" value="<?= htmlspecialchars($agendamento['telefone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="procedimento" class="form-label">Procedimento Estético: </label>
                    <input type="text" class="form-control" name="procedimento" id="procedimento" value="<?= htmlspecialchars($agendamento['procedimento']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="data" class="form-label">Data: </label>
                    <input type="date" class="form-control" name="data" id="data" value="<?= htmlspecialchars($agendamento['dta']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="hora" class="form-label">Hora: </label>
                    <input type="time" class="form-control" name="hora" id="hora" value="<?= htmlspecialchars($agendamento['hora']); ?>" required>
                </div>

                <input type="hidden" name="id" value="<?= htmlspecialchars($agendamento['id']); ?>">

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Salvar Edição</button>
                </div>
            </form>
            <?php
        } // Fim do bloco else da requisição GET
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>