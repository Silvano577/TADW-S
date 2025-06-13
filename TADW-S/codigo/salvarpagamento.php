<?php

require_once "conexao.php";
require_once "funcao.php";

$metodo_pagamento = $_POST['metodo_pagamento'];
$valor = $_POST['valor'];
$status_pagamento = $_POST['status_pagamento'];
$data_pagamento = $_POST['data_pagamento'];



$id=0;
    cadastrar_pagamento($conexao, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento);

header("Location: formpagamento.php");
?>