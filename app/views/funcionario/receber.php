<h1 class="h3 mb-3">Registrar recebimento - coleta #<?= (int)$c['id'] ?></h1>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <p>Material pedido: <strong><?= e($c['tipo_material']) ?></strong><br>
               Endereço: <?= e(enderecoCompleto($c)) ?></p>
            <form method="post" action="index.php?rota=func_receber&id=<?= (int)$c['id'] ?>">
                <div class="mb-3">
                    <label class="form-label" for="material_recebido">Material efetivamente recebido</label>
                    <input class="form-control" id="material_recebido" name="material_recebido"
                           value="<?= isset($f['material_recebido']) ? v($f, 'material_recebido') : e($c['tipo_material']) ?>"
                           placeholder="Ex.: 3 sacos de garrafas PET" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="peso_kg">Peso aproximado (kg)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="peso_kg" name="peso_kg" value="<?= v($f, 'peso_kg') ?>">
                </div>
                <button type="submit" class="btn btn-success">Concluir coleta</button>
                <a class="btn btn-outline-secondary" href="index.php?rota=func_reservadas">Voltar</a>
            </form>
        </div></div>
    </div>
</div>
