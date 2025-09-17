<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

// Config
$taxaEntrega = 5.00; // ou busque do banco

// Dados vindos do form (ou do processo de pagamento)
$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$endentrega = isset($_POST['endentrega']) ? intval($_POST['endentrega']) : 0;
$pagamento_id = isset($_POST['pagamento_id']) ? intval($_POST['pagamento_id']) : 0; // já criado ou 0
$idfeedback = null; // opcional

// Pega carrinho da session (exemplo)
$cart = $_SESSION['cart'] ?? []; // cada item: ['produto_id'=>X,'qtd'=>Y]

// validações básicas
if ($cliente <= 0 || $endentrega <= 0 || empty($cart)) {
    die("Dados inválidos.");
}

// começa transação
mysqli_begin_transaction($conexao);

try {
    // 1) calcular valor dos itens (buscar preço do produto)
    $valorPedido = 0.0;
    $itensParaInserir = []; // armazenar para inserir depois
    $sqlProduto = "SELECT idproduto, nome, preco FROM produto WHERE idproduto = ?";
    $stmtProduto = mysqli_prepare($conexao, $sqlProduto);

    foreach ($cart as $item) {
        $pid = intval($item['produto_id']);
        $qtd = intval($item['qtd']);

        mysqli_stmt_bind_param($stmtProduto, "i", $pid);
        mysqli_stmt_execute($stmtProduto);
        $res = mysqli_stmt_get_result($stmtProduto);
        $prod = mysqli_fetch_assoc($res);
        if (!$prod) throw new Exception("Produto {$pid} não existe.");

        $subtotal = floatval($prod['preco']) * $qtd;
        $valorPedido += $subtotal;

        $itensParaInserir[] = [
            'idproduto' => $pid,
            'qtd' => $qtd,
            'preco_unit' => floatval($prod['preco'])
        ];
    }
    mysqli_stmt_close($stmtProduto);

    // 2) calcular total com taxa
    $valortotal = $valorPedido + $taxaEntrega;

    // 3) se pagamento ainda não existe, registre (opcional)
    // se $pagamento_id == 0 -> cria pagamento e pega id (ex: pagamento pendente)
    if ($pagamento_id <= 0) {
        // função registrar_pagamento fictícia: crie conforme sua tabela pagamento
        $pagamento_id = registrar_pagamento($conexao, $cliente, 'pendente', $valortotal);
        if (!$pagamento_id) throw new Exception("Erro ao criar pagamento.");
    }

    // 4) inserir pedido e pegar id
    // usamos a função salvarPedido que você já tem:
    $idpedido = salvarPedido($conexao, $endentrega, $cliente, $pagamento_id, $valortotal, $idfeedback);
    if (!$idpedido) throw new Exception("Erro ao criar pedido.");

    // 5) inserir itens em pedido_produto (supondo tabela pedido_produto(idpedido,idproduto,quantidade,preco_unit))
    $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade, preco_unit) VALUES (?, ?, ?, ?)";
    $stmtItem = mysqli_prepare($conexao, $sqlItem);

    foreach ($itensParaInserir as $it) {
        mysqli_stmt_bind_param($stmtItem, "iiid", $idpedido, $it['idproduto'], $it['qtd'], $it['preco_unit']);
        if (!mysqli_stmt_execute($stmtItem)) {
            throw new Exception("Erro ao inserir item do pedido.");
        }
    }
    mysqli_stmt_close($stmtItem);

    // 6) criar delivery para o pedido
    $iddelivery = criar_delivery($conexao, $idpedido); // função que insere delivery
    if (!$iddelivery) throw new Exception("Erro ao criar delivery.");

    // 7) commit e limpar carrinho
    mysqli_commit($conexao);
    unset($_SESSION['cart']);

    // 8) redireciona para a listagem do pedido (pode passar id do pedido na query)
    header("Location: ../Listar/listarpedido.php?id={$idpedido}");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexao);
    // log de erro aqui se quiser
    echo "Erro: " . $e->getMessage();
    exit;
}
