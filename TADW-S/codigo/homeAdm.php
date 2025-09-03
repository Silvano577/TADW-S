<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim' || ($_SESSION['tipo'] ?? '') !== 'adm') {
    header("Location: index.php");
    exit;
}


require_once "protege.php";
protegeTipo('adm'); // garante que só ADM acesse

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistema Pizzaria</title>
    <link rel="stylesheet" href="../css/adm.css">
    <!-- Google Fonts e FontAwesome para ícones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
    <div class="user-info">
        <span>Bem-vindo, <?php echo htmlspecialchars($usuario); ?> (<?php echo htmlspecialchars($tipo); ?>)</span>
    </div>
    <div class="logout">
        <a href="deslogar.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Home</a>

</header>

<main class="dashboard">
    <h1>Painel Administrativo</h1>
    <ul class="menu-grid">
        <li><a href="./Forms/formusuario.php"><i class="fas fa-user-plus"></i> Cadastrar Usuário</a></li>
        <li><a href="./Forms/formcliente.php"><i class="fas fa-user-friends"></i> Cadastrar Cliente</a></li>
        <li><a href="./Forms/formfeedback.php"><i class="fas fa-comment-dots"></i> Registrar Feedback</a></li>
        <li><a href="./Forms/formproduto.php"><i class="fas fa-pizza-slice"></i> Cadastrar Produto</a></li>
        <li><a href="./Forms/formpagamento.php"><i class="fas fa-credit-card"></i> Registrar Pagamento</a></li>
        <li><a href="./Forms/formdelivery.php"><i class="fas fa-motorcycle"></i> Registrar Delivery</a></li>
        <li><a href="./Forms/formpedido.php"><i class="fas fa-receipt"></i> Registrar Pedido</a></li>
        <li><a href="./Listar/listarproduto.php"><i class="fas fa-list"></i> Listar Produto</a></li>
        <li><a href="./Listar/listarcliente.php"><i class="fas fa-shopping-cart"></i> Listar Cliente</a></li>
        <li><a href="./Listar/listarusuario.php"><i class="fas fa-shopping-cart"></i> Listar usuario</a></li>
        <li><a href="./Listar/listarvenda.php"><i class="fas fa-shopping-cart"></i> Listar Venda</a></li>
        <li><a href="./Listar/listarpedido.php"><i class="fas fa-shopping-cart"></i> Listar Pedido</a></li>
        <li><a href="./Listar/listarfeedback.php"><i class="fas fa-shopping-cart"></i> Listar Feedback</a></li>
        <li><a href="./Listar/listardelivery.php"><i class="fas fa-shopping-cart"></i> Listar delivery</a></li>
        <li><a href="./Forms/formvenda.php"><i class="fas fa-shopping-cart"></i> Fazer Compra</a></li>

    </ul>
</main>

</body>
</html>
