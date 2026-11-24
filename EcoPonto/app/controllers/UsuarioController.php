<?php
// Área do usuário (quem pede a coleta)
class UsuarioController
{
    public function painel()
    {
        exigir(['usuario']);
        // só mostra pedidos em andamento ou recusados (concluídos não aparecem aqui)
        $filtros = ['usuario_id' => usuario()['id'], 'status' => ['aguardando_aprovacao', 'aprovada', 'recusada', 'agendada', 'coletada']];
        $todas = Coleta::listar($filtros, 'c.criado_em DESC');
        render('usuario/painel', ['titulo' => 'Início', 'coletas' => $todas]);
    }

    public function nova()
    {
        exigir(['usuario']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::criar(usuario()['id'], $_POST, $_FILES['foto'] ?? null);
                flash('Solicitação enviada! Agora ela aguarda a aprovação do administrador.');
                redirect('minhas_coletas');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        render('usuario/coleta_form', [
            'titulo' => 'Nova coleta', 'c' => $_POST, 'acao' => 'nova_coleta', 'id' => 0,
        ]);
    }

    public function editar()
    {
        exigir(['usuario']);
        $id = (int)($_GET['id'] ?? 0);
        $c = Coleta::buscar($id);
        if (!$c || $c['usuario_id'] != usuario()['id'] || $c['status'] != 'aguardando_aprovacao') {
            flash('Só é possível editar solicitações que aguardam aprovação.', 'danger');
            redirect('minhas_coletas');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::atualizar($id, usuario()['id'], $_POST, $_FILES['foto'] ?? null);
                flash('Solicitação atualizada.');
                redirect('minhas_coletas');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
                $c = array_merge($c, $_POST);
            }
        }
        render('usuario/coleta_form', [
            'titulo' => 'Editar coleta', 'c' => $c, 'acao' => 'editar_coleta&id=' . $id, 'id' => $id,
        ]);
    }

    public function excluir()
    {
        exigir(['usuario']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::excluir((int)($_POST['id'] ?? 0), usuario()['id']);
                flash('Solicitação excluída.');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        redirect('minhas_coletas');
    }

    // acompanhamento dos pedidos (sem os já concluídos)
    public function minhas()
    {
        exigir(['usuario']);
        $coletas = Coleta::listar(
            ['usuario_id' => usuario()['id'], 'status' => ['aguardando_aprovacao', 'aprovada', 'recusada', 'agendada', 'coletada']],
            'c.data_hora_coleta ASC'
        );
        render('usuario/minhas', ['titulo' => 'Minhas coletas', 'coletas' => $coletas]);
    }

    public function ver()
    {
        exigir(['usuario']);
        $c = Coleta::buscar((int)($_GET['id'] ?? 0));
        if (!$c || $c['usuario_id'] != usuario()['id']) {
            flash('Solicitação não encontrada.', 'danger');
            redirect('minhas_coletas');
        }
        render('usuario/ver', ['titulo' => 'Coleta #' . $c['id'], 'c' => $c, 'historico' => Coleta::historico($c['id'])]);
    }
}
