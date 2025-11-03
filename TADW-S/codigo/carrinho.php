<?php
session_start();
require_once "conexao.php";
require_once "funcao.php";

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    die("Cliente não encontrado.");
}

// Buscar itens do carrinho
$sql = "SELECT c.idcarrinho, c.quantidade, 
               p.nome AS nome_produto, p.preco, p.foto
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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link rel="stylesheet" href="css/carrinho.css">
</head>
<body>

<h1>🛒 Seu Carrinho</h1>
<div class="carrinho-container">

<?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <tr>
            <th>Foto</th>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Total</th>
            <th>Ação</th>
        </tr>

        <?php while ($item = mysqli_fetch_assoc($result)): 
            $subtotal = $item['preco'] * $item['quantidade'];
            $total_geral += $subtotal;
        ?>
        <tr>
           <td> <img src="<?php echo htmlspecialchars($item['foto']); ?>" alt="" width="50"> </td>
            <td><?php echo htmlspecialchars($item['nome_produto']); ?></td>
            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
            <td>
                <form action="../Forms/atualizar_carrinho.php" method="POST" class="form-qtd">
                    <input type="hidden" name="idcarrinho" value="<?php echo $item['idcarrinho']; ?>">
                    <button type="submit" name="acao" value="menos" class="btn-qtd">−</button>
                    <span><?php echo $item['quantidade']; ?></span>
                    <button type="submit" name="acao" value="mais" class="btn-qtd">+</button>
                </form>
            </td>
            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
            <td>
                <form action="../Deletar/remover.php" method="POST" style="display:inline;">
                    <input type="hidden" name="idcarrinho" value="<?php echo $item['idcarrinho']; ?>">
                    <button type="submit" class="btn btn-remover">Remover</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="total">
        Total Geral: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
    </div>

    <form action="../Forms/formpedido.php" method="POST">
        <button type="submit" class="btn btn-finalizar">Finalizar Compra</button>
    </form>

<?php else: ?>
    <div class="vazio">Seu carrinho está vazio 😢</div>
<?php endif; ?>

</div>
</body>
</html>
