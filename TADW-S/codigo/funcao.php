<?php

function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'cliente';
    $sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $email, $senha_hash, $tipo);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
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


function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha = null) {
    if ($senha !== null && $senha !== '') {
        // Atualiza senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $usuario, $email, $senha_hash, $idusuario);
    } else {
        // Mantém a senha antiga
        $sql = "UPDATE usuario SET usuario=?, email=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $usuario, $email, $idusuario);
    }
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
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
}///////////////////////////////////////////////////////////////////////////////////////////////////////////////

function criar_cliente($conexao, $nome, $data_ani, $telefone, $foto) {
    $sql = "INSERT INTO cliente (nome, data_ani, telefone, foto) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssss', $nome, $data_ani, $telefone, $foto);
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

function atualizar_cliente($conexao, $idcliente, $nome, $data_ani, $telefone, $foto) {
    $sql = "UPDATE cliente SET nome = ?, data_ani = ?, telefone = ?, foto = ? WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssi', $nome, $data_ani, $telefone, $foto, $idcliente);
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


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function registrar_feedback($conexao, $assunto, $comentario) {
    $sql = "INSERT INTO feedback (assunto, comentario) VALUES (?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "ss", $assunto, $comentario);
    mysqli_stmt_execute($comando);
}

function buscar_feedback($conexao, $id) {
    $sql = "SELECT * FROM feedback WHERE idfeedback = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $id);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);

    return mysqli_fetch_assoc($resultado); // retorna só 1 registro
}




function atualizar_feedback($conexao, $id, $assunto, $comentario) {
    $sql = "UPDATE feedback SET assunto = ?, comentario = ? WHERE idfeedback = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "ssi", $assunto, $comentario, $id);
    mysqli_stmt_execute($comando);
}

function deletar_feedback($conexao, $idfeedback) {
    $sql = "DELETE FROM feedback WHERE idfeedback = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idfeedback);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function listar_feedback($conexao) {
    $sql = "SELECT idfeedback, assunto, comentario FROM feedback ORDER BY idfeedback DESC";
    $resultado = mysqli_query($conexao, $sql);

    $feedbacks = [];
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $feedbacks[] = $linha;
    }

    return $feedbacks;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cadastrar_pagamento($conexao, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento) {
    $sql = "INSERT INTO pagamento (metodo_pagamento, valor, status_pagamento, data_pagamento) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    // tipos: s = string (metodo), d = double (valor), s = string (status), s = string (data YYYY-MM-DD)
    mysqli_stmt_bind_param($comando, 'sdss', $metodo_pagamento, $valor, $status_pagamento, $data_pagamento);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}

function atualizar_pagamento($conexao, $id, $metodo_pagamento, $valor, $status_pagamento, $data_pagamento) {
    $sql = "UPDATE pagamento
            SET metodo_pagamento = ?, valor = ?, status_pagamento = ?, data_pagamento = ?
            WHERE idpagamento = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sdssi', $metodo_pagamento, $valor, $status_pagamento, $data_pagamento, $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}
function buscar_pagamento($conexao, $id) {
    $sql = "SELECT * FROM pagamento WHERE idpagamento = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $pagamento = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($comando);
    return $pagamento ?: null;
}


// Listar pagamentos — aceita filtro opcional por método
function listar_pagamentos($conexao, $metodo = null) {
    if ($metodo === null || $metodo === '') {
        $sql = "SELECT * FROM pagamento ORDER BY data_pagamento DESC, idpagamento DESC";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_execute($comando);
    } else {
        $sql = "SELECT * FROM pagamento WHERE metodo_pagamento = ? ORDER BY data_pagamento DESC, idpagamento DESC";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 's', $metodo);
        mysqli_stmt_execute($comando);
    }
    $resultado = mysqli_stmt_get_result($comando);
    $pagamentos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $pagamentos;
}

function deletar_pagamento($conexao, $id) {
    $sql = "DELETE FROM pagamento WHERE idpagamento = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    $resultado = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $resultado;
}


// Função auxiliar para obter resumo (count e soma) por método (opcional)
function resumo_pagamentos($conexao, $metodo = null) {
    if ($metodo === null || $metodo === '') {
        $sql = "SELECT COUNT(*) AS qtd, SUM(valor) AS total FROM pagamento";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_execute($comando);
    } else {
        $sql = "SELECT COUNT(*) AS qtd, SUM(valor) AS total FROM pagamento WHERE metodo_pagamento = ?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 's', $metodo);
        mysqli_stmt_execute($comando);
    }
    $resultado = mysqli_stmt_get_result($comando);
    $row = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($comando);
    return $row ?: ['qtd' => 0, 'total' => 0.00];
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

function salvar_venda($conexao, $idpedido, $idproduto, $quantidade) {
    $sql = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade) VALUES (?, ?, ?) 
    ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade)";

    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'iii', $idpedido, $idproduto, $quantidade);

    $sucesso = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);

    return $sucesso;
}
function listar_venda($conexao) {
    $sql = "SELECT * FROM pedido_produto";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $vendas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $vendas;
}

///////////////////////////////////////////////////////////////////////////////////////////////
function registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $cliente) {
    $sql = "INSERT INTO endentrega (rua, numero, complemento, bairro, cliente) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $rua, $numero, $complemento, $bairro, $cliente);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($conexao);
}

function atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente) {
    $sql = "UPDATE endentrega SET rua=?, numero=?, complemento=?, bairro=?, cliente=? WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $rua, $numero, $complemento, $bairro, $cliente, $id);
    return mysqli_stmt_execute($stmt);
}

function deletar_endereco($conexao, $id) {
    $sql = "DELETE FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    return mysqli_stmt_execute($stmt);
}

function buscar_endereco($conexao, $id) {
    $sql = "SELECT * FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultado);
}

function listar_enderecos($conexao) {
    $sql = "SELECT * FROM endentrega";
    $resultado = mysqli_query($conexao, $sql);
    $enderecos = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $enderecos[] = $row;
    }
    return $enderecos;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Criar delivery vinculado ao pedido
function criar_delivery($conexao, $pedido_id) {
    $sql = "INSERT INTO delivery (pedido_id, status) VALUES (?, 'atribuido')";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pedido_id);
    return mysqli_stmt_execute($stmt);
}

// Atualizar status do delivery
function atualizar_delivery($conexao, $pedido_id, $status) {
    $sql = "UPDATE delivery SET status = ?, 
               entregue_em = CASE WHEN ? = 'entregue' THEN NOW() ELSE entregue_em END
            WHERE pedido_id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $status, $status, $pedido_id);
    return mysqli_stmt_execute($stmt);
}

// Buscar acompanhamento de delivery
function buscar_delivery($conexao, $pedido_id) {
    $sql = "SELECT d.iddelivery, d.status, d.iniciado_em, d.entregue_em,
                   p.idpedido, p.total, c.nome AS cliente
            FROM delivery d
            INNER JOIN pedido p ON d.pedido_id = p.idpedido
            INNER JOIN cliente c ON p.cliente = c.idcliente
            WHERE d.pedido_id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pedido_id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultado);
}

// Listar todos os deliveries
function listar_deliveries($conexao) {
    $sql = "SELECT d.iddelivery, d.status, d.iniciado_em, d.entregue_em,
                   p.idpedido, c.nome AS cliente
            FROM delivery d
            INNER JOIN pedido p ON d.pedido_id = p.idpedido
            INNER JOIN cliente c ON p.cliente = c.idcliente
            ORDER BY d.iniciado_em DESC";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}


?>