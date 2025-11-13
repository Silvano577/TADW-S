<?php
require_once "../conexao.php";
require_once "../funcao.php";

// Filtro opcional por tipo
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : "";
// Busca opcional por nome
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : "";

// Buscar todos os produtos usando a função existente
$todos = listar_produtos($conexao);

// Aplicar filtro de tipo e busca por nome
$lista_produtos = array_filter($todos, function($produto) use ($tipo, $busca) {
    $matchTipo = $tipo === "" || $produto['tipo'] === $tipo;
    $matchNome = $busca === "" || stripos($produto['nome'], $busca) !== false;
    return $matchTipo && $matchNome;
});
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

    <form method="get" class="form-pesquisa">
        <input type="text" name="busca" placeholder="Pesquisar produto..."
               value="<?= htmlspecialchars($busca) ?>" class="input-pesquisa">
        <select name="tipo" onchange="this.form.submit()" class="input-pesquisa">
            <option value="">Todos</option>
            <option value="pizza" <?= $tipo === 'pizza' ? 'selected' : '' ?>>Pizza</option>
            <option value="bebida" <?= $tipo === 'bebida' ? 'selected' : '' ?>>Bebida</option>
            <option value="promocao" <?= $tipo === 'promocao' ? 'selected' : '' ?>>Promoção</option>
        </select>
        <button type="submit" class="btn-pesquisa">Buscar</button>
        <?php if ($busca !== "" || $tipo !== ""): ?>
            <a href="listarproduto.php" class="link-limpar">Limpar</a>
        <?php endif; ?>
    </form>

    <?php if (empty($lista_produtos)): ?>
        <p style="text-align:center;">Nenhum produto encontrado.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($lista_produtos as $produto): ?>
                <div class="card">
                    <?php if (!empty($produto['foto'])): ?>
                        <img src="../fotos/<?= basename($produto['foto']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <?php else: ?>
                        <div class="no-image">Sem imagem</div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p>Tipo: <?= htmlspecialchars($produto['tipo']) ?></p>
                    <?php if (!empty($produto['tamanho'])): ?>
                        <p>Tamanho: <?= htmlspecialchars($produto['tamanho']) ?></p>
                    <?php endif; ?>
                    <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <a href="../Forms/formproduto.php?id=<?= $produto['idproduto'] ?>" class="btn">Editar</a>
                    <a href="../Deletar/deletarproduto.php?id=<?= $produto['idproduto'] ?>" class="btn btn-delete"
                       onclick="return confirm('Deseja realmente excluir este produto?');">Excluir</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="../homeAdm.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
