<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Buscar todos os produtos
$produtos = listar_produtos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cardápio - Pizzaria</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header>
    <h1>Nosso Cardápio</h1>
    <nav>
        <a href="index.php">Início</a> |
        <a href="cardapio.php" class="ativo">Cardápio</a> |
        <a href="carrinho.php">🛒 Carrinho</a> |
        <a href="contato.php">Contato</a>
    </nav>
</header>

<main>
    <section class="produtos">
        <?php if (count($produtos) > 0): ?>
            <form action="adicionar.php" method="post">
                <div class="grid-produtos">
                    <?php foreach ($produtos as $p): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($p['foto']); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>">
                            <h3><?php echo htmlspecialchars($p['nome']); ?></h3>

                            <?php if (!empty($p['tamanho'])): ?>
                                <p>Tamanho: <?php echo htmlspecialchars($p['tamanho']); ?></p>
                            <?php endif; ?>

                            <p class="preco">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>

                            <label>Qtd:</label>
                            <input type="number" name="quantidade[<?= $p['idproduto'] ?>]" value="1" min="1" max="10">

                            <input type="checkbox" name="idproduto[]" value="<?= $p['idproduto'] ?>" id="prod<?= $p['idproduto'] ?>">
                            <label for="prod<?= $p['idproduto'] ?>">Selecionar</label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="botoes">
                    <button type="submit" class="btn">Adicionar ao Carrinho</button>
                    <a href="carrinho.php" class="btn">Ver Carrinho</a>
                </div>
            </form>
        <?php else: ?>
            <p>Nenhum produto disponível no momento.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Pizzaria</p>
</footer>
</body>
</html>
