<?php 
/**
 * salvarDB.php
 * Processa a submissão do formulário de agendamento.
 * Insere um novo agendamento no banco de dados, associando-o ao usuário logado.
 * Esta página é protegida e exige autenticação.
 */

// Inicia a sessão para poder usar $_SESSION e funções de sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/conexao.php'; // Inclui o arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui as funções utilitárias

proteger_pagina(); // Protege esta página. Se não estiver logado, redireciona.

// Verifica se o formulário foi enviado via POST
if (form_nao_enviado()) {
    set_mensagem('erro', 'Por favor, retorne à página inicial e preencha o formulário de agendamento.');
    header('Location: home.php');
    exit();
}

// Define os campos obrigatórios para o agendamento
// O campo 'cpf' pode ser removido daqui se não for estritamente obrigatório no seu formulário
$campos_obrigatorios_agendamento = ['nome', 'telefone', 'procedimento', 'data', 'hora'];

// Valida se todos os campos obrigatórios estão preenchidos
if (ha_campos_em_branco($_POST, $campos_obrigatorios_agendamento)) {
    set_mensagem('erro', 'Por favor, preencha todos os campos obrigatórios do agendamento.');
    header('Location: home.php'); // Redireciona de volta para o formulário
    exit();
}

// Obtém os dados do formulário
$nome = $_POST['nome'] ?? '';
$cpf = $_POST['cpf'] ?? ''; // CPF pode ser opcional ou vazio
$telefone = $_POST['telefone'] ?? '';
$procedimento = $_POST['procedimento'] ?? '';
$data = $_POST['data'] ?? '';
$hora = $_POST['hora'] ?? '';
$usuario_id = $_SESSION['usuario_id']; // Obtém o ID do usuário logado da sessão

$conn = conectar_banco(); // Conecta ao banco de dados

// Prepara a consulta SQL para inserir um novo agendamento
// Inclui 'usuario_id' na lista de colunas e parâmetros
$sql = "INSERT INTO tb_horarios (usuario_id, nome, cpf, telefone, procedimento, dta, hora) VALUES(?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Verifica se a preparação da consulta falhou
if (!$stmt) {
    set_mensagem('erro', 'Erro na preparação da consulta de agendamento: ' . mysqli_error($conn));
    error_log("Erro na preparação do insert de agendamento: " . mysqli_error($conn)); // Log para depuração
    header('Location: home.php');
    exit();
}

// Vincula os parâmetros à consulta preparada
// 'i' para integer (usuario_id), 's' para string (demais campos)
mysqli_stmt_bind_param($stmt, "issssss", $usuario_id, $nome, $cpf, $telefone, $procedimento, $data, $hora);

if (mysqli_stmt_execute($stmt)) {
    set_mensagem('sucesso', 'Agendamento cadastrado com sucesso!');
    header('Location: clientes.php'); // Redireciona para a lista de agendamentos
    exit();
} else {
    set_mensagem('erro', 'Erro ao cadastrar agendamento: ' . mysqli_stmt_error($stmt));
    error_log("Erro ao executar insert de agendamento: " . mysqli_stmt_error($stmt)); // Log para depuração
    header('Location: home.php'); // Redireciona de volta para o formulário
    exit();
}

// Fecha o statement e a conexão
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>