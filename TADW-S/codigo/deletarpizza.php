<?php
require_once "conexao.php";
require_once "funcao.php";

$id = intval($_GET['id']);

// 1) Busca a pizza para pegar o caminho da foto
$pizza = buscar_pizza($conexao, $id, "");
if (!empty($pizza)) {
    $foto = $pizza[0]['foto'];             // ex: "fotos/abc123.png"
    $arquivo = __DIR__ . '/' . $foto;      // caminho absoluto
    if (file_exists($arquivo)) {
        unlink($arquivo);                  // apaga o arquivo do disco
    }
}

// 2) Depois apaga o registro no banco
if (deletar_pizza($conexao, $id)) {
    header("Location: listarpizza.php");
} else {
    header("Location: erro.php");
}
exit;
?>
