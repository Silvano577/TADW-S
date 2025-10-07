<?php

// funcao.php - versões limpas das funções (substitua seu arquivo atual por este)

// ---------- USUÁRIO ----------
function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'cliente';
    $sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $email, $senha_hash, $tipo);
    $exec = mysqli_stmt_execute($stmt);
    if ($exec) {
        $id = mysqli_insert_id($conexao);
        mysqli_stmt_close($stmt);
        return $id;
    }
    mysqli_stmt_close($stmt);
    return false;
}

function buscar_usuario($conexao, $idusuario = 0, $usuario = '') {
    if ($idusuario > 0) {
        $sql = "SELECT * FROM usuario WHERE idusuario = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $idusuario);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    } elseif ($usuario !== '') {
        $sql = "SELECT * FROM usuario WHERE usuario LIKE ?";
        $like = "%{$usuario}%";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 's', $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    } else {
        $sql = "SELECT * FROM usuario";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    }
}

function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha = null) {
    if ($senha !== null && $senha !== '') {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $usuario, $email, $senha_hash, $idusuario);
    } else {
        $sql = "UPDATE usuario SET usuario=?, email=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $usuario, $email, $idusuario);
    }
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function deletar_usuario($conexao, $idusuario) {
    $sql = "DELETE FROM usuario WHERE idusuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idusuario);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function listar_usuarios($conexao) {
    $sql = "SELECT * FROM usuario";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

// ---------- CLIENTE ----------
function criar_cliente($conexao, $nome, $data_ani, $telefone, $foto, $idusuario) {
    $sql = "INSERT INTO cliente (nome, data_ani, telefone, foto, idusuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $data_ani, $telefone, $foto, $idusuario);
    if (mysqli_stmt_execute($stmt)) {
        $id = mysqli_insert_id($conexao);
        mysqli_stmt_close($stmt);
        return $id;
    }
    mysqli_stmt_close($stmt);
    return false;
}

function buscar_cliente($conexao, $idcliente = 0, $nome = '') {
    if ($idcliente > 0) {
        $sql = "SELECT * FROM cliente WHERE idcliente = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $idcliente);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $row;
    } elseif ($nome !== '') {
        $sql = "SELECT * FROM cliente WHERE nome LIKE ?";
        $like = "%{$nome}%";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 's', $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        $sql = "SELECT * FROM cliente";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $rows;
    }
}

// **Apenas uma** definição: buscar_cliente_por_usuario
function buscar_cliente_por_usuario($conexao, $idusuario) {
    $sql = "SELECT * FROM cliente WHERE idusuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idusuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function atualizar_cliente($conexao, $idcliente, $nome, $data_ani, $telefone, $foto) {
    $sql = "UPDATE cliente SET nome = ?, data_ani = ?, telefone = ?, foto = ? WHERE idcliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $data_ani, $telefone, $foto, $idcliente);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function deletar_cliente($conexao, $idcliente) {
    $sql = "DELETE FROM cliente WHERE idcliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function listar_clientes($conexao) {
    $sql = "SELECT * FROM cliente";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
    //////////////////////////////////////////////////////////////////////////////////////////////////
}
function registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $cliente) {
    $sql = "INSERT INTO endentrega (rua, numero, complemento, bairro, cliente) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $rua, $numero, $complemento, $bairro, $cliente);
    mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmt);
    return $id;
}

function atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $cliente) {
    $sql = "UPDATE endentrega SET rua=?, numero=?, complemento=?, bairro=?, cliente=? WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $rua, $numero, $complemento, $bairro, $cliente, $id);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function deletar_endereco($conexao, $id) {
    $sql = "DELETE FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exec;
}

function buscar_endereco($conexao, $id) {
    $sql = "SELECT * FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

function listar_enderecos($conexao) {
    $sql = "SELECT * FROM endentrega";
    $res = mysqli_query($conexao, $sql);
    $enderecos = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $enderecos[] = $row;
    }
    return $enderecos;
}



////////////////////////////////////////////////////////////////////////////

// Buscar cliente pelo idusuario


// Listar endereços por cliente
function listar_enderecos_por_cliente($conexao, $idcliente) {
    $sql = "SELECT * FROM endentrega WHERE cliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    mysqli_stmt_close($comando);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}





///////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

// adiciona no seu arquivo de funções (funcao.php ou funcoes.php)
function pesquisarProdutoId($conexao, $idproduto) {
    $sql = "SELECT idproduto, nome, tipo, preco, tamanho, foto FROM produto WHERE idproduto = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idproduto);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($resultado);
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function registrar_pagamento($conexao, $cliente, $status, $valortotal) {
    // Define status padrão, se vier vazio
    if (empty($status)) {
        $status = 'pendente';
    }

    $sql = "INSERT INTO pagamento (cliente, status, valortotal, data_pagamento)
            VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "isd", $cliente, $status, $valortotal);

    $sucesso = mysqli_stmt_execute($stmt);
    if (!$sucesso) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $idpagamento = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmt);
    return $idpagamento; // retorna o ID do pagamento criado
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
    // Inserir ou atualizar quantidade do item no pedido
    $sql = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade)";

    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'iii', $idpedido, $idproduto, $quantidade);

    $sucesso = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);

    // Se deu certo, atualizar o valor total do pedido
    if ($sucesso) {
        atualizar_total_pedido($conexao, $idpedido);
    }

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


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Criar delivery vinculado ao pedido

function salvarPedido($conexao, $endentrega, $cliente, $idpagamento, $valortotal, $idfeedback = null) {
    $status = 'pendente'; // Status inicial padrão

    $sql = "INSERT INTO pedido (endentrega, cliente, idpagamento, valortotal, idfeedback, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) return false;

    // Correção dos tipos — d = double (para o valor total)
    mysqli_stmt_bind_param($stmt, "iiidis", $endentrega, $cliente, $idpagamento, $valortotal, $idfeedback, $status);

    $sucesso = mysqli_stmt_execute($stmt);
    if (!$sucesso) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $idpedido = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmt);
    return $idpedido; // retorna o ID do pedido criado
}


function criar_delivery($conexao, $idpedido) {
    $status = 'em preparo'; // Status inicial padrão da entrega

    $sql = "INSERT INTO delivery (idpedido, status, data_envio)
            VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "is", $idpedido, $status);

    $sucesso = mysqli_stmt_execute($stmt);
    if (!$sucesso) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $iddelivery = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmt);
    return $iddelivery; // retorna o ID da entrega criada
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
function atualizar_total_pedido($conexao, $idpedido) {
    // 1. Calcular o valor total somando (preco * quantidade)
    $sql = "SELECT SUM(p.preco * pp.quantidade) AS total
            FROM pedido_produto pp
            INNER JOIN produto p ON p.idproduto = pp.idproduto
            WHERE pp.idpedido = ?";
    
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idpedido);
    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $dados = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($comando);

    $total = $dados['total'] ?? 0.00;

    // 2. Atualizar o pedido com o novo valor
    $sql_update = "UPDATE pedido SET valortotal = ? WHERE idpedido = ?";
    $comando_update = mysqli_prepare($conexao, $sql_update);
    mysqli_stmt_bind_param($comando_update, 'di', $total, $idpedido);

    $sucesso = mysqli_stmt_execute($comando_update);
    mysqli_stmt_close($comando_update);

    return $sucesso ? $total : false;
}

function criarPedidoCompleto($conexao, $cliente, $endentrega, $itens, $valortotal, $taxaEntrega = 5.00) {
    // Inicia a transação
    mysqli_begin_transaction($conexao);

    try {
        // --- 1️⃣ Criar pagamento ---
        $status_pagamento = 'pendente';
        $sqlPagamento = "INSERT INTO pagamento (cliente, status, valortotal, data_pagamento) 
                         VALUES (?, ?, ?, NOW())";
        $stmtPag = mysqli_prepare($conexao, $sqlPagamento);
        if (!$stmtPag) throw new Exception("Erro ao preparar pagamento.");
        mysqli_stmt_bind_param($stmtPag, "isd", $cliente, $status_pagamento, $valortotal);
        if (!mysqli_stmt_execute($stmtPag)) throw new Exception("Erro ao registrar pagamento.");
        $idpagamento = mysqli_insert_id($conexao);
        mysqli_stmt_close($stmtPag);

        // --- 2️⃣ Criar pedido ---
        $status_pedido = 'pendente';
        $sqlPedido = "INSERT INTO pedido (endentrega, cliente, idpagamento, valortotal, idfeedback, status)
                      VALUES (?, ?, ?, ?, NULL, ?)";
        $stmtPed = mysqli_prepare($conexao, $sqlPedido);
        if (!$stmtPed) throw new Exception("Erro ao preparar pedido.");
        mysqli_stmt_bind_param($stmtPed, "iiids", $endentrega, $cliente, $idpagamento, $valortotal, $status_pedido);
        if (!mysqli_stmt_execute($stmtPed)) throw new Exception("Erro ao salvar pedido.");
        $idpedido = mysqli_insert_id($conexao);
        mysqli_stmt_close($stmtPed);

        // --- 3️⃣ Inserir produtos no pedido ---
        $sqlItem = "INSERT INTO pedido_produto (idpedido, idproduto, quantidade, preco_unit) 
                    VALUES (?, ?, ?, ?)";
        $stmtItem = mysqli_prepare($conexao, $sqlItem);
        if (!$stmtItem) throw new Exception("Erro ao preparar itens do pedido.");

        foreach ($itens as $item) {
            mysqli_stmt_bind_param($stmtItem, "iiid", 
                $idpedido,
                $item['idproduto'],
                $item['quantidade'],
                $item['preco_unit']
            );
            if (!mysqli_stmt_execute($stmtItem)) {
                throw new Exception("Erro ao inserir item do pedido.");
            }
        }
        mysqli_stmt_close($stmtItem);

        // --- 4️⃣ Criar delivery ---
        $status_delivery = 'em preparo';
        $sqlDelivery = "INSERT INTO delivery (idpedido, status, data_envio) VALUES (?, ?, NOW())";
        $stmtDel = mysqli_prepare($conexao, $sqlDelivery);
        if (!$stmtDel) throw new Exception("Erro ao preparar delivery.");
        mysqli_stmt_bind_param($stmtDel, "is", $idpedido, $status_delivery);
        if (!mysqli_stmt_execute($stmtDel)) throw new Exception("Erro ao criar delivery.");
        $iddelivery = mysqli_insert_id($conexao);
        mysqli_stmt_close($stmtDel);

        // --- 5️⃣ Concluir transação ---
        mysqli_commit($conexao);

        // --- 6️⃣ Retornar dados completos ---
        return [
            'sucesso' => true,
            'idpedido' => $idpedido,
            'idpagamento' => $idpagamento,
            'iddelivery' => $iddelivery,
            'valortotal' => $valortotal
        ];

    } catch (Exception $e) {
        mysqli_rollback($conexao); // desfaz tudo se algo falhar
        return [
            'sucesso' => false,
            'erro' => $e->getMessage()
        ];
    }
}


?>