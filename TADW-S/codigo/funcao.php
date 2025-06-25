<?php



function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'cliente';
    $sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssss', $usuario, $email, $senha_hash, $tipo);
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


function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha) {
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
// Criar Produto
function criar_produto($conexao, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "INSERT INTO produto (nome, tipo, tamanho, preco, foto) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssds', $nome, $tipo, $tamanho, $preco, $foto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

// Buscar Produto por ID ou Nome
function buscar_produto($conexao, $idproduto, $nome) {
    $sql = "SELECT * FROM produto WHERE idproduto = ? OR nome LIKE ?";
    $comando = mysqli_prepare($conexao, $sql);
    $nome = "%$nome%";
    mysqli_stmt_bind_param($comando, 'is', $idproduto, $nome);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

// Atualizar Produto
function atualizar_produto($conexao, $id, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "UPDATE produto SET nome=?, tipo=?, tamanho=?, preco=?, foto=? WHERE idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssdsi', $nome, $tipo, $tamanho, $preco, $foto, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

// Deletar Produto
function deletar_produto($conexao, $idproduto) {
    $sql = "DELETE FROM produto WHERE idproduto = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idproduto);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

// Listar Produtos
function listar_produtos($conexao) {
    $sql = "SELECT * FROM produto";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);

    $lista_produtos = [];
    while ($produto = mysqli_fetch_assoc($resultado)) {
        $lista_produtos[] = $produto;
    }

    mysqli_stmt_close($comando);
    return $lista_produtos;
}



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