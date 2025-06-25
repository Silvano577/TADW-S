<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Busca a pizza para pegar o caminho da foto
$pizza = buscar_pizza($conexao, $id, "");
if (!empty($pizza)) {
    $foto = $pizza[0]['foto'];  // exemplo: "fotos/abc123.png"

    // Caminho absoluto da foto
    $arquivo = "/var/www/html/" . $foto;

    // Se o arquivo existir, apaga
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}

// 2) Depois apaga o registro do banco
if (deletar_pizza($conexao, $id)) {
    header("Location: ../Listar/listarpizza.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
