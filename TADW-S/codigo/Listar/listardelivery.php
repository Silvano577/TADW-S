<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_delivery = listar_deliveries($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deliveries - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Deliveries</h1>

    <?php if (count($lista_delivery) === 0): ?>
        <p style="text-align:center;">Nenhum delivery encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_delivery as $delivery): ?>
                <div class="card">
                    <h3>Delivery #<?= htmlspecialchars($delivery['iddelivery']) ?></h3>
                    <p><strong>Endereço:</strong> <?= htmlspecialchars($delivery['endereco_entrega']) ?></p>
                    <p><strong>Tempo estimado:</strong> <?= htmlspecialchars($delivery['tempo_entrega']) ?></p>
                    
                    <a href="../Forms/formdelivery.php?id=<?= $delivery['iddelivery'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletardelivery.php?id=<?= $delivery['iddelivery'] ?>" onclick="return confirm('Deseja realmente excluir este delivery?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
