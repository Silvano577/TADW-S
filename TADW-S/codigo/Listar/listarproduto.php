<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Filtro opcional por tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : "";

// Se tipo estiver definido, busca com filtro
$lista_produtos = [];
if ($tipo) {
    $todos = listar_produtos($conexao);
    foreach ($todos as $produto) {
        if ($produto['tipo'] === $tipo) {
            $lista_produtos[] = $produto;
        }
    }
} else {
    $lista_produtos = listar_produtos($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
</head>
<body>
    <h1>Lista de Produtos</h1>

    <form method="get" action="">
        <label>Filtrar por tipo:</label>
        <select name="tipo" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="pizza" <?= $tipo === 'pizza' ? 'selected' : '' ?>>Pizza</option>
            <option value="bebida" <?= $tipo === 'bebida' ? 'selected' : '' ?>>Bebida</option>
            <option value="promocao" <?= $tipo === 'promocao' ? 'selected' : '' ?>>Promoção</option>
        </select>
    </form>

    <?php if (count($lista_produtos) == 0): ?>
        <p style="text-align:center;">Nenhum produto encontrado.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Tamanho</th>
                <th>Preço</th>
                <th>Foto</th>
                <th colspan="2">Ações</th>
            </tr>
            <?php foreach ($lista_produtos as $produto): ?>
                <tr>
                    <td><?= $produto['idproduto'] ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= $produto['tipo'] ?></td>
                    <td><?= $produto['tamanho'] ?? '-' ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td>
                        <?php if (!empty($produto['foto'])): ?>
                            <img src="<?= $produto['foto'] ?>" width="80" alt="Foto do produto">
                        <?php else: ?>
                            Sem imagem
                        <?php endif; ?>
                    </td>
                    <td><a href="../Forms/formproduto.php?id=<?= $produto['idproduto'] ?>">Editar</a></td>
                    <td><a href="../Deletar/deletarproduto.php?id=<?= $produto['idproduto'] ?>" onclick="return confirm('Deseja realmente excluir este produto?');">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <form action="../home.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
