<?php
/**
 * processa_login.php
 * Processa a submissão do formulário de login.
 * Valida credenciais e gerencia a sessão do usuário.
 */

// Inicia a sessão para poder usar $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/conexao.php'; // Inclui o arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui o arquivo com as funções utilitárias

// Verifica se o formulário foi enviado via POST. Se não, redireciona para a página inicial.
if (form_nao_enviado()) {
    set_mensagem('erro', 'Acesso inválido. Por favor, use o formulário de login.');
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Obtém os dados do formulário, usando o operador de coalescência nula (??) para evitar erros se a chave não existir
$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

// Define os campos obrigatórios para a validação
$campos_obrigatorios = ['usuario', 'senha'];

// Verifica se os campos obrigatórios estão em branco
if (ha_campos_em_branco($_POST, $campos_obrigatorios)) {
    set_mensagem('erro', 'Por favor, preencha o nome de usuário e a senha para fazer login.'); // Mensagem mais específica
    header('Location: index.php'); // Redireciona para o login sem o parâmetro 'code'
    exit();
}

// Conecta ao banco de dados
$conn = conectar_banco();

// Prepara a consulta SQL para buscar o ID e o hash da senha do usuário
// O login é usado para encontrar o usuário
$query = "SELECT id, senha FROM tb_usuarios WHERE login = ?";
$stmt = mysqli_prepare($conn, $query);

// Verifica se a preparação da consulta falhou
if (!$stmt) {
    set_mensagem('erro', 'Erro interno ao preparar a consulta de login. Por favor, tente novamente.');
    error_log("Erro ao preparar a consulta de login: " . mysqli_error($conn)); // Registra o erro no log do servidor
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

mysqli_stmt_bind_param($stmt, 's', $usuario);

// Executa a consulta
if (!mysqli_stmt_execute($stmt)) {
    set_mensagem('erro', 'Erro ao executar a consulta de login. Por favor, tente novamente.');
    error_log("Erro ao executar a consulta de login: " . mysqli_stmt_error($stmt)); // Registra o erro
    header('Location: index.php');
    exit();
}

// Armazena o resultado da consulta para poder verificar o número de linhas e buscar os dados
mysqli_stmt_store_result($stmt);

// Se nenhuma linha foi retornada, o usuário não existe ou as credenciais estão incorretas
if (mysqli_stmt_num_rows($stmt) == 0) {
    set_mensagem('erro', 'Usuário ou senha inválidos. Tente novamente.');
    header('Location: index.php'); // Redireciona para o login sem o parâmetro 'code'
    exit();
}

// Vincula as colunas do resultado a variáveis PHP
mysqli_stmt_bind_result($stmt, $usuario_id, $senha_hash_armazenada);
// Busca a linha de resultados
mysqli_stmt_fetch($stmt);

// Verifica a senha fornecida com o hash armazenado usando password_verify()
if (password_verify($senha, $senha_hash_armazenada)) {
    // Login bem-sucedido: Armazena o ID e o login do usuário na sessão
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario_login'] = $usuario;
    
    set_mensagem('sucesso', 'Login realizado com sucesso! Bem-vindo(a), ' . htmlspecialchars($usuario) . '!');
    header('Location: home.php'); // Redireciona para a página principal do sistema
    exit();
} else {
    // Senha incorreta
    set_mensagem('erro', 'Usuário ou senha inválidos. Tente novamente.');
    header('Location: index.php'); // Redireciona para o login sem o parâmetro 'code'
    exit();
}

// Fecha o statement e a conexão com o banco de dados
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>