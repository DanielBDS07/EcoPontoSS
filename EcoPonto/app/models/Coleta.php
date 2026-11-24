<?php
class Coleta
{
    const SELECT = 'SELECT c.*, u.nome AS solicitante, u.telefone AS solicitante_telefone,
                           f.nome AS funcionario, f.telefone AS funcionario_telefone
                    FROM coleta c
                    JOIN usuario u ON u.id = c.usuario_id
                    LEFT JOIN usuario f ON f.id = c.funcionario_id';

    public static function buscar($id)
    {
        $st = db()->prepare(self::SELECT . ' WHERE c.id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }


    public static function listar($filtros = [], $ordem = 'c.data_hora_coleta ASC')
    {
        $where = [];
        $params = [];

        if (isset($filtros['usuario_id'])) {
            $where[] = 'c.usuario_id = ?';
            $params[] = $filtros['usuario_id'];
        }
        if (isset($filtros['funcionario_id'])) {
            $where[] = 'c.funcionario_id = ?';
            $params[] = $filtros['funcionario_id'];
        }
        if (!empty($filtros['status'])) {
            $marcas = implode(',', array_fill(0, count($filtros['status']), '?'));
            $where[] = 'c.status IN (' . $marcas . ')';
            foreach ($filtros['status'] as $s) {
                $params[] = $s;
            }
        }

        $sql = self::SELECT;
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY ' . $ordem;

        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function criar($d)
    {
        $st = db()->prepare(
            'INSERT INTO coleta (usuario_id, tipo_material, logradouro, numero, complemento, bairro, cidade, cep,
                                 latitude, longitude, data_hora_coleta, foto, observacoes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            $d['usuario_id'], $d['tipo_material'], $d['logradouro'], $d['numero'], $d['complemento'],
            $d['bairro'], $d['cidade'], $d['cep'], $d['latitude'], $d['longitude'],
            $d['data_hora_coleta'], $d['foto'], $d['observacoes'],
        ]);
        return (int)db()->lastInsertId();
    }

    public static function atualizar($id, $d)
    {
        $st = db()->prepare(
            'UPDATE coleta SET tipo_material = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?,
                    cep = ?, latitude = ?, longitude = ?, data_hora_coleta = ?, foto = ?, observacoes = ?
             WHERE id = ?'
        );
        $st->execute([
            $d['tipo_material'], $d['logradouro'], $d['numero'], $d['complemento'], $d['bairro'], $d['cidade'],
            $d['cep'], $d['latitude'], $d['longitude'], $d['data_hora_coleta'], $d['foto'], $d['observacoes'], $id,
        ]);
    }

    public static function excluir($id)
    {
        $st = db()->prepare('DELETE FROM coleta WHERE id = ?');
        $st->execute([$id]);
    }

    public static function mudarStatus($id, $de, $para, $extra = [])
    {
        $sql = 'UPDATE coleta SET status = ?';
        $params = [$para];
        foreach ($extra as $coluna => $valor) {
            $sql .= ', ' . $coluna . ' = ?';
            $params[] = $valor;
        }
        $sql .= ' WHERE id = ? AND status = ?';
        $params[] = $id;
        $params[] = $de;

        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->rowCount() == 1;
    }

    public static function registrarHistorico($id, $de, $para, $porUsuario, $obs = null)
    {
        $st = db()->prepare(
            'INSERT INTO historico_status (coleta_id, status_anterior, status_novo, alterado_por, observacao)
             VALUES (?, ?, ?, ?, ?)'
        );
        $st->execute([$id, $de, $para, $porUsuario, $obs]);
    }

    public static function historico($id)
    {
        $st = db()->prepare(
            'SELECT h.*, u.nome AS responsavel FROM historico_status h
             JOIN usuario u ON u.id = h.alterado_por
             WHERE h.coleta_id = ? ORDER BY h.id'
        );
        $st->execute([$id]);
        return $st->fetchAll();
    }

    public static function contar($status)
    {
        $marcas = implode(',', array_fill(0, count($status), '?'));
        $st = db()->prepare('SELECT COUNT(*) FROM coleta WHERE status IN (' . $marcas . ')');
        $st->execute($status);
        return (int)$st->fetchColumn();
    }
}
