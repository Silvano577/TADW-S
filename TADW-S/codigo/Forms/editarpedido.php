<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

$usuario_id = $_SESSION['idusuario'] ?? 0;
if (!$usuario_id) header("Location: ../login.php");

$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'];

$idpedido = intval($_GET['id'] ?? 0);
if ($idpedido <= 0) die("Pedido inválido.");

// 🔹 Buscar pedido do cliente
$sql = "SELECT * FROM pedido WHERE idpedido = ? AND idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idpedido, $idcliente);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($res);
if (!$pedido) die("Pedido não encontrado ou você não tem permissão para editar.");

// 🔹 Só permite editar se estiver pendente
if ($pedido['status'] !== 'pendente') die("Só é possível editar pedidos pendentes.");

// Aqui você pode criar um formulário parecido com o `formpedido.php` para editar endereço ou produtos do pedido
// Por exemplo, redirecionar para `formpedidoeditar.php?id=...`
header("Location: formpedidoeditar.php?id=$idpedido");
exit;
