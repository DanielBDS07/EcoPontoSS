<h1 class="h3 mb-3">Painel do administrador</h1>
<div class="row g-3 mb-4 text-center">
    <div class="col-6 col-md-3"><div class="card text-bg-success"><div class="card-body">
        <div class="display-6"><?= $ind['usuarios'] ?></div>Usuários cadastrados
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card text-bg-success"><div class="card-body">
        <div class="display-6"><?= $ind['funcionarios'] ?></div>Funcionários aprovados
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card text-bg-success"><div class="card-body">
        <div class="display-6"><?= $ind['realizadas'] ?></div>Coletas realizadas
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card text-bg-warning"><div class="card-body">
        <div class="display-6"><?= $ind['pendentes'] ?></div>Solicitações pendentes
    </div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-6"><div class="card"><div class="card-body">
        <h5>Solicitações</h5>
        <p>Há <strong><?= $ind['pendentes'] ?></strong> solicitação(ões) aguardando aprovação.</p>
        <a class="btn btn-success" href="index.php?rota=admin_coletas&status=aguardando_aprovacao">Analisar pendentes</a>
    </div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-body">
        <h5>Entregadores</h5>
        <p>Há <strong><?= $ind['func_pendentes'] ?></strong> cadastro(s) de entregador aguardando análise.</p>
        <a class="btn btn-success" href="index.php?rota=admin_funcionarios">Ver funcionários</a>
    </div></div></div>
</div>
