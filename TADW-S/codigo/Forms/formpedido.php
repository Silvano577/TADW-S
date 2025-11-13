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
$carrinho = buscar_carrinho_completo($conexao, $idcliente);
if (empty($carrinho)) {
    die("Seu carrinho está vazio. Adicione produtos antes de finalizar o pedido.");
}

// Taxa de entrega
$taxa_entrega = 15.00;
$total_com_taxa = calcular_total_carrinho($carrinho, $taxa_entrega);

// Buscar endereços do cliente
$enderecos = buscar_enderecos_cliente($conexao, $idcliente);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="../css/pedido.css">
</head>
<body>

<form action="../Salvar/salvarpedido.php" method="post">
    <input type="hidden" name="idcliente" value="<?= $idcliente ?>">

    <h1>Finalizar Pedido</h1>

    <label>Endereço de Entrega:</label><br>
    <select name="endentrega" required>
        <option value="">-- selecione --</option>
        <?php foreach ($enderecos as $end): ?>
            <option value="<?= $end['idendentrega'] ?>">
                <?= htmlspecialchars(
                    $end['rua'] . ', ' . $end['numero'] . 
                    (!empty($end['complemento']) ? ' (' . $end['complemento'] . ')' : '') .
                    ' - ' . $end['bairro']
                ) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <a href="formentrega.php?idcliente=<?= $idcliente ?>&origem=formpedido">+ Cadastrar novo endereço</a>
    <br><br>

    <p>Total dos produtos: R$ <?= number_format(calcular_total_carrinho($carrinho), 2, ',', '.') ?></p>
    <p>Taxa de entrega: R$ <?= number_format($taxa_entrega, 2, ',', '.') ?></p>
    <h3>Total a pagar: R$ <?= number_format($total_com_taxa, 2, ',', '.') ?></h3>

    <button type="submit">Criar Pedido</button>
    <a href="../carrinho.php" class="btn-voltar">Voltar ao Carrinho</a>
</form>

</body>
</html>
