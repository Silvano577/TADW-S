<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim' || ($_SESSION['tipo'] ?? '') !== 'adm') {
    header("Location: index.php");
    exit;
}

require_once "protege.php";
protegeTipo('adm');

// Captura informações do usuário logado (caso existam)
$usuario = $_SESSION['usuario'] ?? 'Administrador';
$tipo = $_SESSION['tipo'] ?? 'adm';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Pizzaria</title>
    <link rel="stylesheet" href="../css/homeAdm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- ================== CABEÇALHO ================== -->
    <header>
        <div class="logo">
            <img src="../fotosc/l.png" alt="Logo Pizzaria">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="homeAdm.php" class="ativo">Admin</a></li>
                <li><a href="deslogar.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <!-- ================== CONTEÚDO PRINCIPAL ================== -->
    <main class="dashboard">
        <div class="user-info">
            <h2>Bem-vindo, <strong><?php echo htmlspecialchars($usuario); ?></strong> (<span><?php echo htmlspecialchars($tipo); ?></span>)</h2>
        </div>

        <h1>Painel Administrativo</h1>

        <ul class="menu-grid">
            <li><a href="./Forms/formproduto.php"><i class="fas fa-pizza-slice"></i> Cadastrar Produto</a></li>
            <li><a href="./Forms/formdelivery.php"><i class="fas fa-motorcycle"></i> Registrar Delivery</a></li>
            <li><a href="./Forms/formstatusdelivery.php"><i class="fas fa-truck"></i> Status do Delivery</a></li>
            <li><a href="./Listar/listarproduto.php"><i class="fas fa-list"></i> Listar Produtos</a></li>
            <li><a href="./Listar/listarcliente.php"><i class="fas fa-users"></i> Listar Clientes</a></li>
            <li><a href="./Listar/listarusuario.php"><i class="fas fa-user-cog"></i> Listar Usuários</a></li>
            <li><a href="./Listar/listarpedido.php"><i class="fas fa-clipboard-list"></i> Listar Pedidos</a></li>
            <li><a href="./Listar/listarfeedback.php"><i class="fas fa-comments"></i> Listar Feedbacks</a></li>
            <li><a href="./Listar/listardelivery.php"><i class="fas fa-motorcycle"></i> Listar Deliveries</a></li>
            <li><a href="./Listar/listarendentrega.php"><i class="fas fa-map"></i> Listar Endereços</a></li>
            <li><a href="./Listar/listarpagamentos.php"><i class="fas fa-file-invoice-dollar"></i> Listar Pagamentos</a></li>
        </ul>
    </main>

</body>
</html>
