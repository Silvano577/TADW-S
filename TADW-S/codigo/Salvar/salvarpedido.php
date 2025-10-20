<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) header("Location: ../login.php");

$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'];

// Coleta dados do form
$idpedido = intval($_POST['idpedido'] ?? 0);
$idpagamento = intval($_POST['idpagamento'] ?? 0);
$valortotal = floatval($_POST['valortotal'] ?? 0);
$identrega = intval($_POST['entrega'] ?? 0);

if (!$idpedido || !$idpagamento || !$valortotal || !$identrega) die("Dados inválidos.");

// Verifica se o pedido pertence ao cliente e está pendente
$sql = "SELECT status FROM pedido WHERE idpedido = ? AND idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idpedido, $idcliente);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($res);
if (!$pedido) die("Pedido não encontrado ou não é seu.");
if ($pedido['status'] !== 'pendente') die("Só é possível editar pedidos pendentes.");

// Atualiza endereço e valor
$sql = "UPDATE pedido SET identrega = ?, valortotal = ? WHERE idpedido = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "idi", $identrega, $valortotal, $idpedido);
mysqli_stmt_execute($stmt);

header("Location: meuspedidos.php?msg=pedido_atualizado");
exit;
