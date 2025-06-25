<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_bebidas = listar_bebidas($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Bebidas</title>
</head>
<body>
    <h1>Lista de Bebidas</h1>

    <?php if (count($lista_bebidas) == 0): ?>
        <p style="text-align:center;">Não existem bebidas cadastradas.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Preço</th>
                <th>Foto</th>
                <th colspan="2">Ações</th>
            </tr>
            <?php foreach ($lista_bebidas as $bebida): ?>
                <tr>
                    <td><?= $bebida['idbebidas'] ?></td>
                    <td><?= $bebida['marca'] ?></td>
                    <td>R$ <?= number_format($bebida['preco'], 2, ',', '.') ?></td>
                    <td><img src="<?= $bebida['foto'] ?>" width="80" alt="Foto da bebida"></td>
                    <td><a href="../Forms/formbebida.php?id=<?= $bebida['idbebidas'] ?>">Editar</a></td>
                    <td><a href="../Deletar/deletarbebida.php?id=<?= $bebida['idbebidas'] ?>" onclick="return confirm('Deseja realmente excluir esta bebida?');">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
