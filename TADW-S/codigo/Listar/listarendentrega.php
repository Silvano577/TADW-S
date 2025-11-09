<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;

// Buscar usuário e cliente
$usuario = buscar_usuario($conexao, $usuario_id);
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);

// Caso não encontre o cliente vinculado ao usuário
if (!is_array($cliente) || empty($cliente)) {
    echo "<p style='text-align:center; color:red;'>Nenhum cliente associado a este usuário.</p>";
    echo "<a href='../homeCliente.php' class='btn-voltar'>Voltar</a>";
    exit;
}

$idcliente = $cliente['idcliente'];

// Buscar somente endereços do cliente logado
$enderecos = buscar_enderecos_por_cliente($conexao, $idcliente);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Endereços</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Meus Endereços</h1>

<?php if (empty($enderecos)): ?>
    <p style="text-align:center;">Você ainda não cadastrou nenhum endereço.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($enderecos as $endereco): ?>
            <div class="card">
                <h3><?= htmlspecialchars($endereco['rua']) ?></h3> 

                <p class="info"><strong>Numero:</strong> <?= htmlspecialchars($endereco['numero']) ?></p>
                
                <p class="info"><strong>ID:</strong> <?= $endereco['idendentrega'] ?></p>
                <p class="info"><strong>Bairro:</strong> <?= htmlspecialchars($endereco['bairro']) ?></p>
                <p class="info"><strong>Complemento:</strong> <?= htmlspecialchars($endereco['complemento']) ?></p>

                <a href="../Forms/formentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn">Editar</a>
                <a href="../Deletar/deletarendentrega.php?id=<?= $endereco['idendentrega'] ?>" class="btn-delete" onclick="return confirm('Deseja realmente excluir este endereço?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="../perfil.php" class="btn-voltar">Voltar</a>
</body>
</html>
