<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Recebe o id do endereço via GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca o cliente vinculado ao usuário logado
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$cliente = $cliente[0] ?? null;

if (!$cliente) {
    // Usuário não tem cliente vinculado
    header("Location: ../index.php");
    exit;
}

// Verifica se o endereço pertence ao cliente
$endereco = buscar_endereco($conexao, $id);
if (!$endereco || $endereco['cliente'] != $cliente['idcliente']) {
    // Endereço não pertence ao cliente
    header("Location: ../perfil.php");
    exit;
}

// Deleta o endereço
deletar_endereco($conexao, $id);

// Redireciona para a página de perfil do cliente
header("Location: ../perfil.php");
exit;
