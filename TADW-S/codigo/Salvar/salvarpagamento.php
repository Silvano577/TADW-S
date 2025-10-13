<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

$idcliente = intval($_POST['idcliente'] ?? 0);
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
$valor = floatval($_POST['valor'] ?? 0);
$data_pagamento = $_POST['data_pagamento'] ?? date('Y-m-d');
$status_pagamento = 'pendente';

// Validação mínima
if ($idcliente <= 0 || $valor <= 0 || empty($metodo_pagamento)) {
    die("Erro: dados do pagamento incompletos.");
}

// Registrar pagamento
$idpagamento = registrar_pagamento($conexao, $idcliente, $metodo_pagamento, $valor, $data_pagamento);
if ($idpagamento) {
    // Redireciona para criar o pedido
    header("Location: ../Forms/formpedido.php?idpagamento=$idpagamento");
    exit;
}

// Falha
header("Location: ../carrinho.php?erro=pagamento");
exit;
?>
