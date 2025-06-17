<?php
/**
 * verificacoes.php
 * Este arquivo agora é exclusivamente responsável por exibir mensagens de feedback
 * (sucesso/erro) que foram definidas na sessão.
 */

// Garante que a sessão seja iniciada antes de usar $_SESSION para mensagens
// O 'session_start()' no 'index.php' já lida com isso, mas esta verificação é boa prática.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


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

?>