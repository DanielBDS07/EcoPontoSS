<?php
// Funções auxiliares usadas em todo o site

$MATERIAIS = ['Plástico', 'Papel', 'Papelão', 'Vidro', 'Metal', 'Óleo de cozinha', 'Eletrônicos', 'Outros'];

// status => [texto, cor do Bootstrap]
$STATUS = [
    'aguardando_aprovacao' => ['Aguardando aprovação', 'warning text-dark'],
    'aprovada'             => ['Aprovada', 'info text-dark'],
    'recusada'             => ['Recusada', 'danger'],
    'agendada'             => ['Agendada', 'primary'],
    'coletada'             => ['Coletada', 'success'],
    'concluida'            => ['Concluída', 'secondary'],
];

function e($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// valor de um campo de array, já protegido (usado para preencher formulários)
function v($dados, $campo)
{
    return e($dados[$campo] ?? '');
}

function redirect($rota, $extra = '')
{
    header('Location: index.php?rota=' . $rota . $extra);
    exit;
}

function flash($texto, $tipo = 'success')
{
    $_SESSION['msg'] = ['texto' => $texto, 'tipo' => $tipo];
}

function usuario()
{
    return $_SESSION['usuario'] ?? null;
}

function paginaInicial($perfil)
{
    if ($perfil == 'admin') return 'admin_painel';
    if ($perfil == 'funcionario') return 'func_disponiveis';
    return 'painel';
}

// Garante que a pessoa está logada e tem o perfil certo
function exigir($perfis = [])
{
    $u = usuario();
    if (!$u) {
        flash('Entre na sua conta para continuar.', 'warning');
        redirect('login');
    }

    // funcionário só acessa se o admin aprovou o cadastro
    if ($u['perfil'] == 'funcionario') {
        $atual = Usuario::buscar($u['id']);
        if (!$atual || $atual['status_cadastro'] != 'aprovado') {
            unset($_SESSION['usuario']);
            flash('Seu cadastro de entregador não está aprovado.', 'danger');
            redirect('login');
        }
    }

    if ($perfis && !in_array($u['perfil'], $perfis)) {
        flash('Você não tem permissão para acessar essa página.', 'danger');
        redirect(paginaInicial($u['perfil']));
    }
}

function render($view, $dados = [])
{
    extract($dados);
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/' . $view . '.php';
    require __DIR__ . '/views/layout/footer.php';
}

function dataHoraBr($data)
{
    if (!$data) return '-';
    return date('d/m/Y H:i', strtotime($data));
}

function statusBadge($status)
{
    global $STATUS;
    $info = $STATUS[$status] ?? [$status, 'secondary'];
    return '<span class="badge bg-' . $info[1] . '">' . e($info[0]) . '</span>';
}

function enderecoCompleto($c)
{
    $txt = $c['logradouro'] . ', ' . $c['numero'];
    if (!empty($c['complemento'])) {
        $txt .= ' - ' . $c['complemento'];
    }
    return $txt . ' - ' . $c['bairro'] . ', ' . $c['cidade'];
}

