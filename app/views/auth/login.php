<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card"><div class="card-body">
            <h1 class="h4 mb-3">Entrar</h1>
            <form method="post" action="index.php?rota=login">
                <div class="mb-3">
                    <label class="form-label" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Entrar</button>
            </form>
            <hr>
            <p class="mb-1">Não tem conta? <a href="index.php?rota=cadastro">Criar conta</a></p>
            <p class="mb-0">Quer trabalhar com a gente? <a href="index.php?rota=cadastro_funcionario">Cadastro de entregador</a></p>
        </div></div>
    </div>
</div>
