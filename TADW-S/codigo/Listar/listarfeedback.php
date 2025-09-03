
<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_feedbacks = listar_feedbacks($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Feedbacks - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Feedbacks</h1>

    <?php if (count($lista_feedbacks) === 0): ?>
        <p style="text-align:center;">Nenhum feedback encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_feedbacks as $fb): ?>
                <div class="card">
                    <h3>Cliente: <?= htmlspecialchars($fb['idcliente']) ?></h3>
                    <p><?= htmlspecialchars($fb['comentario']) ?></p>
                    <a href="../Forms/formfeedback.php?id=<?= $fb['idfeedback'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarfeedback.php?id=<?= $fb['idfeedback'] ?>" onclick="return confirm('Deseja realmente excluir este feedback?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
