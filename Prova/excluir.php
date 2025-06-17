<?php 
/**
 * excluir.php
 * Processa a exclusão de um agendamento.
 * Garante que apenas o proprietário do agendamento possa excluí-lo.
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

// Verifica se o ID do agendamento foi fornecido na URL
if (!isset($_GET['id'])) {
    set_mensagem('erro', 'ID do agendamento não informado para exclusão.');
    header('Location: clientes.php'); // Redireciona para a lista
    exit();
}

$id = (int)$_GET['id']; // Converte o ID para inteiro para segurança
$conn = conectar_banco(); // Conecta ao banco de dados
        
// VERIFICAÇÃO DE SEGURANÇA: Garante que o agendamento pertence ao usuário logado

// O agendamento com este ID (?) pertence ao usuário que está tentando excluí-lo?
$check_owner_sql = "SELECT usuario_id FROM tb_horarios WHERE id = ?";
$stmt_owner = mysqli_prepare($conn, $check_owner_sql);
mysqli_stmt_bind_param($stmt_owner, "i", $id);
mysqli_stmt_execute($stmt_owner);
mysqli_stmt_bind_result($stmt_owner, $owner_id);
mysqli_stmt_fetch($stmt_owner);
mysqli_stmt_close($stmt_owner);

// Se o agendamento não for encontrado ou não pertencer ao usuário logado
if ($owner_id !== $usuario_id_logado) {
    set_mensagem('erro', 'Você não tem permissão para excluir este agendamento.');
    header('Location: clientes.php'); // Redireciona para a lista
    exit();
}

// Prepara a consulta SQL para deletar o agendamento
$sql = "DELETE FROM tb_horarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    set_mensagem('erro', 'Erro na preparação da consulta de exclusão: ' . mysqli_error($conn));
    error_log("Erro na preparação do delete de agendamento: " . mysqli_error($conn));
} else {
    mysqli_stmt_bind_param($stmt, "i", $id); // Vincula o ID do agendamento
    
    // Executa a consulta
    if (mysqli_stmt_execute($stmt)){
        // Verifica se alguma linha foi afetada (se o agendamento realmente foi excluído)
        if(mysqli_affected_rows($conn) == 0) {
            set_mensagem('erro', 'Erro ao excluir agendamento! Agendamento inexistente ou já excluído.');
        } else {
            set_mensagem('sucesso', 'Agendamento excluído com sucesso!');
        }
    } else {
        set_mensagem('erro', 'Erro ao excluir agendamento: ' . mysqli_stmt_error($stmt));
        error_log("Erro ao executar delete de agendamento: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt); // Fecha o statement
}
mysqli_close($conn); // Fecha a conexão

// Redireciona sempre para a lista de agendamentos após a tentativa de exclusão
header('Location: clientes.php');
exit();

?>