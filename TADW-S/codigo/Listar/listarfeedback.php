<?php
require_once "../conexao.php";
require_once "../funcao.php";

$feedbacks = listar_feedback($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Feedbacks</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Feedbacks</h1>

<?php if (count($feedbacks) === 0): ?>
    <p style="text-align:center;">Nenhum feedback cadastrado.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($feedbacks as $feedback): ?>
            <div class="card">
                <h3><?= htmlspecialchars($feedback['assunto'] ?? '') ?></h3>
                <p class="info"><strong>ID:</strong> <?= htmlspecialchars($feedback['idfeedback'] ?? '') ?></p>
                <p class="info"><strong>Comentário:</strong> <?= nl2br(htmlspecialchars($feedback['comentario'] ?? '')) ?></p>

                <a href="../Forms/formfeedback.php?id=<?= $feedback['idfeedback'] ?>" class="btn">Editar</a>
                <a href="../Deletar/deletarfeedback.php?id=<?= $feedback['idfeedback'] ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
</div>
</body>
</html>
