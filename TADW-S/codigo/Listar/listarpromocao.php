<?php
require_once "../conexao.php";
require_once "../funcao.php";

$lista_promocao = listar_promocoes($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Promoçao</title>
</head>
<body>
    <h1>Lista de Pizzas</h1>

    <?php if (count($lista_promocao) == 0): ?>
        <p style="text-align:center;">Não existem promoçoes cadastradas.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>descricao</th>
                <th>preco</th>
                <th>Foto</th>
                <th colspan="2">Ações</th>
            </tr>
            <?php foreach ($lista_promocao as $promocao): ?>
                <tr>
                    <td><?= $promocao['idpromocao'] ?></td>
                    <td><?= $promocao['descricao'] ?></td>
                    <td>R$ <?= number_format($promocao['preco'], 2, ',', '.') ?></td>
                    <td><img src="<?= $promocao['foto'] ?>" width="80" alt="Foto da promocao"></td>
                    <td><a href="../Forms/formpromocao.php?id=<?= $promocao['idpromocao'] ?>">Editar</a></td>
                    <td><a href="../Deletar/deletarpromocao.php?id=<?= $promocao['idpromocao'] ?>" onclick="return confirm('Deseja realmente excluir essa Promoçao?');">Excluir</a></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- ✅ Botão "Voltar" sempre aparece -->
    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
