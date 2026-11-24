<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h1 class="h4 mb-1">Cadastro de entregador</h1>
            <p class="text-muted">Depois de enviar, o administrador vai analisar seu cadastro. Você só consegue entrar depois da aprovação.</p>
            <form method="post" action="index.php?rota=cadastro_funcionario">
                <div class="mb-3">
                    <label class="form-label" for="nome">Nome</label>
                    <input class="form-control" id="nome" name="nome" value="<?= v($f, 'nome') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= v($f, 'email') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="telefone">Telefone</label>
                    <input class="form-control" id="telefone" name="telefone" value="<?= v($f, 'telefone') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="veiculo">Veículo que você usa</label>
                    <select class="form-select" id="veiculo" name="veiculo">
                        <?php foreach (['Bicicleta', 'Moto', 'Carro', 'Caminhonete', 'Outro'] as $op): ?>
                            <option <?= ($f['veiculo'] ?? '') == $op ? 'selected' : '' ?>><?= e($op) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="apresentacao">Fale um pouco sobre você</label>
                    <textarea class="form-control" id="apresentacao" name="apresentacao" rows="3"><?= v($f, 'apresentacao') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" minlength="6" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="senha2">Repita a senha</label>
                        <input type="password" class="form-control" id="senha2" name="senha2" minlength="6" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">Enviar cadastro</button>
            </form>
        </div></div>
    </div>
</div>
