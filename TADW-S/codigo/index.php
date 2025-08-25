<?php
session_start();
$usuarioLogado = isset($_SESSION['logado']) && $_SESSION['logado'] === 'sim';
$nomeUsuario = $_SESSION['usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzaria Delícia</title>
    <link rel="stylesheet" href="cliente.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo">🍕 Pizzaria Delícia</div>
    <nav>
        <a href="#cardapio">Cardápio</a>
        <a href="#promocoes">Promoções</a>
        <a href="#sobre">Sobre</a>
        <a href="#depoimentos">Depoimentos</a>
        <a href="#contato">Contato</a>
        <?php if ($usuarioLogado): ?>
            <a href="minha_conta.php" class="btn-login">Minha Conta (<?php echo htmlspecialchars($nomeUsuario); ?>)</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Entrar</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Banner -->
<section class="banner">
    <div class="banner-text">
        <h1>A melhor pizza da cidade</h1>
        <p>Peça agora e receba quentinha na sua casa!</p>
        <a href="#cardapio" class="btn-pedir">Ver Cardápio</a>
    </div>
</section>

<!-- Cardápio -->
<section id="cardapio" class="cardapio">
    <h2>Nosso Cardápio</h2>
    <div class="grid">
        <?php
        // Exemplo básico para puxar do banco
        require_once "conexao.php";
        $sql = "SELECT * FROM pizza ORDER BY variedade ASC";
        $resultado = mysqli_query($conexao, $sql);
        while ($pizza = mysqli_fetch_assoc($resultado)):
        ?>
        <div class="card">
            <img src="<?php echo $pizza['foto']; ?>" alt="<?php echo htmlspecialchars($pizza['variedade']); ?>">
            <h3><?php echo htmlspecialchars($pizza['variedade']); ?></h3>
            <p>R$ <?php echo number_format($pizza['preco'], 2, ',', '.'); ?></p>
            <a href="pedido.php?id=<?php echo $pizza['id']; ?>" class="btn">Pedir agora</a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Promoções -->
<section id="promocoes" class="promocoes">
    <h2>Promoções da Semana</h2>
    <div class="promo-grid">
        <?php
        $sql = "SELECT * FROM promocao ORDER BY id DESC";
        $resultado = mysqli_query($conexao, $sql);
        while ($promo = mysqli_fetch_assoc($resultado)):
        ?>
        <div class="promo-card">
            <p><?php echo htmlspecialchars($promo['descricao']); ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Sobre -->
<section id="sobre" class="sobre">
    <h2>Sobre Nós</h2>
    <p>Há mais de 10 anos levando sabor e qualidade para você. Nossas pizzas são feitas com ingredientes frescos e assadas na hora.</p>
</section>

<!-- Depoimentos -->
<section id="depoimentos" class="depoimentos">
    <h2>O que dizem nossos clientes</h2>
    <div class="grid">
        <div class="card">
            <p>"Melhor pizza que já comi! Atendimento excelente."</p>
            <strong>- João</strong>
        </div>
        <div class="card">
            <p>"Entrega super rápida e pizza quentinha."</p>
            <strong>- Maria</strong>
        </div>
        <div class="card">
            <p>"Sou cliente fiel, recomendo a todos."</p>
            <strong>- Carlos</strong>
        </div>
    </div>
</section>

<!-- Contato -->
<section id="contato" class="contato">
    <h2>Fale Conosco</h2>
    <p><i class="fas fa-phone"></i> (11) 99999-9999</p>
    <p><i class="fab fa-whatsapp"></i> (11) 98888-8888</p>
    <p><i class="fas fa-map-marker-alt"></i> Rua das Pizzas, 123 - Centro</p>
</section>

<!-- Rodapé -->
<footer>
    <p>&copy; 2025 Pizzaria Delícia | Todos os direitos reservados.</p>
</footer>

</body>
</html>
