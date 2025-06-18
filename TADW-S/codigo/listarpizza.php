<?php
require_once "conexao.php";
require_once "funcao.php";

$lista_pizzas = listar_pizzas($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pizzas</title>
</head>
<body>
    <h1>Lista de Pizzas</h1>

    <?php if (count($lista_pizzas) == 0): ?>
        <p style="text-align:center;">Não existem pizzas cadastradas.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Variedade</th>
                <th>Tamanho</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Foto</th>
                <th colspan="2">Ações</th>
            </tr>
            <?php foreach ($lista_pizzas as $pizza): ?>
                <tr>
                    <td><?= $pizza['idpizza'] ?></td>
                    <td><?= $pizza['variedade'] ?></td>
                    <td><?= $pizza['tamanho'] ?></td>
                    <td>R$ <?= number_format($pizza['preco'], 2, ',', '.') ?></td>
                    <td><?= $pizza['quantidade'] ?></td>
                    <td><img src="<?= $pizza['foto'] ?>" width="80" alt="Foto da pizza"></td>
                    <td><a href="formpizza.php?id=<?= $pizza['idpizza'] ?>">Editar</a></td>
                    <td><a href="deletarpizza.php?id=<?= $pizza['idpizza'] ?>" onclick="return confirm('Deseja realmente excluir esta pizza?');">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- ✅ Botão "Voltar" sempre aparece -->
    <form action="home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
