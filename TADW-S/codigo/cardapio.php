<?php
require_once "conexao.php";
require_once "funcao.php";
session_start();

// Verifica login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

// Buscar produtos
$produtos = listar_produtos($conexao);

// Processa compra se POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['idproduto'])) {
    $cliente_id = $_SESSION['idcliente']; // pegar id do cliente logado
    $idendereco = $_SESSION['idendereco'] ?? 1; // endereco padrão
    $idproduto = intval($_POST['idproduto']);
    $quantidade = intval($_POST['quantidade'] ?? 1);

    mysqli_begin_transaction($conexao);
    try {
        // 1) Buscar preço do produto
        $produto = buscar_produto($conexao, $idproduto);
        if (!$produto) throw new Exception("Produto não encontrado.");

        $valortotal = $produto['preco'] * $quantidade;

        // 2) Criar pagamento
        $idpagamento = registrar_pagamento($conexao, $cliente_id, 'pendente', $valortotal);
        if (!$idpagamento) throw new Exception("Erro ao criar pagamento.");

        // 3) Criar pedido
        $idpedido = salvarPedido($conexao, $idendereco, $cliente_id, $idpagamento, $valortotal);
        if (!$idpedido) throw new Exception("Erro ao criar pedido.");

        // 4) Inserir item no pedido_produto
        $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sqlItem);
        mysqli_stmt_bind_param($stmt, "iii", $idpedido, $idproduto, $quantidade);
        if (!mysqli_stmt_execute($stmt)) throw new Exception("Erro ao inserir item do pedido.");
        mysqli_stmt_close($stmt);

        // 5) Criar delivery
        $iddelivery = criar_delivery($conexao, $idpedido);
        if (!$iddelivery) throw new Exception("Erro ao criar delivery.");

        mysqli_commit($conexao);

        // Redireciona para a página de confirmação ou lista de pedidos
        header("Location: listarpedido.php?id={$idpedido}");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conexao);
        $erro_compra = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cardápio - Pizzaria</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header>
    <h1>Nosso Cardápio</h1>
</header>

<main>
    <?php if (!empty($erro_compra)): ?>
        <p style="color:red;"><?= htmlspecialchars($erro_compra) ?></p>
    <?php endif; ?>

    <section class="produtos">
        <?php foreach ($produtos as $p): ?>
            <div class="card">
                <img src="<?= $p['foto'] ?>" alt="<?= $p['nome'] ?>">
                <h3><?= htmlspecialchars($p['nome']) ?></h3>
                <?php if (!empty($p['tamanho'])): ?>
                    <p>Tamanho: <?= htmlspecialchars($p['tamanho']) ?></p>
                <?php endif; ?>
                <p class="preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>

                <!-- Formulário de compra -->
                <form method="post">
                    <input type="hidden" name="idproduto" value="<?= $p['idproduto'] ?>">
                    <input type="number" name="quantidade" value="1" min="1">
                    <button type="submit">Comprar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
