<?php
// Páginas informativas (qualquer pessoa pode ver)
class PaginaController
{
    public function mostrar()
    {
        $rota = $_GET['rota'] ?? 'home';
        $titulos = [
            'home'      => 'Início',
            'separacao' => 'Como separar o lixo',
        ];
        render('paginas/' . $rota, ['titulo' => $titulos[$rota]]);
    }
}
