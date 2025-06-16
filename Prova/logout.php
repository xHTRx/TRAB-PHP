<?php 
/**
 * logout.php
 * Encerra a sessão do usuário e redireciona para a página de login.
 */

// Inicia a sessão para poder manipular as variáveis de sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION);     // Limpa todas as variáveis de sessão
session_destroy();    // Destrói a sessão atual

// Redireciona o usuário para a página de login (index.php)
header('location:index.php');
exit(); // Garante que o script para de executar após o redirecionamento
?>