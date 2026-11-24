<?php
global $MATERIAIS;
$dh = str_replace(' ', 'T', substr($c['data_hora_coleta'] ?? '', 0, 16));
$minimo = date('Y-m-d\TH:i', time() + 24 * 3600);
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>
<div class="card"><div class="card-body">
<form method="post" enctype="multipart/form-data" action="index.php?rota=<?= $acao ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tipo_material">Tipo de material</label>
            <select class="form-select" id="tipo_material" name="tipo_material" required>
                <option value="">Escolha...</option>
                <?php foreach ($MATERIAIS as $m): ?>
                    <option <?= ($c['tipo_material'] ?? '') == $m ? 'selected' : '' ?>><?= e($m) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="data_hora_coleta">Data e horário disponíveis</label>
            <input type="datetime-local" class="form-control" id="data_hora_coleta" name="data_hora_coleta"
                   min="<?= $minimo ?>" value="<?= e($dh) ?>" required>
            <div class="form-text">Mínimo de 24 horas de antecedência.</div>
        </div>
    </div>

    <h6 class="mt-2">Endereço da coleta</h6>
    <div class="row">
        <div class="col-md-8 mb-3">
            <label class="form-label" for="logradouro">Rua / Avenida</label>
            <input class="form-control" id="logradouro" name="logradouro" value="<?= v($c, 'logradouro') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="numero">Número</label>
            <input class="form-control" id="numero" name="numero" value="<?= v($c, 'numero') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="complemento">Complemento</label>
            <input class="form-control" id="complemento" name="complemento" value="<?= v($c, 'complemento') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="bairro">Bairro</label>
            <input class="form-control" id="bairro" name="bairro" value="<?= v($c, 'bairro') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="cep">CEP</label>
            <input class="form-control" id="cep" name="cep" value="<?= v($c, 'cep') ?>" placeholder="00000-000">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="cidade">Cidade</label>
            <input class="form-control" id="cidade" name="cidade" value="<?= isset($c['cidade']) ? v($c, 'cidade') : 'São Sebastião' ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label d-block">Localização (opcional)</label>
            <button type="button" class="btn btn-outline-success" id="btnLocal">Marcar minha localização atual</button>
            <span class="text-success ms-2" id="localOk"><?= !empty($c['latitude']) ? 'Localização marcada' : '' ?></span>
            <input type="hidden" name="latitude" id="latitude" value="<?= v($c, 'latitude') ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= v($c, 'longitude') ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="foto">Foto do material <?= $id ? '(deixe em branco para manter a atual)' : '' ?></label>
        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" <?= $id ? '' : 'required' ?>>
        <div class="form-text">JPG, PNG ou WebP, até 4 MB.</div>
        <?php if (!empty($c['foto'])): ?>
            <img src="uploads/<?= e($c['foto']) ?>" class="img-thumbnail mt-2" style="max-height:120px" alt="Foto atual">
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label" for="observacoes">Observações</label>
        <textarea class="form-control" id="observacoes" name="observacoes" rows="2"><?= v($c, 'observacoes') ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Salvar solicitação</button>
    <a class="btn btn-outline-secondary" href="index.php?rota=minhas_coletas">Cancelar</a>
</form>
</div></div>

<script>
// Pega a localização do navegador (se a pessoa permitir)
document.getElementById('btnLocal').addEventListener('click', function () {
    if (!navigator.geolocation) {
        alert('Seu navegador não suporta localização.');
        return;
    }
    navigator.geolocation.getCurrentPosition(function (pos) {
        document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
        document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
        document.getElementById('localOk').textContent = 'Localização marcada';
    }, function () {
        alert('Não foi possível pegar a localização.');
    });
});
</script>
