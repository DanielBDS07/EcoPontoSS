<h1 class="h3 mb-3">Usuários cadastrados</h1>
<?php if (!$lista): ?>
    <div class="alert alert-info">Nenhum usuário ainda.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped bg-white">
    <thead class="table-success"><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Cadastro</th></tr></thead>
    <tbody>
    <?php foreach ($lista as $u): ?>
        <tr>
            <td><?= e($u['nome']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e($u['telefone']) ?></td>
            <td><?= e(dataHoraBr($u['criado_em'])) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
