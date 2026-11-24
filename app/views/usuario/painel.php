<?php
$contagem = ['aguardando_aprovacao' => 0, 'aprovada' => 0, 'agendada' => 0, 'recusada' => 0];
foreach ($coletas as $c) {
    if (isset($contagem[$c['status']])) $contagem[$c['status']]++;
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Olá, <?= e(explode(' ', usuario()['nome'])[0]) ?>!</h1>
    <a class="btn btn-success" href="index.php?rota=nova_coleta">+ Nova coleta</a>
</div>

<div class="row g-3 mb-4 text-center">
    <div class="col-6 col-md-3"><div class="card"><div class="card-body">
        <div class="fs-3"><?= $contagem['aguardando_aprovacao'] ?></div><small>Aguardando aprovação</small>
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card"><div class="card-body">
        <div class="fs-3"><?= $contagem['aprovada'] ?></div><small>Aprovadas</small>
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card"><div class="card-body">
        <div class="fs-3"><?= $contagem['agendada'] ?></div><small>Agendadas</small>
    </div></div></div>
    <div class="col-6 col-md-3"><div class="card"><div class="card-body">
        <div class="fs-3"><?= $contagem['recusada'] ?></div><small>Recusadas</small>
    </div></div></div>
</div>

<h5>Minhas solicitações</h5>
<?php
$rotaVer = 'ver_coleta';
require __DIR__ . '/../partials/tabela_coletas.php';
require __DIR__ . '/../partials/informacoes.php';
?>
