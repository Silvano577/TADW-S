<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

// Verifica login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    echo "Você precisa estar logado para visualizar o carrinho.";
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    echo "Cliente não encontrado.";
    exit;
}

// Buscar itens do carrinho
$sql = "SELECT c.idcarrinho, c.quantidade, p.nome AS nome_produto, p.preco, p.foto
        FROM carrinho c
        INNER JOIN produto p ON c.idproduto = p.idproduto
        WHERE c.idcliente = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $idcliente);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total_geral = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="./css/carrinho_frag.css">
</head>
<body>

<main>
    <div class="carrinho-container">
        <h2>🛒 Meu Carrinho</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($item = mysqli_fetch_assoc($result)): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total_geral += $subtotal;
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['foto']) ?>" alt="Produto"></td>
                        <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td class="qtd"><?= htmlspecialchars($item['quantidade']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <div class="rodape-carrinho">
                <form action="carrinho.php" method="POST">
                    <button type="submit" class="btn">Continuar Pedido</button>
                </form>
                <p class="total">Total: <strong>R$ <?= number_format($total_geral, 2, ',', '.') ?></strong></p>
            </div>
        <?php else: ?>
            <p class="vazio">Seu carrinho está vazio 😢</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
