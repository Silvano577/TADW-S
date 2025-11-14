<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário não logado"]);
    exit;
}

// Obtém cliente logado
$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    echo json_encode(["sucesso" => false, "mensagem" => "Cliente não encontrado"]);
    exit;
}

// Recebe dados do AJAX
$idcarrinho = intval($_POST['idcarrinho'] ?? 0);
$acao = $_POST['acao'] ?? '';

if ($idcarrinho <= 0 || !in_array($acao, ['mais', 'menos'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos"]);
    exit;
}

// Busca item do carrinho garantindo que pertença ao cliente
$sql = "SELECT carrinho.quantidade, produto.preco 
        FROM carrinho 
        INNER JOIN produto ON carrinho.idproduto = produto.idproduto 
        WHERE idcarrinho = ? AND idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idcarrinho, $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo json_encode(["sucesso" => false, "mensagem" => "Item não encontrado"]);
    exit;
}

$quantidade = $item['quantidade'];
$preco = $item['preco'];

$quantidade = ($acao === 'mais') ? $quantidade + 1 : $quantidade - 1;

// Se quantidade <= 0, remove o item
if ($quantidade <= 0) {
    $sql_del = "DELETE FROM carrinho WHERE idcarrinho = ? AND idcliente = ?";
    $stmt_del = mysqli_prepare($conexao, $sql_del);
    mysqli_stmt_bind_param($stmt_del, "ii", $idcarrinho, $idcliente);
    mysqli_stmt_execute($stmt_del);

    echo json_encode(["sucesso" => true, "quantidade" => 0, "subtotal" => 0]);
    exit;
}

// Atualiza quantidade
$sql_update = "UPDATE carrinho SET quantidade = ? WHERE idcarrinho = ? AND idcliente = ?";
$stmt_update = mysqli_prepare($conexao, $sql_update);
mysqli_stmt_bind_param($stmt_update, "iii", $quantidade, $idcarrinho, $idcliente);

if (mysqli_stmt_execute($stmt_update)) {
    echo json_encode([
        "sucesso" => true,
        "quantidade" => $quantidade,
        "subtotal" => $quantidade * $preco
    ]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => mysqli_stmt_error($stmt_update)]);
}
