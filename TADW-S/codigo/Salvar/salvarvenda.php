<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

// Taxa de entrega fixa
$taxaEntrega = 5.00;

// Dados do formulário
$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$endentrega = isset($_POST['endentrega']) ? intval($_POST['endentrega']) : 0;
$idprodutos = $_POST['idproduto'] ?? [];
$quantidades = $_POST['quantidade'] ?? [];
$idpedido = isset($_POST['idpedido']) ? intval($_POST['idpedido']) : 0;
$idfeedback = null;

// Validações básicas
if ($cliente <= 0 || $endentrega <= 0 || empty($idprodutos)) {
    die("Dados inválidos. Verifique cliente, endereço ou produtos selecionados.");
}

// Inicia transação
mysqli_begin_transaction($conexao);

try {
    // 1) Calcular valor total do pedido
    $valortotal = 0;
    foreach ($idprodutos as $pid) {
        $pid = intval($pid);
        $qtd = intval($quantidades[$pid] ?? 1);

        $sql = "SELECT preco FROM produto WHERE idproduto = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $pid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $produto = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$produto) throw new Exception("Produto {$pid} não existe.");

        $valortotal += $produto['preco'] * $qtd;
    }

    // 2) Adiciona taxa de entrega
    $valortotal += $taxaEntrega;

    // 3) Cria pagamento (pendente)
    $idpagamento = registrar_pagamento($conexao, $cliente, 'pendente', $valortotal);
    if (!$idpagamento) throw new Exception("Erro ao criar pagamento.");

    // 4) Cria pedido
    $idpedido = salvarpedido($conexao, $cliente, $endentrega, $idpagamento, $valortotal, 'pendente');
    if (!$idpedido) throw new Exception("Erro ao criar pedido.");

    // 5) Inserir itens no pedido
    $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade) VALUES (?, ?, ?)";
    $stmtItem = mysqli_prepare($conexao, $sqlItem);

    foreach ($idprodutos as $pid) {
        $pid = intval($pid);
        $qtd = intval($quantidades[$pid] ?? 1);
        mysqli_stmt_bind_param($stmtItem, "iii", $idpedido, $pid, $qtd);
        if (!mysqli_stmt_execute($stmtItem)) throw new Exception("Erro ao inserir item do pedido.");
    }
    mysqli_stmt_close($stmtItem);

    // 6) Criar delivery
    $iddelivery = criar_delivery($conexao, $idpedido);
    if (!$iddelivery) throw new Exception("Erro ao criar delivery.");

    // 7) Commit e limpar carrinho
    mysqli_commit($conexao);
    unset($_SESSION['cart']);

    // 8) Redireciona para lista de pedidos ou página de sucesso
    header("Location: ../Listar/listarpedido.php?id={$idpedido}");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexao);
    echo "Erro: " . $e->getMessage();
    exit;
}
?>
