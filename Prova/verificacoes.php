<?php
/**
 * verificacoes.php
 * Este arquivo agora é exclusivamente responsável por exibir mensagens de feedback
 * (sucesso/erro) que foram definidas na sessão.
 *
 * TODA a lógica de login foi movida para 'processa_login.php'.
 * O uso de $_GET['code'] para mensagens diretas foi removido em favor das mensagens de sessão.
 */

// Garante que a sessão seja iniciada antes de usar $_SESSION para mensagens
// O 'session_start()' no 'index.php' já lida com isso, mas esta verificação é boa prática.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// IMPORTANTE: Este arquivo DEVE incluir 'includes/funcoes.php' para ter 'get_mensagem()' disponível.
// Certifique-se de que 'includes/funcoes.php' existe e o caminho está correto.
require_once 'includes/funcoes.php'; 

/**
 * Exibe mensagens de erro ou sucesso armazenadas na sessão.
 * Esta função é agora o principal ponto para exibir feedback ao usuário
 * nas páginas que a incluem (como index.php).
 */
function tratar_erros() {
    // Apenas chama get_mensagem() para exibir qualquer mensagem que tenha sido definida na sessão.
    echo get_mensagem();
}

// As funções 'form_nao_enviado' e 'ha_campos_em_branco' estão em 'includes/funcoes.php'.
// Não são mais definidas ou utilizadas diretamente neste arquivo.

?>