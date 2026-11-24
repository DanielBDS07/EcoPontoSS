<h1 class="h3 mb-3">Funcionários (entregadores)</h1>
<?php if (!$lista): ?>
    <div class="alert alert-info">Nenhum cadastro de entregador ainda.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped align-middle bg-white">
    <thead class="table-success">
        <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Veículo</th><th>Apresentação</th><th>Cadastro</th><th></th></tr>
    </thead>
    <tbody>
    <?php
    $cores = ['pendente' => 'warning text-dark', 'aprovado' => 'success', 'recusado' => 'danger'];
    foreach ($lista as $f): ?>
        <tr>
            <td><?= e($f['nome']) ?></td>
            <td><?= e($f['email']) ?></td>
            <td><?= e($f['telefone']) ?></td>
            <td><?= e($f['veiculo']) ?></td>
            <td class="small"><?= e($f['apresentacao']) ?></td>
            <td><span class="badge bg-<?= $cores[$f['status_cadastro']] ?>"><?= e(ucfirst($f['status_cadastro'])) ?></span></td>
            <td class="text-nowrap">
                <?php if ($f['status_cadastro'] != 'aprovado'): ?>
                <form class="d-inline" method="post" action="index.php?rota=admin_func_status">
                    <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                    <input type="hidden" name="status" value="aprovado">
                    <button class="btn btn-sm btn-success" type="submit">Aprovar</button>
                </form>
                <?php endif; ?>
                <?php if ($f['status_cadastro'] != 'recusado'): ?>
                <form class="d-inline" method="post" action="index.php?rota=admin_func_status">
                    <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                    <input type="hidden" name="status" value="recusado">
                    <button class="btn btn-sm btn-danger" type="submit">Recusar</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
