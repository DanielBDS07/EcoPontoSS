<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= e($titulo) ?></h1>
    <a class="btn btn-outline-secondary" href="index.php?rota=minhas_coletas">Voltar</a>
</div>

<?php if ($c['status'] == 'aguardando_aprovacao'): ?>
    <div class="mb-3">
        <a class="btn btn-outline-success" href="index.php?rota=editar_coleta&id=<?= (int)$c['id'] ?>">Editar</a>
        <form class="d-inline" method="post" action="index.php?rota=excluir_coleta" onsubmit="return confirm('Excluir esta solicitação?');">
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
            <button type="submit" class="btn btn-outline-danger">Excluir</button>
        </form>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/detalhe.php'; ?>
