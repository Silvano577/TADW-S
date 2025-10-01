<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

$taxaEntrega = 5.00; // ou buscar do banco

$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$endentrega = isset($_POST['endentrega']) ? intval($_POST['endentrega']) : 0;
$pagamento_id = isset($_POST['idpagamento']) ? intval($_POST['idpagamento']) : 0;
$idfeedback = null;

$idprodutos = $_POST['idproduto'] ?? [];
$quantidades = $_POST['quantidade'] ?? [];

if ($cliente <= 0 || $endentrega <= 0 || empty($idprodutos)) {
    die("Dados inválidos. Verifique cliente, endereço ou produtos selecionados.");
}

mysqli_begin_transaction($conexao);

try {
    // 1) calcular valor total
    $valortotal = 0;
    $itensParaInserir = [];

    foreach ($idprodutos as $idproduto) {
        $idproduto = intval($idproduto);
        $qtd = intval($quantidades[$idproduto] ?? 1);

        $sql = "SELECT preco FROM produto WHERE idproduto = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idproduto);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $produto = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$produto) throw new Exception("Produto {$idproduto} não existe.");

        $subtotal = $produto['preco'] * $qtd;
        $valortotal += $subtotal;

        $itensParaInserir[] = [
            'idproduto' => $idproduto,
            'quantidade' => $qtd,
            'preco_unit' => $produto['preco']
        ];
    }

    // 2) total com taxa
    $valortotal += $taxaEntrega;

    // 3) criar pagamento se necessário
    if ($pagamento_id <= 0) {
        $pagamento_id = registrar_pagamento($conexao, $cliente, 'pendente', $valortotal);
        if (!$pagamento_id) throw new Exception("Erro ao criar pagamento.");
    }

    // 4) criar pedido
    $idpedido = salvarPedido($conexao, $endentrega, $cliente, $pagamento_id, $valortotal, $idfeedback);
    if (!$idpedido) throw new Exception("Erro ao criar pedido.");

    // 5) inserir itens no pedido_produto
    $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade, preco_unit) VALUES (?, ?, ?, ?)";
    $stmtItem = mysqli_prepare($conexao, $sqlItem);

    foreach ($itensParaInserir as $it) {
        mysqli_stmt_bind_param($stmtItem, "iiid", $idpedido, $it['idproduto'], $it['quantidade'], $it['preco_unit']);
        if (!mysqli_stmt_execute($stmtItem)) throw new Exception("Erro ao inserir item do pedido.");
    }
    mysqli_stmt_close($stmtItem);

    // 6) criar delivery
    $iddelivery = criar_delivery($conexao, $idpedido);
    if (!$iddelivery) throw new Exception("Erro ao criar delivery.");

    mysqli_commit($conexao);
    unset($_SESSION['cart']);

    // 7) redirecionamento
    header("Location: ../Listar/listarpedido.php?id={$idpedido}");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexao);
    echo "Erro: " . $e->getMessage();
    exit;
}
