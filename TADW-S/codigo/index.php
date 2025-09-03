<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once "conexao.php"; 

// Buscar todos os produtos (pizzas e bebidas)
$sql = "SELECT * FROM produto WHERE tipo IN ('pizza', 'bebida')";
$result = $conexao->query($sql);
$produtos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzaria - Home</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="../fotosc/images.png" alt="Logo Pizzaria">
    </div>
    <nav>
        <ul>
            <li><a href="index.php" class="ativo">Início</a></li>
            <li><a href="sobre.php">Sobre</a></li>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim' && ($_SESSION['tipo'] ?? '') === 'adm'): ?>

                <li><a href="homeAdm.php">Admin</a></li>
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
        <h1>Bem-vindo à nossa Pizzaria!</h1>
        <p>Confira nossas novidades em pizzas e bebidas.</p>

        <section class="produtos">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $p): ?>
                    <div class="card">
                        <img src="<?php echo $p['foto']; ?>" alt="<?php echo $p['nome']; ?>">
                        <h3><?php echo $p['nome']; ?></h3>
                        <?php if (!empty($p['tamanho'])): ?>
                            <p>Tamanho: <?php echo $p['tamanho']; ?></p>
                        <?php endif; ?>
                        <p class="preco">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum produto cadastrado ainda.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Pizzaria - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
