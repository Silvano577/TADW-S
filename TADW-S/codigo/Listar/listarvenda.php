<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_pagamentos = listar_pagamentos($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vendas - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Vendas</h1>

    <?php if (count($lista_pagamentos) === 0): ?>
        <p style="text-align:center;">Nenhuma venda encontrada.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_pagamentos as $pagamento): ?>
                <div class="card">
                    <h3>Pedido: <?= htmlspecialchars($pagamento['idpedido']) ?></h3>
                    <p>Método: <?= htmlspecialchars($pagamento['metodo']) ?></p>
                    <p>Valor: R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></p>
                    <a href="../Forms/formpagamento.php?id=<?= $pagamento['idpagamento'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarpagamento.php?id=<?= $pagamento['idpagamento'] ?>" onclick="return confirm('Deseja realmente excluir esta venda?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>

