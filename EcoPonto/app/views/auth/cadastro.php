<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h1 class="h4 mb-3">Criar conta</h1>
            <form method="post" action="index.php?rota=cadastro">
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
                    <input class="form-control" id="telefone" name="telefone" value="<?= v($f, 'telefone') ?>" placeholder="(12) 90000-0000">
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
                <button type="submit" class="btn btn-success w-100">Criar conta</button>
            </form>
            <p class="mt-3 mb-0">Já tem conta? <a href="index.php?rota=login">Entrar</a></p>
        </div></div>
    </div>
</div>
