<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= e($titulo) ?></h1>
    <a class="btn btn-outline-secondary" href="index.php?rota=admin_coletas">Voltar</a>
</div>

<?php if ($c['status'] == 'aguardando_aprovacao'): ?>
<div class="card mb-4"><div class="card-body">
    <h5>Analisar solicitação</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <form method="post" action="index.php?rota=admin_aprovar">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button type="submit" class="btn btn-success w-100">Aprovar</button>
            </form>
        </div>
        <div class="col-md-8">
            <form method="post" action="index.php?rota=admin_recusar" class="input-group">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <input class="form-control" name="motivo" placeholder="Motivo da recusa" required>
                <button type="submit" class="btn btn-danger">Recusar</button>
            </form>
        </div>
    </div>
</div></div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/detalhe.php'; ?>
