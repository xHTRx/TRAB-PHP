CREATE DATABASE IF NOT EXISTS bd_clinica;
USE bd_clinica;

-- Tabela para Usuários
CREATE TABLE IF NOT EXISTS tb_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Armazenar o HASH da senha (ex: usar password_hash() no PHP)
    email VARCHAR(255) UNIQUE -- Adicionado como requisito, mas pode ser opcional se não for usado no login
);

-- Tabela para Horários/Agendamentos
CREATE TABLE IF NOT EXISTS tb_horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL, -- Chave estrangeira para associar o agendamento ao usuário
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14), 
    telefone VARCHAR(20) NOT NULL,
    procedimento VARCHAR(255) NOT NULL,
    dta DATE NOT NULL,
    hora TIME NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE
);

