<?php
require_once "../protege.php";
require_once "../conexao.php";
require_once "../funcao.php";

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$cliente = $cliente[0] ?? null;

if (!$cliente) {
    echo "<p>Erro: cliente não encontrado. Cadastre seus dados no perfil antes de finalizar o pedido.</p>";
    exit;
}

// Valor do carrinho vindo da sessão
$valor = $_SESSION['total_compra'] ?? 0;

// Dados iniciais do pagamento
$metodo_pagamento = "";
$status_pagamento = "pendente"; // sempre pendente
$data_pagamento = date('Y-m-d');
$botao = "Gerar Pagamento";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $botao ?></title>
    <link rel="stylesheet" href="../css/lista_padrao.css">
</head>
<body>
<h1><?= $botao ?></h1>

<form action="../Salvar/salvarpagamento.php" method="post" class="form-padrao">
    <input type="hidden" name="idcliente" value="<?= $cliente['idcliente'] ?>">

    <label>Forma de pagamento</label><br>
    <select name="metodo_pagamento" class="input-pesquisa" required>
        <option value="">-- selecione --</option>
        <option value="pix">PIX</option>
        <option value="cartao debito">Cartão débito</option>
        <option value="cartao credito">Cartão crédito</option>
        <option value="dinheiro">Dinheiro</option>
    </select>
    <br><br>

    <label>Valor Total</label><br>
    <input type="number" step="0.01" name="valor" class="input-pesquisa" value="<?= htmlspecialchars($valor) ?>" readonly>
    <br><br>

    <input type="hidden" name="status_pagamento" value="pendente">
    <input type="hidden" name="data_pagamento" value="<?= $data_pagamento ?>">

    <button type="submit" class="btn">💰 <?= $botao ?></button>
    <a href="../carrinho.php">Voltar ao Carrinho</a>
</form>
</body>
</html>
