<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Minhas coletas</h1>
    <a class="btn btn-success" href="index.php?rota=nova_coleta">+ Nova coleta</a>
</div>
<?php
$rotaVer = 'ver_coleta';
require __DIR__ . '/../partials/tabela_coletas.php';
?>
