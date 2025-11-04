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
    <link rel="stylesheet" href="./css/cardapio.css">
    <script defer src="./js/cardapio.js"></script>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="./fotosc/logo.png" alt="Logo da Pizzaria" class="logo">
        <h1>Nosso Cardápio</h1>
    </div>
    <nav>
        <a href="index.php">Início</a> |
        <a href="cardapio.php" class="ativo">Cardápio</a> |
        <a href="carrinho.php" class="carrinho-status">🛒 0 itens</a> |
        <a href="contato.php">Contato</a>
    </nav>
</header>

<main>
    <!-- FILTRO LATERAL -->
    <aside class="filtro">
        <button class="btn-filtro ativo" data-categoria="todas">TODOS</button>
        <button class="btn-filtro" data-categoria="pizza">PIZZAS</button>
        <button class="btn-filtro" data-categoria="bebida">BEBIDAS</button>
        <button class="btn-filtro" data-categoria="promocao">PROMOÇÕES</button>
    </aside>

    <!-- PRODUTOS -->
    <section class="produtos">
        <?php if (count($produtos) > 0): ?>
            <div class="grid-produtos">
                <?php foreach ($produtos as $p): ?>
                    <div class="card" data-categoria="<?php echo htmlspecialchars($p['tipo']); ?>">
                        <img src="<?php echo htmlspecialchars($p['foto']); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>">
                        <h3><?php echo htmlspecialchars($p['nome']); ?></h3>

                        <?php if (!empty($p['tamanho'])): ?>
                            <p>Tamanho: <?php echo htmlspecialchars($p['tamanho']); ?></p>
                        <?php endif; ?>

                        <p class="preco">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>
                            

                        <input type="hidden" id="qtd<?= $p['idproduto'] ?>" value="1">


                        <button type="button" class="btn-adicionar" data-id="<?= $p['idproduto'] ?>">Adicionar ao Carrinho</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhum produto disponível no momento.</p>
        <?php endif; ?>
    </section>

    <!-- Container do carrinho (dinâmico) -->
    <section id="carrinho-container" class="carrinho-ajax"></section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Pizzaria</p>
</footer>

</body>
</html>
