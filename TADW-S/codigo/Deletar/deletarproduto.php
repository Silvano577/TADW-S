<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Buscar o produto no banco para obter o caminho da foto
$produto = buscar_produto($conexao, $id, "");
if (!empty($produto)) {
    $foto = $produto[0]['foto']; // Ex: "fotos/abc123.jpg"

    // Caminho absoluto no servidor
    $arquivo = "/var/www/html/" . $foto;

    // Apagar a imagem se ela existir fisicamente
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}

// 2) Apagar o registro do banco
if (deletar_produto($conexao, $id)) {
    header("Location: ../Listar/listarproduto.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
