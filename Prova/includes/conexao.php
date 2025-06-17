<?php

/**
 * Funções de conexão com o banco de dados.
 */

/**
 * Estabelece e retorna uma conexão com o banco de dados MySQL.
 * Configurações de conexão: localhost, root, sem senha, banco 'bd_clinica', porta 3307.
 * Define o charset para UTF-8.
 *
 * @return mysqli Objeto de conexão MySQLi.
 * @throws Exception Se a conexão falhar.
 */
function conectar_banco(){
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "bd_clinica";
    
    // // Tenta estabelecer a conexão com o banco de dados usando mysqli_connect
    // // Inclui a porta explicitamente
    // $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

    $port = 3307; // minha porta
    $conn = mysqli_connect($servidor, $usuario, $senha, $banco, $port);

    // Verifica se a conexão foi bem-sucedida
    if (!$conn) {
        // Registra o erro em um log do servidor (útil para depuração)
        error_log("Erro de conexão com o banco de dados: " . mysqli_connect_error());
        // Encerra a execução e exibe uma mensagem amigável ao usuário
        exit("<h2>Erro de Conexão</h2><p>Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.</p>");
    }
    
    // Define o conjunto de caracteres da conexão para UTF-8 para evitar problemas de acentuação
    mysqli_set_charset($conn, "utf8");

    return $conn; // Retorna o objeto de conexão
}

?>