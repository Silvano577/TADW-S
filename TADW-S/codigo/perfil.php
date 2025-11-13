<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Busca dados do usuário
$usuario = buscar_usuario($conexao, $usuario_id);
if (is_array($usuario) && isset($usuario[0])) {
    $usuario = $usuario[0]; // garante que seja um array associativo simples
}

// Busca cliente vinculado ao usuário
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
if (!is_array($cliente) || empty($cliente)) {
    $cliente = null;
}

// Busca endereços do cliente (se houver)
$enderecos = [];
if (!empty($cliente['idcliente'])) {
    $enderecos = buscar_enderecos_por_cliente($conexao, $cliente['idcliente']);
}

// Exclusão de endereço
if (isset($_GET['delete_endereco'])) {
    $id_endereco = intval($_GET['delete_endereco']);
    deletar_endereco($conexao, $id_endereco);
    header("Location: perfil.php");
    exit;
}

// Exclusão completa de conta
if (isset($_GET['delete_conta']) && $_GET['delete_conta'] == 1) {
    if (!empty($cliente['idcliente'])) {
        $cliente_id = $cliente['idcliente'];

        // Exclui endereços
        foreach ($enderecos as $end) {
            deletar_endereco($conexao, $end['idendentrega']);
        }

        // Remove foto do cliente se existir
        if (!empty($cliente['foto'])) {
            $arquivo = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($cliente['foto'], '/');
            if (file_exists($arquivo)) unlink($arquivo);
        }

        deletar_cliente($conexao, $cliente_id);
    }

    // Exclui usuário
    deletar_usuario($conexao, $usuario_id);
    session_unset();
    session_destroy();
    header("Location: login.php?conta_deletada=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Usuário - Perfil</title>
    <link rel="stylesheet" href="./css/perfi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><a href="sobre.php">Sobre</a></li>

            <?php if (!empty($_SESSION['logado']) && $_SESSION['logado'] === 'sim'): ?>
                <?php if(($_SESSION['tipo'] ?? '') === 'adm'): ?>
                    <li><a href="homeAdm.php">Admin</a></li>
                <?php elseif(($_SESSION['tipo'] ?? '') === 'cliente'): ?>
                    <li><a href="perfil.php" class="ativo">Perfil</a></li>
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

<main class="perfil-container">
    <section class="perfil-card">
        <div class="perfil-titulo">
            <i class="fa-regular fa-user-circle"></i>
            <h2>Seu Perfil</h2>
        </div>

        <div class="perfil-conteudo">
            <div class="perfil-esquerda">
                <div class="foto-perfil">
                    <img src="<?= htmlspecialchars($cliente['foto'] ?? 'imagens/default_user.png') ?>" alt="Foto de perfil">
                </div>

                <h4>Dados do Usuário</h4>
                <label>Nome de Usuário:</label>
                <input type="text" value="<?= htmlspecialchars($usuario['usuario'] ?? '') ?>" readonly>

                <label>E-mail:</label>
                <input type="text" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" readonly>

                <label>Senha:</label>
                <input type="password" value="************" readonly>
            </div>

            <div class="perfil-direita">
                <h4>Dados do Cliente</h4>
                <label>Nome:</label>
                <input type="text" value="<?= htmlspecialchars($cliente['nome'] ?? 'Não cadastrado') ?>" readonly>

                <label>Data de Nascimento:</label>
                <input type="text" value="<?= htmlspecialchars($cliente['data_ani'] ?? 'Não informado') ?>" readonly>

                <label>Telefone:</label>
                <input type="text" value="<?= htmlspecialchars($cliente['telefone'] ?? 'Não informado') ?>" readonly>

                <p>Suporte: (62) 99463-0327</p>
            </div>

            <aside class="config-painel">
                <a href="Forms/formusuario.php?id=<?= $usuario_id ?>">Editar Usuário</a>

                <?php if (!empty($cliente['idcliente'])): ?>
                    <a href="Forms/formcliente.php?id=<?= intval($cliente['idcliente']) ?>">Editar Cliente</a>
                    <a href="Forms/formentrega.php?cliente_id=<?= intval($cliente['idcliente']) ?>">Registrar Endereço</a>
                <?php else: ?>
                    <a href="Forms/formcliente.php?idusuario=<?= intval($usuario_id) ?>">Cadastrar Dados do Cliente</a>
                <?php endif; ?>

                <a href="meus_pedidos.php">Histórico de Pedidos</a>
                <a href="./Listar/listarendentrega.php">Meus Endereços</a>
                <a href="perfil.php?delete_conta=1" onclick="return confirm('Deseja realmente excluir sua conta?')">Excluir Conta</a>
                <a href="deslogar.php" class="btn-danger">Sair</a>
            </aside>
        </div>
    </section>
</main>

</body>
</html>
