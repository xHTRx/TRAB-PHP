<?php
/**
 * processa_cadastro_usuario.php
 * Processa a submissão do formulário de cadastro de novo usuário.
 * Realiza validações, hasheia a senha e insere o novo usuário no banco de dados.
 */

// Inicia a sessão para poder usar $_SESSION e funções de mensagem
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/conexao.php'; // Inclui o arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui o arquivo com as funções utilitárias

// Verifica se o formulário foi enviado via POST. Se não, redireciona para a página de cadastro.
if (form_nao_enviado()) {
    set_mensagem('erro', 'Acesso inválido. Por favor, use o formulário de cadastro.');
    header('Location: cadastro_usuario.php');
    exit();
}

// Obtém os dados do formulário
$login = $_POST['login'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Define os campos obrigatórios para validação
$campos_obrigatorios = ['login', 'email', 'senha', 'confirmar_senha'];

// Validação de campos em branco
if (ha_campos_em_branco($_POST, $campos_obrigatorios)) {
    set_mensagem('erro', 'Todos os campos do formulário de cadastro são obrigatórios. Por favor, preencha-os.'); // Mensagem mais específica
    header('Location: cadastro_usuario.php');
    exit();
}

// Validação se as senhas coincidem
if ($senha !== $confirmar_senha) {
    set_mensagem('erro', 'As senhas não coincidem. Por favor, tente novamente.');
    header('Location: cadastro_usuario.php');
    exit();
}

// Validação do comprimento mínimo da senha (exemplo)
if (strlen($senha) < 6) {
    set_mensagem('erro', 'A senha deve ter no mínimo 6 caracteres.');
    header('Location: cadastro_usuario.php');
    exit();
}

// Conecta ao banco de dados
$conn = conectar_banco();

// Verifica se o login ou e-mail já existem no banco de dados
$query_check = "SELECT id FROM tb_usuarios WHERE login = ? OR email = ?";
$stmt_check = mysqli_prepare($conn, $query_check);

if (!$stmt_check) {
    set_mensagem('erro', 'Erro interno ao verificar dados existentes. Por favor, tente novamente.');
    error_log("Erro ao preparar consulta de verificação de usuário/email: " . mysqli_error($conn));
    mysqli_close($conn);
    header('Location: cadastro_usuario.php');
    exit();
}

mysqli_stmt_bind_param($stmt_check, "ss", $login, $email);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    set_mensagem('erro', 'Nome de usuário ou E-mail já cadastrados. Por favor, escolha outro.');
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    header('Location: cadastro_usuario.php');
    exit();
}
mysqli_stmt_close($stmt_check); // Fecha o statement de verificação

// Hasheia a senha antes de armazená-la no banco de dados por segurança
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere o novo usuário no banco de dados
$sql_insert = "INSERT INTO tb_usuarios (login, senha, email) VALUES (?, ?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);

if (!$stmt_insert) {
    set_mensagem('erro', 'Erro interno ao preparar o cadastro do usuário. Por favor, tente novamente.');
    error_log("Erro ao preparar insert de usuário: " . mysqli_error($conn));
    mysqli_close($conn);
    header('Location: cadastro_usuario.php');
    exit();
}

mysqli_stmt_bind_param($stmt_insert, "sss", $login, $senha_hash, $email);

if (mysqli_stmt_execute($stmt_insert)) {
    set_mensagem('sucesso', 'Usuário cadastrado com sucesso! Você já pode fazer login.');
    header('Location: index.php'); // Redireciona para a página de login
    exit();
} else {
    set_mensagem('erro', 'Erro ao cadastrar usuário. Por favor, tente novamente.');
    error_log("Erro ao executar insert de usuário: " . mysqli_stmt_error($stmt_insert));
    header('Location: cadastro_usuario.php');
    exit();
}

mysqli_stmt_close($stmt_insert);
mysqli_close($conn);

?>