<?php
class Usuario
{
    public static function buscar($id)
    {
        $st = db()->prepare('SELECT * FROM usuario WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function buscarPorEmail($email)
    {
        $st = db()->prepare('SELECT * FROM usuario WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch() ?: null;
    }

    public static function criar($d)
    {
        $st = db()->prepare(
            'INSERT INTO usuario (nome, email, telefone, senha_hash, perfil, status_cadastro, veiculo, apresentacao)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            $d['nome'], $d['email'], $d['telefone'], password_hash($d['senha'], PASSWORD_DEFAULT),
            $d['perfil'], $d['status_cadastro'], $d['veiculo'] ?? null, $d['apresentacao'] ?? null,
        ]);
    }

    public static function listar($perfil)
    {
        $st = db()->prepare('SELECT * FROM usuario WHERE perfil = ? ORDER BY criado_em DESC');
        $st->execute([$perfil]);
        return $st->fetchAll();
    }

    public static function definirStatus($id, $status)
    {
        $st = db()->prepare('UPDATE usuario SET status_cadastro = ? WHERE id = ?');
        $st->execute([$status, $id]);
    }

    public static function contar($perfil, $status = null)
    {
        if ($status) {
            $st = db()->prepare('SELECT COUNT(*) FROM usuario WHERE perfil = ? AND status_cadastro = ?');
            $st->execute([$perfil, $status]);
        } else {
            $st = db()->prepare('SELECT COUNT(*) FROM usuario WHERE perfil = ?');
            $st->execute([$perfil]);
        }
        return (int)$st->fetchColumn();
    }
}
