<?php
// Regras de negócio das coletas
class ColetaService
{
    // Confere os dados do formulário. Se algo estiver errado, lança um erro com a mensagem.
    private static function validar($d)
    {
        global $MATERIAIS;

        if (!in_array($d['tipo_material'] ?? '', $MATERIAIS)) {
            throw new Exception('Escolha o tipo de material.');
        }
        foreach (['logradouro', 'numero', 'bairro', 'cidade'] as $campo) {
            if (trim($d[$campo] ?? '') == '') {
                throw new Exception('Preencha o endereço completo (rua, número, bairro e cidade).');
            }
        }

        $quando = strtotime(str_replace('T', ' ', $d['data_hora_coleta'] ?? ''));
        if (!$quando) {
            throw new Exception('Escolha a data e o horário da coleta.');
        }
        // Regra: no mínimo 24 horas de antecedência
        if ($quando < time() + 24 * 3600) {
            throw new Exception('A coleta precisa ser agendada com no mínimo 24 horas de antecedência.');
        }

        return [
            'tipo_material'    => $d['tipo_material'],
            'logradouro'       => trim($d['logradouro']),
            'numero'           => trim($d['numero']),
            'complemento'      => trim($d['complemento'] ?? ''),
            'bairro'           => trim($d['bairro']),
            'cidade'           => trim($d['cidade']),
            'cep'              => trim($d['cep'] ?? ''),
            'latitude'         => is_numeric($d['latitude'] ?? '') ? $d['latitude'] : null,
            'longitude'        => is_numeric($d['longitude'] ?? '') ? $d['longitude'] : null,
            'data_hora_coleta' => date('Y-m-d H:i:s', $quando),
            'observacoes'      => trim($d['observacoes'] ?? ''),
        ];
    }

    // Salva a foto na pasta uploads e devolve o nome do arquivo
    private static function salvarFoto($arquivo, $obrigatoria)
    {
        if (!$arquivo || $arquivo['error'] == UPLOAD_ERR_NO_FILE) {
            if ($obrigatoria) {
                throw new Exception('Envie uma foto do material.');
            }
            return null;
        }
        if ($arquivo['error'] != UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar a foto. Tente novamente.');
        }
        if ($arquivo['size'] > 4 * 1024 * 1024) {
            throw new Exception('A foto deve ter no máximo 4 MB.');
        }

        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
            throw new Exception('A foto precisa ser JPG, PNG ou WebP.');
        }
        if (!getimagesize($arquivo['tmp_name'])) {
            throw new Exception('O arquivo enviado não é uma imagem.');
        }

        $nome = md5(uniqid('', true)) . '.' . $extensao;
        if (!move_uploaded_file($arquivo['tmp_name'], __DIR__ . '/../../uploads/' . $nome)) {
            throw new Exception('Não foi possível salvar a foto.');
        }
        return $nome;
    }

    // Muda o status e guarda no histórico (os dois juntos, em transação)
    private static function mudarStatus($id, $de, $para, $quem, $extra = [], $obs = null)
    {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            if (!Coleta::mudarStatus($id, $de, $para, $extra)) {
                throw new Exception('Essa solicitação mudou de situação. Atualize a página e tente de novo.');
            }
            Coleta::registrarHistorico($id, $de, $para, $quem, $obs);
            $pdo->commit();
        } catch (Exception $erro) {
            $pdo->rollBack();
            throw $erro;
        }
    }

    // ---------- Usuário ----------
    public static function criar($usuarioId, $dados, $arquivo)
    {
        $limpo = self::validar($dados);
        $limpo['foto'] = self::salvarFoto($arquivo, true);
        $limpo['usuario_id'] = $usuarioId;

        $id = Coleta::criar($limpo);
        Coleta::registrarHistorico($id, null, 'aguardando_aprovacao', $usuarioId);
        return $id;
    }

    // O usuário só pode editar ou excluir enquanto estiver aguardando aprovação
    private static function buscarEditavel($id, $usuarioId)
    {
        $c = Coleta::buscar($id);
        if (!$c || $c['usuario_id'] != $usuarioId) {
            throw new Exception('Solicitação não encontrada.');
        }
        if ($c['status'] != 'aguardando_aprovacao') {
            throw new Exception('Só é possível alterar solicitações que ainda aguardam aprovação.');
        }
        return $c;
    }

    public static function atualizar($id, $usuarioId, $dados, $arquivo)
    {
        $antiga = self::buscarEditavel($id, $usuarioId);
        $limpo = self::validar($dados);
        $novaFoto = self::salvarFoto($arquivo, false);
        $limpo['foto'] = $novaFoto ? $novaFoto : $antiga['foto'];
        Coleta::atualizar($id, $limpo);
    }

    public static function excluir($id, $usuarioId)
    {
        $c = self::buscarEditavel($id, $usuarioId);
        Coleta::excluir($id);
        @unlink(__DIR__ . '/../../uploads/' . $c['foto']);
    }

    // ---------- Administrador ----------
    public static function aprovar($id, $adminId)
    {
        self::mudarStatus($id, 'aguardando_aprovacao', 'aprovada', $adminId);
    }

    public static function recusar($id, $adminId, $motivo)
    {
        if (trim($motivo) == '') {
            throw new Exception('Informe o motivo da recusa.');
        }
        self::mudarStatus($id, 'aguardando_aprovacao', 'recusada', $adminId, ['motivo_recusa' => trim($motivo)], $motivo);
    }

    // ---------- Funcionário ----------
    public static function reservar($id, $funcionarioId)
    {
        self::mudarStatus($id, 'aprovada', 'agendada', $funcionarioId, ['funcionario_id' => $funcionarioId]);
    }

    private static function buscarDoFuncionario($id, $funcionarioId)
    {
        $c = Coleta::buscar($id);
        if (!$c || $c['funcionario_id'] != $funcionarioId) {
            throw new Exception('Essa coleta não está reservada para você.');
        }
        return $c;
    }

    public static function marcarColetada($id, $funcionarioId)
    {
        self::buscarDoFuncionario($id, $funcionarioId);
        self::mudarStatus($id, 'agendada', 'coletada', $funcionarioId);
    }

    public static function registrarRecebimento($id, $funcionarioId, $material, $peso)
    {
        self::buscarDoFuncionario($id, $funcionarioId);
        if (trim($material) == '') {
            throw new Exception('Informe o material que foi recebido.');
        }
        if ($peso != '' && (!is_numeric($peso) || $peso < 0)) {
            throw new Exception('O peso precisa ser um número válido.');
        }
        $extra = ['material_recebido' => trim($material), 'peso_kg' => $peso == '' ? null : $peso];
        self::mudarStatus($id, 'coletada', 'concluida', $funcionarioId, $extra);
    }
}
