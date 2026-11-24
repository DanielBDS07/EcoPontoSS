<?php
class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $u = Usuario::buscarPorEmail(trim($_POST['email'] ?? ''));

            if (!$u || !password_verify($_POST['senha'] ?? '', $u['senha_hash'])) {
                flash('E-mail ou senha incorretos.', 'danger');
                redirect('login');
            }
            if ($u['perfil'] == 'funcionario' && $u['status_cadastro'] == 'pendente') {
                flash('Seu cadastro ainda está aguardando a aprovação do administrador.', 'warning');
                redirect('login');
            }
            if ($u['perfil'] == 'funcionario' && $u['status_cadastro'] == 'recusado') {
                flash('Seu cadastro de entregador foi recusado.', 'danger');
                redirect('login');
            }

            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => $u['id'], 'nome' => $u['nome'], 'perfil' => $u['perfil']];
            redirect(paginaInicial($u['perfil']));
        }
        render('auth/login', ['titulo' => 'Entrar']);
    }

    // valida os campos comuns do cadastro. Retorna a mensagem de erro ou '' se estiver ok.
    private function validar()
    {
        if (trim($_POST['nome'] ?? '') == '' || !filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            return 'Preencha o nome e um e-mail válido.';
        }
        if (strlen($_POST['senha'] ?? '') < 6) {
            return 'A senha precisa ter pelo menos 6 caracteres.';
        }
        if (($_POST['senha'] ?? '') != ($_POST['senha2'] ?? '')) {
            return 'As senhas não são iguais.';
        }
        if (Usuario::buscarPorEmail(trim($_POST['email']))) {
            return 'Já existe uma conta com esse e-mail.';
        }
        return '';
    }

    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $erro = $this->validar();
            if ($erro == '') {
                Usuario::criar([
                    'nome' => trim($_POST['nome']), 'email' => trim($_POST['email']),
                    'telefone' => trim($_POST['telefone'] ?? ''), 'senha' => $_POST['senha'],
                    'perfil' => 'usuario', 'status_cadastro' => 'aprovado',
                ]);
                flash('Conta criada! Agora é só entrar.');
                redirect('login');
            }
            flash($erro, 'danger');
        }
        render('auth/cadastro', ['titulo' => 'Criar conta', 'f' => $_POST]);
    }

    public function cadastroFuncionario()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $erro = $this->validar();
            if ($erro == '' && trim($_POST['telefone'] ?? '') == '') {
                $erro = 'Informe um telefone para contato.';
            }
            if ($erro == '') {
                Usuario::criar([
                    'nome' => trim($_POST['nome']), 'email' => trim($_POST['email']),
                    'telefone' => trim($_POST['telefone']), 'senha' => $_POST['senha'],
                    'perfil' => 'funcionario', 'status_cadastro' => 'pendente',
                    'veiculo' => trim($_POST['veiculo'] ?? ''), 'apresentacao' => trim($_POST['apresentacao'] ?? ''),
                ]);
                flash('Cadastro enviado! Aguarde a aprovação do administrador para entrar.');
                redirect('login');
            }
            flash($erro, 'danger');
        }
        render('auth/cadastro_funcionario', ['titulo' => 'Quero ser entregador', 'f' => $_POST]);
    }

    public function logout()
    {
        unset($_SESSION['usuario']);
        flash('Você saiu da sua conta.');
        redirect('login');
    }
}
