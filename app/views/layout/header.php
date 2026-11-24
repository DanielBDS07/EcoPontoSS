<?php $u = usuario(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Reciclagem') ?> - EcoPonto SS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?rota=<?= $u ? paginaInicial($u['perfil']) : 'home' ?>">EcoPonto SS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
            <?php if (!$u): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=home">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=separacao">Como separar</a></li>
            <?php elseif ($u['perfil'] == 'usuario'): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=painel">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=nova_coleta">Nova coleta</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=minhas_coletas">Minhas coletas</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=separacao">Como separar</a></li>
            <?php elseif ($u['perfil'] == 'funcionario'): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=func_disponiveis">Disponíveis</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=func_reservadas">Reservadas</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=func_historico">Histórico</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=admin_painel">Painel</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=admin_coletas">Solicitações</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=admin_funcionarios">Funcionários</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?rota=admin_usuarios">Usuários</a></li>
            <?php endif; ?>
            </ul>
            <?php if ($u): ?>
                <span class="navbar-text me-3"><?= e($u['nome']) ?></span>
                <a class="btn btn-outline-light btn-sm" href="index.php?rota=logout">Sair</a>
            <?php else: ?>
                <a class="btn btn-outline-light btn-sm me-2" href="index.php?rota=login">Entrar</a>
                <a class="btn btn-light btn-sm" href="index.php?rota=cadastro">Criar conta</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mb-5">
<?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= e($_SESSION['msg']['tipo']) ?> alert-dismissible fade show">
        <?= e($_SESSION['msg']['texto']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['msg']); ?>
<?php endif; ?>
