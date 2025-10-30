<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

if (!$idcliente) {
    die("Cliente não encontrado.");
}

// Buscar produtos do carrinho
$sql_carrinho = "SELECT c.idproduto, c.quantidade, p.nome, p.preco 
                 FROM carrinho c 
                 JOIN produto p ON c.idproduto = p.idproduto 
                 WHERE c.idcliente = ?";
$stmt_carrinho = mysqli_prepare($conexao, $sql_carrinho);
mysqli_stmt_bind_param($stmt_carrinho, "i", $idcliente);
mysqli_stmt_execute($stmt_carrinho);
$res_carrinho = mysqli_stmt_get_result($stmt_carrinho);

if (mysqli_num_rows($res_carrinho) == 0) {
    die("Seu carrinho está vazio. Adicione produtos antes de finalizar o pedido.");
}

// Calcular total
$total = 0;
while ($item = mysqli_fetch_assoc($res_carrinho)) {
    $total += $item['preco'] * $item['quantidade'];
}

// Taxa de entrega
$taxa_entrega = 15.00;
$total_com_taxa = $total + $taxa_entrega;

// Buscar endereços do cliente
$sql_end = "SELECT idendentrega, rua, numero, complemento, bairro 
            FROM endentrega 
            WHERE idcliente = ?";
$stmt_end = mysqli_prepare($conexao, $sql_end);
mysqli_stmt_bind_param($stmt_end, "i", $idcliente);
mysqli_stmt_execute($stmt_end);
$res_end = mysqli_stmt_get_result($stmt_end);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1>Finalizar Pedido</h1>

<form action="../Salvar/salvarpedido.php" method="post">
    <input type="hidden" name="idcliente" value="<?= $idcliente ?>">
    
    <label>Endereço de Entrega:</label><br>
    <select name="endentrega" required>
        <option value="">-- selecione --</option>
        <?php while ($end = mysqli_fetch_assoc($res_end)): ?>
            <option value="<?= $end['idendentrega'] ?>">
                <?= htmlspecialchars($end['rua'] . ', ' . $end['numero'] . 
                    (!empty($end['complemento']) ? ' (' . $end['complemento'] . ')' : '') .
                    ' - ' . $end['bairro']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <a href="formentrega.php?idcliente=<?= $idcliente ?>&origem=formpedido">+ Cadastrar novo endereço</a>
    <br><br>

    <p>Total dos produtos: R$ <?= number_format($total, 2, ',', '.') ?></p>
    <p>Taxa de entrega: R$ <?= number_format($taxa_entrega, 2, ',', '.') ?></p>
    <h3>Total a pagar: R$ <?= number_format($total_com_taxa, 2, ',', '.') ?></h3>

    <button type="submit">Criar Pedido</button>
    <a href="../carrinho.php">Voltar ao Carrinho</a>
</form>
</body>
</html>
