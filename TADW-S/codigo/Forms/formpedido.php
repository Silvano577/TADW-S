<?php
require_once "../protege.php"; 
require_once "../conexao.php";
require_once "../funcao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Verifica tipo de usuário
$isAdmin = ($_SESSION['tipo'] === 'adm');

// Se for edição
if (isset($_GET['id'])) {
    $idpedido = intval($_GET['id']);
    $pedido = buscar_pedido($conexao, $idpedido);
    $botao = "Atualizar";

    $clienteId = $pedido['cliente'];
    $endentregaId = $pedido['endentrega'];
    $idpagamento = $pedido['idpagamento'];
} else {
    $idpedido = 0;
    $botao = "Cadastrar";
    $clienteId = "";
    $endentregaId = "";
    $idpagamento = "";
}

// Lista produtos do cardápio
$produtos = listar_produtos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $botao; ?> Pedido</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h1><?php echo $botao; ?> Pedido</h1>

<form action="../Salvar/salvarpedido.php?id=<?php echo $idpedido; ?>" method="post">
    Cliente (ID):<br>
    <input type="number" name="cliente" value="<?php echo htmlspecialchars($clienteId); ?>" required><br><br>

    Endereço de Entrega (ID):<br>
    <input type="number" name="endentrega" value="<?php echo htmlspecialchars($endentregaId); ?>" required><br><br>

    Pagamento (ID) (opcional, deixe 0 se ainda não existe):<br>
    <input type="number" name="idpagamento" value="<?php echo htmlspecialchars($idpagamento); ?>"><br><br>

    <h3>Produtos</h3>
    <?php foreach ($produtos as $p): ?>
        <?php 
        $id = $p['idproduto'];
        $nome = htmlspecialchars($p['nome']);
        $preco = number_format($p['preco'], 2, ',', '.'); 
        ?>
        <input type="checkbox" name="idproduto[]" value="<?php echo $id; ?>" class="produto-check" onchange="calcular()">
        <?php echo $nome; ?> - R$ <span id="preco_<?php echo $id; ?>"><?php echo $preco; ?></span>
        <input type="number" name="quantidade[<?php echo $id; ?>]" id="quantidade_<?php echo $id; ?>" min="1" value="1" onchange="calcular()">
        <br>
    <?php endforeach; ?>

    <br>
    Valor Total (R$):<br>
    <input type="text" id="valor_total" disabled><br><br>

    <input type="submit" value="<?php echo $botao; ?>">
</form>

<form action="<?php echo $isAdmin ? '../homeAdm.php' : '../home.php'; ?>" method="get">
    <button type="submit">Voltar</button>
</form>

<script src="../js/calcular_total.js"></script>
</body>
</html>
