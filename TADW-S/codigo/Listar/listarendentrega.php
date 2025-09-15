<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

$enderecos = listar_enderecos($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Endereços</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Endereços</h1>

<?php if (count($enderecos) === 0): ?>
    <p style="text-align:center;">Nenhum endereço cadastrado.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($enderecos as $endereco): ?>
            <div class="card">
                <h3><?= htmlspecialchars($endereco['rua']) ?>, <?= htmlspecialchars($endereco['numero']) ?></h3>
                <p class="info"><strong>ID:</strong> <?= $endereco['idendentrega'] ?></p>
                <p class="info"><strong>Bairro:</strong> <?= htmlspecialchars($endereco['bairro']) ?></p>
                <p class="info"><strong>Complemento:</strong> <?= htmlspecialchars($endereco['complemento']) ?></p>
                <p class="info"><strong>ID Cliente:</strong> <?= $endereco['cliente'] ?></p>

                <a href="../Forms/formendentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn">Editar</a>
                <a href="../Deletar/deletarendentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="../homeAdm.php" class="btn-voltar">Voltar</a>
</body>
</html>
