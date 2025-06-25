


<?php
function buscar_promocao($conexao, $idpromocao, $descricao, $foto) {
    $sql = "SELECT * FROM promocao WHERE idpromocao = ? OR descricao LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $descricao = "%$descricao%";
    mysqli_stmt_bind_param($comando, 'iss', $idpromocao, $descricao, $foto);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
function atualizar_promocao($conexao, $id, $descricao, $desconto, $vantagem, $foto) {    
    $sql = "UPDATE promocao SET descricao=?, preco=?, descricao=?, foto=? WHERE idpromocao=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sdssi', $descricao, $desconto, $vantagem, $foto, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}