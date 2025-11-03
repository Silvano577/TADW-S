<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["total" => 0]);
    exit;
}

$usuario_id = $_SESSION['idusuario'];
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

if (!$cliente) {
    echo json_encode(["total" => 0]);
    exit;
}

$idcliente = $cliente['idcliente'];
$total = contar_itens_carrinho($conexao, $idcliente);

echo json_encode(["total" => $total]);
