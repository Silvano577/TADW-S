<?php


function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuario (usuario, email, senha) VALUES (?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sss', $usuario, $email, $senha_hash);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function buscar_usuario($conexao, $idusuario, $usuario) {
    $sql = "SELECT * FROM usuario WHERE idusuario = ? OR usuario LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $usuario = "%$usuario%";
    mysqli_stmt_bind_param($comando, 'is', $idusuario, $usuario);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

function atualizar_usuario($conexao, $idusuario , $usuario, $email , $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssi', $usuario, $email, $senha_hash, $idusuario);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function deletar_usuario($conexao, $idusuario) {
    $sql = "DELETE FROM usuario WHERE idusuario = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idusuario);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function listar_usuarios($conexao) {
    $sql = "SELECT * FROM usuario";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $usuarios;
}


function criar_cliente($conexao, $nome, $data_ani, $endereco, $telefone, $foto) {
    $sql = "INSERT INTO cliente (nome, data_ani, endereco, telefone, foto) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssss', $nome, $data_ani, $endereco, $telefone, $foto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function buscar_cliente($conexao, $idcliente, $nome) {
    $sql = "SELECT * FROM cliente WHERE idcliente = ? OR nome LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $nome = "%$nome%";
    mysqli_stmt_bind_param($comando, 'is', $idcliente, $nome);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

function atualizar_cliente($conexao, $idcliente, $nome, $data_ani, $endereco, $telefone, $foto) {
    $sql = "UPDATE cliente SET nome = ?, data_ani = ?, endereco = ?, telefone = ?, foto = ? WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssssi', $nome, $data_ani, $endereco, $telefone, $foto, $idcliente);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function deletar_cliente($conexao, $idcliente) {
    $sql = "DELETE FROM cliente WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function listar_clientes($conexao) {
    $sql = "SELECT * FROM cliente";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $clientes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $clientes;
}

function criar_pedido($conexao, $delivery, $cliente, $idfeedback, $idpagamento1) {
    $sql = "INSERT INTO pedido (delivery, cliente, idfeedback, idpagamento1) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'iiii', $delivery, $cliente, $idfeedback, $idpagamento1);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function buscar_pedido($conexao, $idpedido) {
    $sql = "SELECT * FROM pedido WHERE idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_assoc($resultado);
}

function atualizar_pedido($conexao, $id_pedido, $delivery, $cliente, $promocao ) {
    $sql = "UPDATE pedido SET delivery=?, cliente=?, promocao=? WHERE idpedido=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'iiii', $delivery, $cliente, $promocao, $id_pedido);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function deletar_pedido($conexao, $idpedido) {
    $sql = "DELETE FROM pedido WHERE idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function listar_pedidos($conexao) {
    $sql = "SELECT * FROM pedido";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $pedidos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $pedidos;
}

function registrar_feedback($conexao, $assunto, $comentario) {
    $sql = "INSERT INTO feedback (assunto, comentario) VALUES (?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ss', $assunto, $comentario);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function buscar_feedback($conexao, $idfeedback) {
    $sql = "SELECT * FROM feedback WHERE idfeedback = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idfeedback);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_assoc($resultado);
}

function atualizar_feedback($conexao, $id, $assunto, $comentario ) {
    $sql = "UPDATE feedback SET assunto=?, comentario=? WHERE idfeedback=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssi', $assunto, $comentario, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function deletar_feedback($conexao, $idfeedback) {
    $sql = "DELETE FROM feedback WHERE idfeedback = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idfeedback);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function listar_feedbacks($conexao) {
    $sql = "SELECT * FROM feedback";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $feedbacks = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $feedbacks;
} 

function criar_pizza($conexao, $variedade, $tamanho, $preco, $foto) {
    $sql = "INSERT INTO pizza (variedade, tamanho, preco, foto) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssds', $variedade, $tamanho, $preco, $foto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function buscar_pizza($conexao, $idpizza, $variedade) {
    $sql = "SELECT * FROM pizza WHERE idpizza = ? OR variedade LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $variedade = "%$variedade%";
    mysqli_stmt_bind_param($comando, 'is', $idpizza, $variedade);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
function atualizar_pizza($conexao, $id, $variedade, $tamanho, $preco, $foto) {    
    $sql = "UPDATE pizza SET variedade=?, tamanho=?, preco=?, foto=? WHERE idpizza=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssdsi', $variedade, $tamanho, $preco, $foto, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function deletar_pizza($conexao, $idpizza) {    $sql = "DELETE FROM pizza WHERE idpizza = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpizza);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function listar_pizzas($conexao) {
    $sql = "SELECT * FROM pizza";
    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);

    $lista_pizzas = [];
    while ($pizza = mysqli_fetch_assoc($resultado)) {
        $lista_pizzas[] = $pizza;
    }

    mysqli_stmt_close($comando);
    return $lista_pizzas;
}










function criar_promocao($conexao, $descricao, $preco, $foto) {
    $sql = "INSERT INTO promocao (descricao, preco, foto) VALUES (?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sds', $descricao, $preco, $foto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function buscar_promocao($conexao, $idpromocao, $descricao, $foto) {
    $sql = "SELECT * FROM promocao WHERE idpromocao = ? OR descricao LIKE ? OR foto LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);

    $descricao = "%$descricao%";
    $foto = "%$foto%";

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
function deletar_promocao($conexao, $idpromocao) {    
    $sql = "DELETE FROM promocao WHERE idpromocao = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpromocao);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function listar_promocoes($conexao) {    
    $sql = "SELECT * FROM promocao";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}















function cadastrar_bebida($conexao, $marca, $preco, $foto) {
    $sql = "INSERT INTO bebidas (marca, preco, foto) VALUES (?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sds', $marca, $preco, $foto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function buscar_bebida($conexao, $idbebidas, $marca) {
    $sql = "SELECT * FROM bebidas WHERE idbebidas = ? OR marca LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $marca = "%$marca%";
    mysqli_stmt_bind_param($comando, 'is', $idbebidas, $marca);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
function atualizar_bebida($conexao, $id, $marca, $preco, $foto) {    
    $sql = "UPDATE bebidas SET marca=?, preco=?, foto=? WHERE idbebidas=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sdsi', $marca, $preco, $foto, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function deletar_bebida($conexao, $idbebida) {    
    $sql = "DELETE FROM bebidas WHERE idbebidas = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idbebida);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function listar_bebidas($conexao) {    
    $sql = "SELECT * FROM bebidas";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

// Funções para manipulação da tabela 'delivery'
function criar_delivery($conexao, $endereco_entrega, $tempo_entrega) {
    $sql = "INSERT INTO delivery (endereco_entrega, tempo_entrega) VALUES (?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ss', $endereco_entrega, $tempo_entrega);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function buscar_delivery($conexao, $iddelivery) {
    $sql = "SELECT * FROM delivery WHERE iddelivery = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $iddelivery);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_assoc($resultado);
}
function cancelar_delivery($conexao, $iddelivery) {    
    $sql = "DELETE FROM delivery WHERE iddelivery = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $iddelivery);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function listar_deliveries($conexao) {    
    $sql = "SELECT * FROM delivery";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
function cadastrar_pagamento($conexao, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento) {
    $sql = "INSERT INTO pagamento (metodo_pagamento, valor, status_pagamento, data_pagamento) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sdss', $metodo_pagamento, $valor, $status_pagamento, $data_pagamento);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function listar_pagamentos($conexao) {
    $sql = "SELECT * FROM pagamento";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

?>