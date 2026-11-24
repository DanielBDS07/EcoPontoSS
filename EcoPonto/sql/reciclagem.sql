CREATE DATABASE IF NOT EXISTS reciclagem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reciclagem;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('usuario','funcionario','admin') NOT NULL DEFAULT 'usuario',
    status_cadastro ENUM('pendente','aprovado','recusado') NOT NULL DEFAULT 'aprovado',
    veiculo VARCHAR(60) NULL,
    apresentacao TEXT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE coleta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    funcionario_id INT NULL,
    tipo_material VARCHAR(40) NOT NULL,
    logradouro VARCHAR(120) NOT NULL,
    numero VARCHAR(15) NOT NULL,
    complemento VARCHAR(60) NULL,
    bairro VARCHAR(60) NOT NULL,
    cidade VARCHAR(60) NOT NULL,
    cep VARCHAR(9) NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    data_hora_coleta DATETIME NOT NULL,
    foto VARCHAR(80) NOT NULL,
    observacoes TEXT NULL,
    status ENUM('aguardando_aprovacao','aprovada','recusada','agendada','coletada','concluida')
        NOT NULL DEFAULT 'aguardando_aprovacao',
    motivo_recusa VARCHAR(255) NULL,
    material_recebido VARCHAR(160) NULL,
    peso_kg DECIMAL(8,2) NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id),
    FOREIGN KEY (funcionario_id) REFERENCES usuario(id),
    INDEX idx_status (status),
    INDEX idx_usuario (usuario_id),
    INDEX idx_funcionario (funcionario_id)
);

CREATE TABLE historico_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coleta_id INT NOT NULL,
    status_anterior VARCHAR(30) NULL,
    status_novo VARCHAR(30) NOT NULL,
    alterado_por INT NOT NULL,
    observacao VARCHAR(255) NULL,
    alterado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coleta_id) REFERENCES coleta(id) ON DELETE CASCADE,
    FOREIGN KEY (alterado_por) REFERENCES usuario(id)
);

-- Os usuários de teste (admin, funcionário e usuário) são criados rodando instalar.php.
