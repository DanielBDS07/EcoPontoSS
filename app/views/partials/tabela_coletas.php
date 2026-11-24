<?php
// Tabela de coletas. Variáveis: $coletas, $rotaVer e (opcional) $comSolicitante
$comSolicitante = $comSolicitante ?? false;
?>
<?php if (!$coletas): ?>
    <div class="alert alert-info">Nenhuma coleta por aqui.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover bg-white">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Data e hora</th>
                <th>Material</th>
                <th>Bairro</th>
                <?php if ($comSolicitante): ?><th>Solicitante</th><?php endif; ?>
                <th>Situação</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($coletas as $c): ?>
            <tr>
                <td><?= (int)$c['id'] ?></td>
                <td><?= e(dataHoraBr($c['data_hora_coleta'])) ?></td>
                <td><?= e($c['tipo_material']) ?></td>
                <td><?= e($c['bairro']) ?></td>
                <?php if ($comSolicitante): ?><td><?= e($c['solicitante']) ?></td><?php endif; ?>
                <td><?= statusBadge($c['status']) ?></td>
                <td><a class="btn btn-sm btn-outline-success" href="index.php?rota=<?= $rotaVer ?>&id=<?= (int)$c['id'] ?>">Ver</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
