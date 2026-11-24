<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= e($titulo) ?></h1>
    <a class="btn btn-outline-secondary" href="index.php?rota=func_disponiveis">Voltar</a>
</div>

<div class="mb-3">
<?php if ($c['status'] == 'aprovada'): ?>
    <form method="post" action="index.php?rota=func_reservar">
        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
        <button type="submit" class="btn btn-success">Reservar esta coleta</button>
    </form>
<?php elseif ($c['status'] == 'agendada'): ?>
    <form method="post" action="index.php?rota=func_coletar" onsubmit="return confirm('Confirmar que a coleta foi realizada?');">
        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
        <button type="submit" class="btn btn-success">Marcar como coletada</button>
    </form>
<?php elseif ($c['status'] == 'coletada'): ?>
    <a class="btn btn-success" href="index.php?rota=func_receber&id=<?= (int)$c['id'] ?>">Registrar recebimento</a>
<?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/detalhe.php'; ?>
