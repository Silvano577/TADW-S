<?php
require_once "../conexao.php";
require_once "../funcao.php";
session_start();

// taxa de entrega (pode ser variável, buscar no BD se quiser)
$taxaEntrega = 5.00;

$cliente = isset($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$endentrega = isset($_POST['endentrega']) ? intval($_POST['endentrega']) : 0;
$pagamento_id = isset($_POST['idpagamento']) ? intval($_POST['idpagamento']) : 0;
$idfeedback = null;

$idprodutos = $_POST['idproduto'] ?? [];
$quantidades = $_POST['quantidade'] ?? [];

if ($cliente <= 0 || $endentrega <= 0 || empty($idprodutos) || !is_array($idprodutos)) {
    die("Dados inválidos. Verifique cliente, endereço ou produtos selecionados.");
}

// Iniciar transação
mysqli_begin_transaction($conexao);

try {
    // 1) calcular valor total e preparar itens
    $valortotal = 0.0;
    $itensParaInserir = [];

    $sqlSelect = "SELECT preco FROM produto WHERE idproduto = ?";
    $stmtSelect = mysqli_prepare($conexao, $sqlSelect);
    if (!$stmtSelect) throw new Exception("Erro ao preparar statement de produto.");

    foreach ($idprodutos as $idproduto) {
        $idproduto = intval($idproduto);
        $qtd = isset($quantidades[$idproduto]) ? intval($quantidades[$idproduto]) : 1;
        $qtd = max(1, $qtd);

        mysqli_stmt_bind_param($stmtSelect, "i", $idproduto);
        mysqli_stmt_execute($stmtSelect);
        $res = mysqli_stmt_get_result($stmtSelect);
        $produto = mysqli_fetch_assoc($res);

        if (!$produto) {
            throw new Exception("Produto {$idproduto} não existe.");
        }

        $precoUnit = (float)$produto['preco'];
        $subtotal = $precoUnit * $qtd;
        $valortotal += $subtotal;

        $itensParaInserir[] = [
            'idproduto' => $idproduto,
            'quantidade' => $qtd,
            'preco_unit' => $precoUnit
        ];
    }
    mysqli_stmt_close($stmtSelect);

    // 2) somar taxa de entrega
    $valortotal += $taxaEntrega;

    // 3) criar pagamento se necessário (assume que registrar_pagamento existe e retorna id)
    if ($pagamento_id <= 0) {
        // ajustar segundo a assinatura da sua função registrar_pagamento
        // Ex.: registrar_pagamento($conexao, $cliente, $status, $valor)
        $pagamento_id = registrar_pagamento($conexao, $cliente, 'pendente', $valortotal);
        if (!$pagamento_id) throw new Exception("Erro ao criar pagamento.");
    }

    // 4) criar pedido (assume salvarPedido retorna id do pedido)
    // Assumimos a assinatura: salvarPedido($conexao, $endentrega, $cliente, $pagamento_id, $valortotal, $idfeedback)
    $idpedido = salvarPedido($conexao, $endentrega, $cliente, $pagamento_id, $valortotal, $idfeedback);
    if (!$idpedido) throw new Exception("Erro ao criar pedido.");

    // 5) inserir itens no pedido_produto
    $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade, preco_unit) VALUES (?, ?, ?, ?)";
    $stmtItem = mysqli_prepare($conexao, $sqlItem);
    if (!$stmtItem) throw new Exception("Erro ao preparar inserção dos itens.");

    foreach ($itensParaInserir as $it) {
        // tipos: i i i d
        mysqli_stmt_bind_param($stmtItem, "iiid", $idpedido, $it['idproduto'], $it['quantidade'], $it['preco_unit']);
        if (!mysqli_stmt_execute($stmtItem)) {
            throw new Exception("Erro ao inserir item do pedido (produto {$it['idproduto']}).");
        }
    }
    mysqli_stmt_close($stmtItem);

    // 6) criar delivery (assume criar_delivery retorna id do delivery)
    $iddelivery = criar_delivery($conexao, $idpedido);
    if ($iddelivery === false || $iddelivery === null) {
        throw new Exception("Erro ao criar delivery.");
    }

    // 7) commit e limpar carrinho da sessão (atenção: usar a mesma chave que você usa no site)
    mysqli_commit($conexao);

    // limpar o carrinho correto: $_SESSION['carrinho']
    if (isset($_SESSION['carrinho'])) unset($_SESSION['carrinho']);

    // redirecionar para página de listagem/detalhes do pedido
    header("Location: ../Listar/listarpedido.php?id=" . urlencode($idpedido));
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexao);
    // mensagem de erro - em produção, registre o erro e mostre mensagem genérica
    echo "Erro: " . htmlspecialchars($e->getMessage());
    exit;
}
