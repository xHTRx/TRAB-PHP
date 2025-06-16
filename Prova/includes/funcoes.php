<?php

/**
 * funcoes.php
 * Arquivo que contém funções utilitárias para o projeto, incluindo
 * validações de formulário, manipulação de mensagens de sessão e controle de autenticação.
 */

// Garante que a sessão seja iniciada antes de usar $_SESSION
// Importante: Este bloco deve ser o primeiro código PHP executado neste arquivo.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se a requisição HTTP não foi um POST.
 * Usado para garantir que o formulário foi enviado corretamente.
 *
 * @return bool True se a requisição não for POST, false caso contrário.
 */
function form_nao_enviado() {
    return $_SERVER['REQUEST_METHOD'] != 'POST';
}

/**
 * Verifica se há campos em branco em um array de dados.
 *
 * @param array $dados Array associativo contendo os dados do formulário (ex: $_POST).
 * @param array $campos_obrigatorios Array de strings com os nomes dos campos que devem ser verificados.
 * @return bool True se algum campo obrigatório estiver em branco, false caso contrário.
 */
function ha_campos_em_branco($dados, $campos_obrigatorios) {
    foreach ($campos_obrigatorios as $campo) {
        // Verifica se o campo não existe ou está vazio após remover espaços em branco
        if (!isset($dados[$campo]) || trim($dados[$campo]) === '') {
            return true;
        }
    }
    return false;
}

/**
 * Define uma mensagem de feedback (sucesso ou erro) na sessão.
 *
 * @param string $tipo Tipo da mensagem ('sucesso' ou 'erro').
 * @param string $texto Conteúdo da mensagem.
 */
function set_mensagem($tipo, $texto) {
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

/**
 * Recupera e exibe uma mensagem de feedback armazenada na sessão.
 * A mensagem é removida da sessão após ser exibida.
 * Utiliza classes Bootstrap para estilização.
 *
 * @return string HTML da mensagem formatada, ou string vazia se não houver mensagem.
 */
function get_mensagem() {
    if (isset($_SESSION['mensagem'])) {
        $mensagem = $_SESSION['mensagem'];
        unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
        
        // Define a classe CSS do alerta com base no tipo da mensagem
        $classe_alert = ($mensagem['tipo'] == 'sucesso') ? 'alert-success' : 'alert-danger';
        
        // Retorna o HTML completo para o alerta Bootstrap
        return '<div class="alert ' . $classe_alert . ' alert-dismissible fade show" role="alert">' . 
               htmlspecialchars($mensagem['texto']) . // Escapa o texto para segurança XSS
               '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' .
               '</div>';
    }
    return ''; // Retorna vazio se não houver mensagem na sessão
}

/**
 * Verifica se o usuário está logado.
 *
 * @return bool True se o 'usuario_id' estiver definido na sessão, false caso contrário.
 */
function usuario_logado() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Protege uma página, redirecionando o usuário para a página de login
 * se ele não estiver autenticado.
 * Define uma mensagem de erro na sessão antes de redirecionar.
 */
function proteger_pagina() {
    if (!usuario_logado()) {
        set_mensagem('erro', 'Você precisa estar logado para acessar esta página.');
        // Redireciona para o login sem o parâmetro 'code', a mensagem já está na sessão
        header('Location: index.php'); 
        exit();
    }
}

?>