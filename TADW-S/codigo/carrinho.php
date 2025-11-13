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
$carrinho = buscar_carrinho($conexao, $idcliente);

// Calcular total geral
$total_geral = 0;
foreach ($carrinho as $item) {
    $total_geral += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link rel="stylesheet" href="./css/car.css">
</head>
<body>

<h1>🛒 Seu Carrinho</h1>
<div class="carrinho-container">

<a href="cardapio.php" class="btn btn-voltar">← Voltar ao Cardápio</a>

<?php if (!empty($carrinho)): ?>
    <table id="tabela-carrinho">
        <tr>
            <th>Foto</th>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Total</th>
            <th>Ação</th>
        </tr>

        <?php foreach ($carrinho as $item): 
            $subtotal = $item['preco'] * $item['quantidade'];
        ?>
        <tr data-idcarrinho="<?= $item['idcarrinho'] ?>" data-preco="<?= $item['preco'] ?>">
            <td><img src="<?= htmlspecialchars($item['foto']) ?>" alt="" width="50"></td>
            <td><?= htmlspecialchars($item['nome_produto'] ?? '') ?></td>

            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
            <td>
                <button class="btn-qtd" data-acao="menos">−</button>
                <span class="quantidade"><?= $item['quantidade'] ?></span>
                <button class="btn-qtd" data-acao="mais">+</button>
            </td>
            <td class="subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            <td>
                <button class="btn btn-remover">Remover</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">
        Total Geral: <span id="total-geral">R$ <?= number_format($total_geral, 2, ',', '.') ?></span>
    </div>

    <form action="../Forms/formpedido.php" method="POST">
        <button type="submit" class="btn btn-finalizar">Finalizar Compra</button>
    </form>

<?php else: ?>
    <div class="vazio">Seu carrinho está vazio 😢</div>
<?php endif; ?>

</div>

<script src="./js/carrinho.js"></script>
</body>
</html>
