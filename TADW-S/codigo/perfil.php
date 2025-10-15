<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica se o usuário está logado
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Buscar dados do usuário
$usuario = buscar_usuario($conexao, $usuario_id); // já retorna 1 usuário ou null

// Buscar dados do cliente vinculado
$idcliente = buscar_cliente_por_usuario($conexao, $usuario_id);

// Garantir que seja um array válido
if (!is_array($idcliente) || empty($idcliente)) {
    $idcliente = null;
}

// Buscar endereços do cliente
$enderecos = [];
if (!empty($idcliente['idcliente'])) {
    $enderecos = buscar_enderecos_por_cliente($conexao, $idcliente['idcliente']);
}

// Tratar exclusão de endereço
if (isset($_GET['delete_endereco'])) {
    $id_endereco = intval($_GET['delete_endereco']);
    deletar_endereco($conexao, $id_endereco);
    header("Location: perfil.php");
    exit;
}

// Tratar exclusão da conta inteira
if (isset($_GET['delete_conta']) && $_GET['delete_conta'] == 1) {
    if (!empty($idcliente['idcliente'])) {
        $cliente_id = $idcliente['idcliente'];

        // Deletar endereços vinculados
        foreach ($enderecos as $end) {
            deletar_endereco($conexao, $end['idendentrega']);
        }

        // Deletar foto do cliente
        if (!empty($idcliente['foto'])) {
            $arquivo = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($idcliente['foto'], '/');
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
        <p><strong>Nome de Usuário:</strong> <?= htmlspecialchars($usuario['usuario'] ?? '') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email'] ?? '') ?></p>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($usuario['tipo'] ?? '') ?></p>
        <a href="Forms/formusuario.php?id=<?= $usuario_id ?>">Editar Usuário</a>
    </div>

    <?php if ($idcliente): ?>
        <div>
            <h2>Dados do Cliente</h2>
            <?php if (!empty($idcliente['foto'])): ?>
                <img src="<?= htmlspecialchars($idcliente['foto']) ?>" width="120" alt="Foto do Cliente"><br>
            <?php endif; ?>
            <p><strong>Nome:</strong> <?= htmlspecialchars($idcliente['nome'] ?? '') ?></p>
            <p><strong>Data de Aniversário:</strong> <?= htmlspecialchars($idcliente['data_ani'] ?? '') ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($idcliente['telefone'] ?? '') ?></p>
            <a href="Forms/formcliente.php?id=<?= $idcliente['idcliente'] ?>&idusuario=<?= $usuario_id ?>">Editar Cliente</a>
        </div>

        <div>
            <h2>Endereços</h2>
            <?php if (!empty($enderecos)): ?>
                <ul>
                    <?php foreach ($enderecos as $end): ?>
                        <li>
                            <?= htmlspecialchars($end['rua'] ?? '') ?>, 
                            <?= htmlspecialchars($end['numero'] ?? '') ?> - 
                            <?= htmlspecialchars($end['bairro'] ?? '') ?> 
                            (<?= htmlspecialchars($end['complemento'] ?? '') ?>)
                            <a href="Forms/formentrega.php?id=<?= $end['idendentrega'] ?>&cliente_id=<?= $idcliente['idcliente'] ?>">Editar</a>
                            <a href="perfil.php?delete_endereco=<?= $end['idendentrega'] ?>" onclick="return confirm('Deseja realmente excluir este endereço?');">Deletar</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum endereço cadastrado.</p>
            <?php endif; ?>
            <a href="Forms/formentrega.php?cliente_id=<?= $idcliente['idcliente'] ?>">Adicionar Endereço</a>
        </div>
    <?php else: ?>
        <div>
            <p>Você ainda não cadastrou seus dados de cliente.</p>
            <a href="Forms/formcliente.php?idusuario=<?= $usuario_id ?>">Cadastrar agora</a>
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
