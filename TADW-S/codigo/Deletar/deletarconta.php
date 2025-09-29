<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// 1) Buscar cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$cliente = $cliente[0] ?? null;

if ($cliente) {
    $cliente_id = $cliente['idcliente'];

    // 2) Deletar todos os endereços do cliente
    $enderecos = listar_enderecos_por_cliente($conexao, $cliente_id);
    foreach ($enderecos as $endereco) {
        deletar_endereco($conexao, $endereco['idendentrega']);
    }

    // 3) Apagar foto do cliente, se existir
    if (!empty($cliente['foto'])) {
        $arquivo = "/var/www/html/fotos/" . basename($cliente['foto']);
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }
    }

    // 4) Deletar registro do cliente
    deletar_cliente($conexao, $cliente_id);
}

// 5) Deletar usuário
deletar_usuario($conexao, $usuario_id);

// 6) Destruir sessão e redirecionar
session_unset();
session_destroy();

header("Location: login.php?conta_deletada=1");
exit;
