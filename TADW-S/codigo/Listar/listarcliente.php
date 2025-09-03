<?php
require_once "../conexao.php";
require_once "../funcao.php";

$clientes = listar_clientes($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Clientes</h1>

<?php if (count($clientes) === 0): ?>
    <p style="text-align:center;">Nenhum cliente cadastrado.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($clientes as $cliente): ?>
            <div class="card">
                <?php if (!empty($cliente['foto'])): ?>
                    <img src="../fotos/<?= basename($cliente['foto']) ?>" alt="Foto de <?= htmlspecialchars($cliente['nome'] ?? '') ?>">
                <?php else: ?>
                    <div class="no-image">Sem foto</div>
                <?php endif; ?>

                <h3><?= htmlspecialchars($cliente['nome'] ?? '') ?></h3>
                <p class="info"><strong>ID:</strong> <?= htmlspecialchars($cliente['idcliente'] ?? '') ?></p>
                <p class="info"><strong>Data de Nascimento:</strong> <?= htmlspecialchars($cliente['data_ani'] ?? '') ?></p>
                <p class="info"><strong>Endereço:</strong> <?= htmlspecialchars($cliente['endereco'] ?? '') ?></p>
                <p class="info"><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone'] ?? '') ?></p>

                <a href="../Forms/formcliente.php?id=<?= $cliente['idcliente'] ?>" class="btn">Editar</a>
                <a href="../Deletar/deletarCliente.php?id=<?= $cliente['idcliente'] ?>&foto=<?= urlencode($cliente['foto'] ?? '') ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div style="text-align:center; margin-top:20px;">
    <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
</div>
</body>
</html>
