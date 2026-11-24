<?php

require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $existe = $pdo->query("SHOW DATABASES LIKE 'reciclagem'")->fetch();
    if ($existe) {
        $pdo->exec('USE reciclagem');
        $tabela = $pdo->query("SHOW TABLES LIKE 'usuario'")->fetch();
    } else {
        $tabela = false;
    }
    if (!$tabela) {
        $pdo->exec(file_get_contents(__DIR__ . '/sql/reciclagem.sql'));
        echo 'Banco e tabelas criados.<br>';
    } else {
        echo 'As tabelas já existiam.<br>';
    }

    //Cria os usuários de teste
    $contas = [
        ['Administrador',     'admin@reciclagem.com',      '(12) 90000-0001', 'admin',       'aprovado', null,          null],
        ['Carlos Entregador', 'entregador@reciclagem.com', '(12) 90000-0002', 'funcionario', 'aprovado', 'Caminhonete', 'Faço coletas na região central.'],
        ['Paulo Candidato',   'candidato@reciclagem.com',  '(12) 90000-0004', 'funcionario', 'pendente',  'Moto',        'Quero trabalhar como entregador.'],
        ['Ana Moradora',      'usuario@reciclagem.com',    '(12) 90000-0003', 'usuario',     'aprovado', null,          null],
    ];
    $st = db()->prepare(
        'INSERT IGNORE INTO usuario (nome, email, telefone, senha_hash, perfil, status_cadastro, veiculo, apresentacao)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($contas as $c) {
        $st->execute([$c[0], $c[1], $c[2], password_hash('123456', PASSWORD_DEFAULT), $c[3], $c[4], $c[5], $c[6]]);
    }
    echo 'Usuários criados. Senha de todos: 123456.<br>Agora apague o arquivo instalar.php.';
} catch (Exception $erro) {
    echo 'Erro: ' . htmlspecialchars($erro->getMessage());
}
