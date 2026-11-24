<?php
// Área do administrador
class AdminController
{
    public function painel()
    {
        exigir(['admin']);
        $ind = [
            'usuarios'     => Usuario::contar('usuario'),
            'funcionarios' => Usuario::contar('funcionario', 'aprovado'),
            'func_pendentes' => Usuario::contar('funcionario', 'pendente'),
            'realizadas'   => Coleta::contar(['coletada', 'concluida']),
            'pendentes'    => Coleta::contar(['aguardando_aprovacao']),
        ];
        render('admin/painel', ['titulo' => 'Painel do administrador', 'ind' => $ind]);
    }

    public function coletas()
    {
        exigir(['admin']);
        $filtro = $_GET['status'] ?? '';
        $filtros = $filtro != '' ? ['status' => [$filtro]] : [];
        $coletas = Coleta::listar($filtros, 'c.criado_em DESC');
        render('admin/coletas', ['titulo' => 'Solicitações de coleta', 'coletas' => $coletas, 'filtro' => $filtro]);
    }

    public function coletaVer()
    {
        exigir(['admin']);
        $c = Coleta::buscar((int)($_GET['id'] ?? 0));
        if (!$c) {
            flash('Solicitação não encontrada.', 'danger');
            redirect('admin_coletas');
        }
        render('admin/coleta_ver', ['titulo' => 'Coleta #' . $c['id'], 'c' => $c, 'historico' => Coleta::historico($c['id'])]);
    }

    public function aprovar()
    {
        exigir(['admin']);
        $id = (int)($_POST['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::aprovar($id, usuario()['id']);
                flash('Solicitação aprovada. Ela já aparece para os entregadores.');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        redirect('admin_coleta_ver', '&id=' . $id);
    }

    public function recusar()
    {
        exigir(['admin']);
        $id = (int)($_POST['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::recusar($id, usuario()['id'], $_POST['motivo'] ?? '');
                flash('Solicitação recusada.');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        redirect('admin_coleta_ver', '&id=' . $id);
    }

    public function funcionarios()
    {
        exigir(['admin']);
        render('admin/funcionarios', ['titulo' => 'Funcionários', 'lista' => Usuario::listar('funcionario')]);
    }

    public function funcionarioStatus()
    {
        exigir(['admin']);
        $id = (int)($_POST['id'] ?? 0);
        $novo = $_POST['status'] ?? '';
        $f = Usuario::buscar($id);
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $f && $f['perfil'] == 'funcionario' && in_array($novo, ['aprovado', 'recusado'])) {
            Usuario::definirStatus($id, $novo);
            flash($novo == 'aprovado' ? 'Funcionário aprovado.' : 'Funcionário recusado.');
        }
        redirect('admin_funcionarios');
    }

    public function usuarios()
    {
        exigir(['admin']);
        render('admin/usuarios', ['titulo' => 'Usuários', 'lista' => Usuario::listar('usuario')]);
    }
}
