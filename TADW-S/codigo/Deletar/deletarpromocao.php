<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = intval($_GET['id']);

// 1) Busca a promocao para pegar o caminho da foto
$promocao = buscar_promocao($conexao, $id, "","");
if (!empty($promocao)) {
    $foto = $promocao[0]['foto'];  // exemplo: "fotos/abc123.png"

    // Caminho absoluto da foto
    $arquivo = "/var/www/html/" . $foto;

    // Se o arquivo existir, apaga
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}

// 2) Depois apaga o registro do banco
if (deletar_promocao($conexao, $id)) {
    header("Location: ../Listar/listarpromocao.php");
} else {
    header("Location: ../home.php");
}
exit;
?>
