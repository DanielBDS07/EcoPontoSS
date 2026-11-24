<?php // Detalhes de uma coleta. Variáveis: $c e $historico ?>
<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <img src="uploads/<?= e($c['foto']) ?>" class="card-img-top" alt="Foto do material">
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= e($c['tipo_material']) ?> <?= statusBadge($c['status']) ?></h5>
                <p class="mb-1"><strong>Endereço:</strong> <?= e(enderecoCompleto($c)) ?><?= $c['cep'] ? ' - CEP ' . e($c['cep']) : '' ?></p>
                <p class="mb-1"><strong>Data e hora disponíveis:</strong> <?= e(dataHoraBr($c['data_hora_coleta'])) ?></p>
                <p class="mb-1"><strong>Solicitante:</strong> <?= e($c['solicitante']) ?> (<?= e($c['solicitante_telefone']) ?>)</p>
                <?php if ($c['observacoes']): ?>
                    <p class="mb-1"><strong>Observações:</strong> <?= e($c['observacoes']) ?></p>
                <?php endif; ?>
                <?php if ($c['funcionario']): ?>
                    <p class="mb-1"><strong>Entregador:</strong> <?= e($c['funcionario']) ?></p>
                <?php endif; ?>
                <?php if ($c['motivo_recusa']): ?>
                    <p class="mb-1 text-danger"><strong>Motivo da recusa:</strong> <?= e($c['motivo_recusa']) ?></p>
                <?php endif; ?>
                <?php if ($c['material_recebido']): ?>
                    <p class="mb-1"><strong>Material recebido:</strong> <?= e($c['material_recebido']) ?>
                        <?= $c['peso_kg'] !== null ? '(' . e($c['peso_kg']) . ' kg)' : '' ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <h5>Histórico de status</h5>
        <ul class="list-group">
        <?php foreach ($historico as $h): ?>
            <li class="list-group-item">
                <?= statusBadge($h['status_novo']) ?>
                <small class="text-muted d-block"><?= e(dataHoraBr($h['alterado_em'])) ?> - <?= e($h['responsavel']) ?></small>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>
