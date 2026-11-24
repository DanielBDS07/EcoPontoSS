<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

require __DIR__ . '/config/database.php';
require __DIR__ . '/app/helpers.php';
require __DIR__ . '/app/models/Usuario.php';
require __DIR__ . '/app/models/Coleta.php';
require __DIR__ . '/app/services/ColetaService.php';
require __DIR__ . '/app/controllers/PaginaController.php';
require __DIR__ . '/app/controllers/AuthController.php';
require __DIR__ . '/app/controllers/UsuarioController.php';
require __DIR__ . '/app/controllers/FuncionarioController.php';
require __DIR__ . '/app/controllers/AdminController.php';


$rotas = [
    //páginas base
    'home'        => ['PaginaController', 'mostrar'],
    'separacao'   => ['PaginaController', 'mostrar'],

    //login e cadastro
    'login'                => ['AuthController', 'login'],
    'cadastro'             => ['AuthController', 'cadastro'],
    'cadastro_funcionario' => ['AuthController', 'cadastroFuncionario'],
    'logout'               => ['AuthController', 'logout'],

    //usuário
    'painel'         => ['UsuarioController', 'painel'],
    'nova_coleta'    => ['UsuarioController', 'nova'],
    'editar_coleta'  => ['UsuarioController', 'editar'],
    'excluir_coleta' => ['UsuarioController', 'excluir'],
    'minhas_coletas' => ['UsuarioController', 'minhas'],
    'ver_coleta'     => ['UsuarioController', 'ver'],

    //funcionário
    'func_disponiveis' => ['FuncionarioController', 'disponiveis'],
    'func_ver'         => ['FuncionarioController', 'ver'],
    'func_reservar'    => ['FuncionarioController', 'reservar'],
    'func_reservadas'  => ['FuncionarioController', 'reservadas'],
    'func_coletar'     => ['FuncionarioController', 'coletar'],
    'func_receber'     => ['FuncionarioController', 'receber'],
    'func_historico'   => ['FuncionarioController', 'historico'],

    //administrador
    'admin_painel'        => ['AdminController', 'painel'],
    'admin_coletas'       => ['AdminController', 'coletas'],
    'admin_coleta_ver'    => ['AdminController', 'coletaVer'],
    'admin_aprovar'       => ['AdminController', 'aprovar'],
    'admin_recusar'       => ['AdminController', 'recusar'],
    'admin_funcionarios'  => ['AdminController', 'funcionarios'],
    'admin_func_status'   => ['AdminController', 'funcionarioStatus'],
    'admin_usuarios'      => ['AdminController', 'usuarios'],
];

$rota = $_GET['rota'] ?? 'home';
if (!isset($rotas[$rota])) {
    http_response_code(404);
    exit('Página não encontrada.');
}

$classe = $rotas[$rota][0];
$metodo = $rotas[$rota][1];
$controller = new $classe();
$controller->$metodo();
