<?php
class FuncionarioController
{
    public function disponiveis()
    {
        exigir(['funcionario']);
        $coletas = Coleta::listar(['status' => ['aprovada']], 'c.data_hora_coleta ASC');
        render('funcionario/disponiveis', ['titulo' => 'Coletas disponíveis', 'coletas' => $coletas]);
    }

    public function ver()
    {
        exigir(['funcionario']);
        $c = Coleta::buscar((int)($_GET['id'] ?? 0));
        // pode ver se está disponível ou se foi reservada por ele
        if (!$c || ($c['status'] != 'aprovada' && $c['funcionario_id'] != usuario()['id'])) {
            flash('Coleta não encontrada.', 'danger');
            redirect('func_disponiveis');
        }
        render('funcionario/ver', ['titulo' => 'Coleta #' . $c['id'], 'c' => $c, 'historico' => Coleta::historico($c['id'])]);
    }

    public function reservar()
    {
        exigir(['funcionario']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::reservar((int)($_POST['id'] ?? 0), usuario()['id']);
                flash('Coleta reservada! Ela está na sua lista de reservadas.');
                redirect('func_reservadas');
            } catch (Exception $erro) {
                flash('Não foi possível reservar: outro entregador pode ter reservado antes de você.', 'danger');
            }
        }
        redirect('func_disponiveis');
    }

    public function reservadas()
    {
        exigir(['funcionario']);
        $coletas = Coleta::listar(
            ['funcionario_id' => usuario()['id'], 'status' => ['agendada', 'coletada']],
            'c.data_hora_coleta ASC'
        );
        render('funcionario/reservadas', ['titulo' => 'Coletas reservadas', 'coletas' => $coletas]);
    }

    public function coletar()
    {
        exigir(['funcionario']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::marcarColetada((int)($_POST['id'] ?? 0), usuario()['id']);
                flash('Coleta marcada como realizada. Agora registre o recebimento do material.');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        redirect('func_reservadas');
    }

    public function receber()
    {
        exigir(['funcionario']);
        $id = (int)($_GET['id'] ?? 0);
        $c = Coleta::buscar($id);
        if (!$c || $c['funcionario_id'] != usuario()['id'] || $c['status'] != 'coletada') {
            flash('Essa coleta não está pronta para registrar o recebimento.', 'danger');
            redirect('func_reservadas');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                ColetaService::registrarRecebimento($id, usuario()['id'], $_POST['material_recebido'] ?? '', $_POST['peso_kg'] ?? '');
                flash('Recebimento registrado. Coleta concluída!');
                redirect('func_historico');
            } catch (Exception $erro) {
                flash($erro->getMessage(), 'danger');
            }
        }
        render('funcionario/receber', ['titulo' => 'Registrar recebimento', 'c' => $c, 'f' => $_POST]);
    }

    public function historico()
    {
        exigir(['funcionario']);
        $coletas = Coleta::listar(
            ['funcionario_id' => usuario()['id'], 'status' => ['concluida']],
            'c.atualizado_em DESC'
        );
        render('funcionario/historico', ['titulo' => 'Histórico de coletas', 'coletas' => $coletas]);
    }
}
