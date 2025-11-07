<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);


$cliente = buscar_cliente($conexao, $id, "");
if (!empty($cliente)) {
    $foto = $cliente[0]['foto']; 
    $arquivo = "/var/www/html/fotos/" . basename($foto);
    if (!empty($foto) && file_exists($arquivo)) {
        unlink($arquivo);
    }
}

if (deletar_cliente($conexao, $id)) {
    header("Location: ../Listar/listarcliente.php");
} else {
    header("Location: ../homeAdm.php");
}
exit;
?>
