<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

$idcliente = $_POST['idcliente'] ?? 0;
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
$valor = $_POST['valor'] ?? 0;
$data_pagamento = $_POST['data_pagamento'] ?? date('Y-m-d');

// Status fixo
$status_pagamento = 'pendente';

if ($idcliente > 0) {
    $idpagamento = registrar_pagamento($conexao, $idcliente, $metodo_pagamento, $valor, $data_pagamento);
    if ($idpagamento) {
        // Após criar o pagamento, podemos redirecionar para gerar o pedido
        header("Location: ../Forms/formpedido.php?idpagamento=$idpagamento");
        exit;
    }
}

// Se algo falhar
header("Location: ../carrinho.php?erro=pagamento");
exit;
?>
