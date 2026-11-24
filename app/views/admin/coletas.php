<?php global $STATUS; ?>
<h1 class="h3 mb-3">Solicitações de coleta</h1>
<form class="row g-2 mb-3" method="get" action="index.php">
    <input type="hidden" name="rota" value="admin_coletas">
    <div class="col-auto">
        <select class="form-select" name="status">
            <option value="">Todas as situações</option>
            <?php foreach ($STATUS as $chave => $info): ?>
                <option value="<?= $chave ?>" <?= $filtro == $chave ? 'selected' : '' ?>><?= e($info[0]) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-success" type="submit">Filtrar</button></div>
</form>
<?php
$rotaVer = 'admin_coleta_ver';
$comSolicitante = true;
require __DIR__ . '/../partials/tabela_coletas.php';
?>
