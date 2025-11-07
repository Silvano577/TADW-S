<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";


if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;


$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$cliente = $cliente[0] ?? null;

if ($cliente) {
    $cliente_id = $cliente['idcliente'];

    $enderecos = listar_enderecos_por_cliente($conexao, $cliente_id);
    foreach ($enderecos as $endereco) {
        deletar_endereco($conexao, $endereco['idendentrega']);
    }

    if (!empty($cliente['foto'])) {
        $arquivo = "/var/www/html/fotos/" . basename($cliente['foto']);
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }
    }

    deletar_cliente($conexao, $cliente_id);
}

deletar_usuario($conexao, $usuario_id);

session_unset();
session_destroy();

header("Location: login.php?conta_deletada=1");
exit;
