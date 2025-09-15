<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

$deliveries = listar_deliveries($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Deliveries</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Acompanhamento de Pedidos (Delivery)</h1>

<?php if (count($deliveries) === 0): ?>
    <p style="text-align:center;">Nenhum delivery cadastrado.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($deliveries as $d): ?>
            <div class="card">
                <h3>Pedido #<?= htmlspecialchars($d['pedido_id']) ?></h3>
                <p><strong>Status:</strong> <?= htmlspecialchars($d['status']) ?></p>
                <p><strong>Iniciado:</strong> <?= htmlspecialchars($d['iniciado_em']) ?></p>
                <p><strong>Entregue em:</strong> <?= htmlspecialchars($d['entregue_em'] ?? '-') ?></p>

                <a href="../Forms/formdelivery.php?id=<?= $d['iddelivery'] ?>" class="btn">Editar</a>
                <a href="../Deletar/deletardelivery.php?id=<?= $d['iddelivery'] ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="../home.php" class="btn-voltar">Voltar</a>
</body>
</html>
