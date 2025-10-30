<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// 🔹 Verifica se o usuário está logado
if (!isset($_SESSION['idusuario'])) {
    echo "Você precisa estar logado para fazer um pedido.";
    exit;
}

// 🔹 Busca o cliente pelo usuário logado
$usuario_id = $_SESSION['idusuario'];
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if ($idcliente == 0) {
    die("Cliente não encontrado.");
}

// 🔹 Buscar produtos do carrinho do cliente
$sql_carrinho = "SELECT c.idproduto, c.quantidade, p.preco 
                 FROM carrinho c
                 JOIN produto p ON c.idproduto = p.idproduto
                 WHERE c.idcliente = ?";
$stmt_carrinho = mysqli_prepare($conexao, $sql_carrinho);
mysqli_stmt_bind_param($stmt_carrinho, "i", $idcliente);
mysqli_stmt_execute($stmt_carrinho);
$result_carrinho = mysqli_stmt_get_result($stmt_carrinho);

if (mysqli_num_rows($result_carrinho) == 0) {
    die("Seu carrinho está vazio. Adicione produtos antes de fazer o pedido.");
}

// 🔹 Calcula o valor total do pedido
$valor_total = 0;
$produtos = [];
while ($item = mysqli_fetch_assoc($result_carrinho)) {
    $produtos[] = $item;
    $valor_total += $item['preco'] * $item['quantidade'];
}

// 🔹 Taxa de entrega fixa
$taxa_entrega = 15.00;
$valor_total += $taxa_entrega;

// 🔹 Receber o endereço de entrega selecionado no form
if (!isset($_POST['endentrega'])) {
    die("Selecione um endereço de entrega.");
}
$identrega = intval($_POST['endentrega']);

// 🔹 Criar o pedido
$sql_pedido = "INSERT INTO pedido (idcliente, valortotal, identrega, data_pedido, status)
               VALUES (?, ?, ?, NOW(), 'pendente')";
$stmt_pedido = mysqli_prepare($conexao, $sql_pedido);
mysqli_stmt_bind_param($stmt_pedido, "idi", $idcliente, $valor_total, $identrega);
mysqli_stmt_execute($stmt_pedido);

// 🔹 Recupera o ID do pedido recém-criado
$idpedido = mysqli_insert_id($conexao);

// 🔹 Adiciona os produtos do carrinho no pedido
$sql_produto = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade, preco_unit)
                VALUES (?, ?, ?, ?)";
$stmt_produto = mysqli_prepare($conexao, $sql_produto);

foreach ($produtos as $p) {
    mysqli_stmt_bind_param($stmt_produto, "iiid", $idpedido, $p['idproduto'], $p['quantidade'], $p['preco']);
    mysqli_stmt_execute($stmt_produto);
}

// 🔹 Limpa o carrinho do cliente
$sql_limpar = "DELETE FROM carrinho WHERE idcliente = ?";
$stmt_limpar = mysqli_prepare($conexao, $sql_limpar);
mysqli_stmt_bind_param($stmt_limpar, "i", $idcliente);
mysqli_stmt_execute($stmt_limpar);

// 🔹 Redireciona para o pagamento
header("Location: ../Forms/formpagamento.php?idpedido=" . $idpedido . "&valor_total=" . $valor_total);
exit;
?>
