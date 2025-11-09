<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzaria - Sobre</title>
    <link rel="stylesheet" href="./css/sobre.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="./fotosc/l.png" alt="Logo Pizzaria">
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="cardapio.php">Cardápio</a></li>
            <li><a href="sobre.php" class="ativo">Sobre</a></li>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim'): ?>
                <?php if(($_SESSION['tipo'] ?? '') === 'adm'): ?>
                    <li><a href="homeAdm.php">Admin</a></li>
                <?php elseif(($_SESSION['tipo'] ?? '') === 'cliente'): ?>
                    <li><a href="perfil.php">Perfil</a></li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim'): ?>
                <li class="saudacao">Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? ''); ?></li>
                <li><a href="deslogar.php">Sair</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

    <main class="sobre">
        <h1>Sobre a Nossa Pizzaria</h1>
        <p>
            Tudo começou em uma pequena cozinha, onde o aroma do molho de tomate fresco se misturava 
            com o cheiro irresistível do pão assando no forno. Silvano, apaixonado por tecnologia e também 
            por sabores autênticos, decidiu unir duas paixões: criar algo próprio e levar alegria às pessoas 
            através da comida.
        </p>
        <p>
            Inspirado pelas receitas de família e pelas noites em que amigos se reuniam para saborear pizzas 
            caseiras, ele teve uma ideia: <strong>uma pizzaria que combinasse tradição artesanal com um toque moderno</strong>, 
            sem abrir mão do carinho em cada detalhe.
        </p>
        <p>
            Hoje, cada pizza é preparada com ingredientes frescos, massa feita diariamente e temperos especiais 
            que realçam o sabor. Do clássico muçarela às combinações exclusivas, tudo é pensado para que cada fatia 
            conte uma história.
        </p>
        <p>
            Mais do que uma pizzaria, somos um ponto de encontro para amigos, famílias e apaixonados por pizza. 
            Nosso objetivo é simples: <strong>fazer você se sentir em casa, comendo a melhor pizza da cidade</strong>.
        </p>
    </main>



<footer>
    <p>&copy; <?= date('Y'); ?> Pizzaria - Todos os direitos reservados.</p>
</footer>
</body>
</html>
