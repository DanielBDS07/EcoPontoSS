
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
    <div class="col-md-7">
        <h5>Localização</h5>
        <div class="ratio ratio-16x9">
            <iframe src="https://maps.google.com/maps?q=<?= rawurlencode(consultaMapa($c)) ?>&z=16&output=embed" loading="lazy"></iframe>
        </div>
        <a class="btn btn-sm btn-outline-success mt-2" target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode(consultaMapa($c)) ?>">Abrir no Google Maps</a>
    </div>
    <div class="col-md-5">
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
