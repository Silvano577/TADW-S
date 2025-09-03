<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Filtro opcional por tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : "";

// Buscar produtos usando a função listar_produtos()
$lista_produtos = [];
$todos = listar_produtos($conexao);
if ($tipo) {
    foreach ($todos as $produto) {
        if ($produto['tipo'] === $tipo) {
            $lista_produtos[] = $produto;
        }
    }
} else {
    $lista_produtos = $todos;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Pizzaria Delícia</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Produtos</h1>

    <form method="get" action="" class="filtro-form">
        <label>Filtrar por tipo:</label>
        <select name="tipo" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="pizza" <?= $tipo === 'pizza' ? 'selected' : '' ?>>Pizza</option>
            <option value="bebida" <?= $tipo === 'bebida' ? 'selected' : '' ?>>Bebida</option>
            <option value="promocao" <?= $tipo === 'promocao' ? 'selected' : '' ?>>Promoção</option>
        </select>
    </form>

    <?php if (count($lista_produtos) === 0): ?>
        <p style="text-align:center;">Nenhum produto encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_produtos as $produto): ?>
                <div class="card">
                    <?php if (!empty($produto['foto'])): ?>
                        <!-- Usa basename para pegar apenas o nome do arquivo -->
                        <img src="../fotos/<?= basename($produto['foto']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <?php else: ?>
                        <div class="no-image">Sem imagem</div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p>Tipo: <?= $produto['tipo'] ?></p>
                    <?php if (!empty($produto['tamanho'])): ?>
                        <p>Tamanho: <?= htmlspecialchars($produto['tamanho']) ?></p>
                    <?php endif; ?>
                    <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <a href="../Forms/formproduto.php?id=<?= $produto['idproduto'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarproduto.php?id=<?= $produto['idproduto'] ?>" onclick="return confirm('Deseja realmente excluir este produto?');" class="btn btn-delete">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
