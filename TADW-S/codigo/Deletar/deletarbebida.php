<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Busca a bebida para pegar o caminho da foto
$bebida = buscar_bebida($conexao, $id, "");
if (!empty($bebida)) {
    $foto = $bebida[0]['foto'];  // Exemplo: "fotos/abc123.png"

    // Caminho absoluto da foto
    $arquivo = "/var/www/html/" . $foto;

    // Se o arquivo existir, apaga
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}

// 2) Apaga o registro no banco
if (deletar_bebida($conexao, $id)) {
    header("Location: ../Listar/listarbebida.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
