<?php
require_once "../conexao.php";
require_once "../funcao.php";


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
$valor = $_POST['valor'] ?? 0;
$status_pagamento = $_POST['status_pagamento'] ?? '';
$data_pagamento = $_POST['data_pagamento'] ?? date('Y-m-d');


if ($id > 0) {

    atualizar_pagamento($conexao, $id, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento);
} else {

    cadastrar_pagamento($conexao, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento);

}


header("Location: ../Listar/listarpagamentos.php");
exit;
?>
