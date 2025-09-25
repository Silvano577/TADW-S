<?php
function criar_usuario($conexao, $usuario, $email, $senha) {
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = 'cliente';
$sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, 'ssss', $usuario, $email, $senha_hash, $tipo);
$exec = mysqli_stmt_execute($comando);
if ($exec) {
$id = mysqli_insert_id($conexao);
mysqli_stmt_close($comando);
return $id; // retorna id do usuario criado
}
mysqli_stmt_close($comando);
return false;
}


function buscar_usuario($conexao, $idusuario = 0, $usuario = '') {
$sql = "SELECT * FROM usuario WHERE (? = 0 OR idusuario = ?) AND (? = '' OR usuario LIKE ?)";
$comando = mysqli_prepare($conexao, $sql);
$usuario_like = "%$usuario%";
mysqli_stmt_bind_param($comando, 'iiss', $idusuario, $idusuario, $usuario, $usuario_like);
mysqli_stmt_execute($comando);
$resultado = mysqli_stmt_get_result($comando);
$rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
mysqli_stmt_close($comando);
return $rows;
}


function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha = null) {
if ($senha !== null && $senha !== '') {
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, 'sssi', $usuario, $email, $senha_hash, $idusuario);
} else {
$sql = "UPDATE usuario SET usuario=?, email=? WHERE idusuario=?";
$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, 'ssi', $usuario, $email, $idusuario);
}
$res = mysqli_stmt_execute($comando);
mysqli_stmt_close($comando);
return $res;
}


function deletar_usuario($conexao, $idusuario) {
$sql = "DELETE FROM usuario WHERE idusuario = ?";
$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, 'i', $idusuario);
$res = mysqli_stmt_execute($comando);
mysqli_stmt_close($comando);
return $res;
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function vincular_usuario_cliente($conexao, $usuario_id, $cliente_id) {
    $sql = "UPDATE cliente SET usuario_id = ? WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ii', $usuario_id, $cliente_id);
    mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
}

function buscar_cliente_por_usuario($conexao, $usuario_id) {
    $sql = "SELECT * FROM cliente WHERE usuario_id = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $usuario_id);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function criar_cliente($conexao, $nome, $data_ani, $telefone, $foto, $idusuario) {
    $sql = "INSERT INTO cliente (nome, data_ani, telefone, foto, idusuario) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssi', $nome, $data_ani, $telefone, $foto, $idusuario);
    if (mysqli_stmt_execute($comando)) {
    $id = mysqli_insert_id($conexao);
    mysqli_stmt_close($comando);
    return $id; // retorna o id do cliente
}
    mysqli_stmt_close($comando);
    return false;
}
function buscar_cliente_por_usuario($conexao, $idusuario) {
    $sql = "SELECT * FROM cliente WHERE idusuario = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idusuario);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $rows;
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
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssi', $rua, $numero, $complemento, $bairro, $cliente);
    mysqli_stmt_execute($comando);
    $id = mysqli_insert_id($conexao);
    mysqli_stmt_close($comando);
    return $id;
}


function atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente) {
    $sql = "UPDATE endentrega SET rua=?, numero=?, complemento=?, bairro=?, cliente=? WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssii', $rua, $numero, $complemento, $bairro, $cliente, $id);
    $res = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $res;
}


function deletar_endereco($conexao, $id) {
    $sql = "DELETE FROM endentrega WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    $res = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $res;
}


function buscar_endereco($conexao, $id) {
    $sql = "SELECT * FROM endentrega WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $row = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($comando);
    return $row;
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
function criar_delivery($conexao, $idpedido) {
    $sql = "INSERT INTO delivery (pedido_id) VALUES (?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    if (!mysqli_stmt_execute($comando)) {
        return false;
    }
    $iddelivery = mysqli_insert_id($conexao);
    mysqli_stmt_close($comando);
    return $iddelivery;
}


// Atualizar status do delivery
function atualizar_delivery($conexao, $idpedido, $novo_status) {
    // Verifica se o status informado é válido
    $status_validos = ['atribuido', 'a_caminho', 'entregue', 'falha'];
    if (!in_array($novo_status, $status_validos)) {
        return false;
    }
    $sql = "UPDATE delivery SET status = ?, entregue_em = CASE WHEN ? = 'entregue' THEN NOW() ELSE entregue_em END
    WHERE pedido_id = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssi', $novo_status, $novo_status, $idpedido);
    $res = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $res;
}

// Buscar acompanhamento de delivery
function buscar_delivery($conexao, $pedido_id) {
    $sql = "SELECT d.iddelivery, d.status, d.iniciado_em, d.entregue_em, p.idpedido, p.total, c.nome AS cliente FROM delivery d
            INNER JOIN pedido p ON d.pedido_id = p.idpedido
            INNER JOIN cliente c ON p.cliente = c.idcliente
            WHERE d.pedido_id = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $pedido_id);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    return mysqli_fetch_assoc($resultado);
}

// Listar todos os deliveries
function listar_deliveries($conexao) {
    $sql = "SELECT d.iddelivery, d.status, d.iniciado_em, d.entregue_em, p.idpedido, c.nome AS cliente FROM delivery d
            INNER JOIN pedido p ON d.pedido_id = p.idpedido
            INNER JOIN cliente c ON p.cliente = c.idcliente
            ORDER BY d.iniciado_em DESC";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Criar (Registrar) Pedido
// Criar novo pedido


// Atualizar Pedido
function atualizar_pedido($conexao, $idpedido, $endentrega, $cliente, $idfeedback, $idpagamento, $valortotal) {
    $sql = "UPDATE pedido SET endentrega=?, cliente=?, idfeedback=?, idpagamento=?, valortotal=? WHERE idpedido=?";
    $comando = mysqli_prepare($conexao, $sql);

    // idfeedback pode ser null
    if ($idfeedback === null) {
        // Bind ignorando idfeedback (vai como NULL)
        mysqli_stmt_bind_param($comando, 'iiiidi', $endentrega, $cliente, $idfeedback, $idpagamento, $valortotal, $idpedido);
    } else {
        mysqli_stmt_bind_param($comando, 'iiiidi', $endentrega, $cliente, $idfeedback, $idpagamento, $valortotal, $idpedido);
    }

    $res = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $res;
}

// Buscar pedido por ID
function buscar_pedido($conexao, $idpedido) {
    $sql = "SELECT p.*, d.status AS status_delivery FROM pedido p LEFT JOIN delivery d ON d.pedido_id = p.idpedido
    WHERE p.idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $pedido = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return $pedido;
}


// Deletar pedido
function deletar_pedido($conexao, $idpedido) {
    $sql = "DELETE FROM pedido WHERE idpedido=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    $res = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $res;
}

// Listar pedidos
function listar_pedidos($conexao, $cliente_id = null) {
    if ($cliente_id) {
        $sql = "SELECT p.*, d.status AS status_delivery
                FROM pedido p
                LEFT JOIN delivery d ON d.pedido_id = p.idpedido
                WHERE p.cliente = ?
                ORDER BY p.idpedido DESC";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'i', $cliente_id);
    } else {
        $sql = "SELECT p.*, d.status AS status_delivery
                FROM pedido p
                LEFT JOIN delivery d ON d.pedido_id = p.idpedido
                ORDER BY p.idpedido DESC";
        $comando = mysqli_prepare($conexao, $sql);
    }
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $pedidos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $pedidos;
}

function listar_itens_pedido($conexao, $idpedido) {
    $sql = "SELECT pp.quantidade, pr.nome, pp.preco_unit AS preco
            FROM pedido_produto pp
            JOIN produto pr ON pr.idproduto = pp.idproduto
            WHERE pp.idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $itens;
}


?>