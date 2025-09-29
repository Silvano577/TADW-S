<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica se usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Buscar usuário
$usuario = buscar_usuario($conexao, $usuario_id, "");
$usuario = $usuario[0] ?? null;

// Buscar cliente vinculado
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$cliente = $cliente[0] ?? null;

// Buscar endereços
$enderecos = [];
if ($cliente) {
    $enderecos = listar_enderecos_por_cliente($conexao, $cliente['idcliente']);
}

// Tratar exclusão de endereço se houver GET delete_endereco
if (isset($_GET['delete_endereco'])) {
    $id_endereco = intval($_GET['delete_endereco']);
    deletar_endereco($conexao, $id_endereco);
    header("Location: perfil.php");
    exit;
}

// Tratar exclusão da conta inteira se houver GET delete_conta
if (isset($_GET['delete_conta']) && $_GET['delete_conta'] == 1) {
    if ($cliente) {
        $cliente_id = $cliente['idcliente'];

        // Deletar endereços
        foreach ($enderecos as $end) {
            deletar_endereco($conexao, $end['idendentrega']);
        }

        // Deletar foto do cliente
        if (!empty($cliente['foto'])) {
            $arquivo = "/var/www/html/fotos/" . basename($cliente['foto']);
            if (file_exists($arquivo)) unlink($arquivo);
        }

        // Deletar cliente
        deletar_cliente($conexao, $cliente_id);
    }

    // Deletar usuário
    deletar_usuario($conexao, $usuario_id);

    // Finalizar sessão
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
    <title>Perfil do Usuário</title>
</head>
<body>
    <h1>Área de Perfil</h1>

    <div>
        <h2>Dados do Usuário</h2>
        <p><strong>Nome de Usuário:</strong> <?= htmlspecialchars($usuario['usuario']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($usuario['tipo']) ?></p>
        <a href="Forms/formusuario.php?id=<?= $usuario_id ?>">Editar Usuário</a>
    </div>

    <?php if ($cliente): ?>
        <div>
            <h2>Dados do Cliente</h2>
            <?php if (!empty($cliente['foto'])): ?>
                <img src="<?= htmlspecialchars($cliente['foto']) ?>" width="120" alt="Foto do cliente"><br>
            <?php endif; ?>
            <p><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
            <p><strong>Data de Aniversário:</strong> <?= htmlspecialchars($cliente['data_ani']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone']) ?></p>
            <a href="Forms/formcliente.php?id=<?= $cliente['idcliente'] ?>&usuario_id=<?= $usuario_id ?>">Editar Cliente</a>
        </div>

        <div>
            <h2>Endereços</h2>
            <?php if (count($enderecos) > 0): ?>
                <ul>
                    <?php foreach ($enderecos as $end): ?>
                        <li>
                            <?= htmlspecialchars($end['rua']) ?>, <?= htmlspecialchars($end['numero']) ?> - 
                            <?= htmlspecialchars($end['bairro']) ?> (<?= htmlspecialchars($end['complemento']) ?>)
                            <a href="Forms/formendentrega.php?id=<?= $end['idendentrega'] ?>&cliente_id=<?= $cliente['idcliente'] ?>">Editar</a>
                            <a href="perfil.php?delete_endereco=<?= $end['idendentrega'] ?>" onclick="return confirm('Deseja realmente excluir este endereço?');">Deletar</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum endereço cadastrado.</p>
            <?php endif; ?>
            <a href="Forms/formendentrega.php?cliente_id=<?= $cliente['idcliente'] ?>">Adicionar Endereço</a>
        </div>
    <?php else: ?>
        <div>
            <p>Você ainda não cadastrou seus dados de cliente.</p>
            <a href="Forms/formcliente.php?usuario_id=<?= $usuario_id ?>">Cadastrar agora</a>
        </div>
    <?php endif; ?>

    <div>
        <h2>Deletar Conta</h2>
        <a href="perfil.php?delete_conta=1" onclick="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita!');">Excluir Conta</a>
    </div>

    <br>
    <a href="index.php">Voltar</a>
</body>
</html>
