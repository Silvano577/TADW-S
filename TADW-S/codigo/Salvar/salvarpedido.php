<?php
include '../conexao.php';
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['idcliente'])) {
    echo "Você precisa estar logado para fazer um pedido.";
    exit;
}

$idcliente = $_SESSION['idcliente'];

// Recebe o tipo de entrega (cliente ou ponto fixo)
$tipo_entrega = $_POST['tipo_entrega'] ?? 'cliente';

// Define o endereço de entrega conforme a escolha
if ($tipo_entrega === 'cliente') {
    // Endereço normal do cliente
    if (empty($_POST['endentrega_cliente'])) {
        echo "Por favor, selecione um endereço de entrega.";
        exit;
    }
    $endentrega = intval($_POST['endentrega_cliente']);
} else {
    // Ponto fixo da pizzaria
    $sqlPonto = "SELECT idendentrega FROM endentrega WHERE tipo = 'ponto_fixo' LIMIT 1";
    $res = mysqli_query($conexao, $sqlPonto);
    $ponto = mysqli_fetch_assoc($res);

    if (!$ponto) {
        echo "Nenhum ponto fixo está cadastrado no sistema. Contate o administrador.";
        exit;
    }

    $endentrega = $ponto['identrega'];
}

// Cria o pedido no banco
$sqlPedido = "INSERT INTO pedido (idcliente, idendentrega, data_pedido, status_pedido)
              VALUES (?, ?, NOW(), 'Aguardando Pagamento')";
$stmt = mysqli_prepare($conexao, $sqlPedido);
mysqli_stmt_bind_param($stmt, "ii", $idcliente, $endentrega);

if (mysqli_stmt_execute($stmt)) {
    $idpedido = mysqli_insert_id($conexao);

    echo "<h3>Pedido criado com sucesso!</h3>";
    echo "<p>ID do Pedido: <strong>$idpedido</strong></p>";
    echo "<p>Status: Aguardando Pagamento</p>";
    echo "<p><a href='gerarpagamento.php?idpedido=$idpedido'>Clique aqui para gerar o pagamento</a></p>";
} else {
    echo "Erro ao salvar pedido: " . mysqli_error($conexao);
}
?>
