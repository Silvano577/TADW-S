<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Buscar o cliente no banco para obter o caminho da foto
$cliente = buscar_cliente($conexao, $id, "");
if (!empty($cliente)) {
    $foto = $cliente[0]['foto']; // ex: "fotos/abc123.jpg"

    // Caminho absoluto no servidor
    $arquivo = "/var/www/html/fotos/" . basename($foto);

    // Apagar a imagem se ela existir fisicamente
    if (!empty($foto) && file_exists($arquivo)) {
        unlink($arquivo);
    }
}

// 2) Apagar o registro do banco
if (deletar_cliente($conexao, $id)) {
    header("Location: ../Listar/listarcliente.php");
} else {
    header("Location: ../homeAdm.php");
}
exit;
?>
