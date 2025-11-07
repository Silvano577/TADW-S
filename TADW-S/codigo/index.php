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
    <title>Pizzaria - Home</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="./fotosc/l.png" alt="Logo Pizzaria">
    </div>
    <nav>
        <ul>
            <li><a href="index.php" class="ativo">Início</a></li>
            <li><a href="cardapio.php">Cardápio</a></li>
            <li><a href="sobre.php">Sobre</a></li>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim'): ?>
                <?php if(($_SESSION['tipo'] ?? '') === 'adm'): ?>
                    <li><a href="homeAdm.php">Admin</a></li>
                <?php elseif(($_SESSION['tipo'] ?? '') === 'cliente'): ?>
                    <li><a href="perfil.php">Perfil</a></li>
                <?php endif; ?>
            <?php endif; ?>

            <li><a href="contato.php">Contato</a></li>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim'): ?>
                <li class="saudacao">Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? ''); ?></li>
                <li><a href="deslogar.php">Sair</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <div class="texto-principal">
        <h1>Bem-vindo à nossa Pizzaria!</h1>
        <p>Saboreie o melhor da culinária sem sair de casa.</p>
        <a href="cardapio.php" class="btn-cardapio">Ver Cardápio</a>
    </div>

    <div class="imagem-pizza">
        <img src="./imagens/pizza-principal.png" alt="Pizza ">
    </div>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Pizzaria - Todos os direitos reservados.</p>
</footer>
</body>
</html>
