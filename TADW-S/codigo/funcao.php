<?php
// funcao.php - versão padronizada e limpa

// ======================= USUÁRIO =======================
function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'cliente';
    $sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssss', $usuario, $email, $senha_hash, $tipo);
    $ok = mysqli_stmt_execute($comando);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($comando);
    return $id;
}

function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha = null) {
    if ($senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'sssi', $usuario, $email, $senha_hash, $idusuario);
    } else {
        $sql = "UPDATE usuario SET usuario=?, email=? WHERE idusuario=?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'ssi', $usuario, $email, $idusuario);
    }
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function deletar_usuario($conexao, $idusuario) {
    $sql = "DELETE FROM usuario WHERE idusuario=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idusuario);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function listar_usuarios($conexao) {
    $sql = "SELECT * FROM usuario";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $usuarios = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $usuarios;
}

function buscar_usuario($conexao, $idusuario = 0, $usuario = '') {
    if ($idusuario > 0) {
        $sql = "SELECT * FROM usuario WHERE idusuario=?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'i', $idusuario);
    } elseif ($usuario) {
        $sql = "SELECT * FROM usuario WHERE usuario LIKE ?";
        $comando = mysqli_prepare($conexao, $sql);
        $like = "%$usuario%";
        mysqli_stmt_bind_param($comando, 's', $like);
    } else {
        $sql = "SELECT * FROM usuario";
        $comando = mysqli_prepare($conexao, $sql);
    }
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $rows;
}

// ======================= CLIENTE =======================
function criar_cliente($conexao, $nome, $data_ani, $telefone, $foto, $idusuario) {
    $sql = "INSERT INTO cliente (nome, data_ani, telefone, foto, idusuario) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssi', $nome, $data_ani, $telefone, $foto, $idusuario);
    $ok = mysqli_stmt_execute($comando);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($comando);
    return $id;
}

function atualizar_cliente($conexao, $idcliente, $nome, $data_ani, $telefone, $foto = null) {
    if ($foto) {
        $sql = "UPDATE cliente SET nome=?, data_ani=?, telefone=?, foto=? WHERE idcliente=?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'ssssi', $nome, $data_ani, $telefone, $foto, $idcliente);
    } else {
        $sql = "UPDATE cliente SET nome=?, data_ani=?, telefone=? WHERE idcliente=?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'sssi', $nome, $data_ani, $telefone, $idcliente);
    }
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function deletar_cliente($conexao, $idcliente) {
    $sql = "DELETE FROM cliente WHERE idcliente=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function listar_clientes($conexao) {
    $sql = "SELECT * FROM cliente";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $clientes = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $clientes;
}

function buscar_cliente_por_usuario($conexao, $idusuario) {
    $sql = "SELECT * FROM cliente WHERE idusuario=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idusuario);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $cliente = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return $cliente ?: null;
}

function buscar_cliente_por_id($conexao, $idcliente) {
    $sql = "SELECT * FROM cliente WHERE idcliente=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $cliente = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return $cliente ?: null;
}

// ======================= ENDEREÇOS =======================
function registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $idcliente) {
    $sql = "INSERT INTO endentrega (rua, numero, complemento, bairro, idcliente) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssi', $rua, $numero, $complemento, $bairro, $idcliente);
    $ok = mysqli_stmt_execute($comando);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($comando);
    return $id;
}

function atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $idcliente) {
    $sql = "UPDATE endentrega SET rua=?, numero=?, complemento=?, bairro=?, idcliente=? WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssii', $rua, $numero, $complemento, $bairro, $idcliente, $id);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function deletar_endereco($conexao, $idendentrega) {
    $sql = "DELETE FROM endentrega WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idendentrega);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function buscar_endereco($conexao, $idendentrega) {
    $sql = "SELECT * FROM endentrega WHERE idendentrega=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idendentrega);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $endereco = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return $endereco ?: null;
}

function buscar_enderecos_por_cliente($conexao, $idcliente) {
    $sql = "SELECT * FROM endentrega WHERE idcliente=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $enderecos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $enderecos;
}

// ======================= PRODUTOS =======================
function criar_produto($conexao, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "INSERT INTO produto (nome, tipo, tamanho, preco, foto) VALUES (?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssds', $nome, $tipo, $tamanho, $preco, $foto);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function atualizar_produto($conexao, $id, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "UPDATE produto SET nome=?, tipo=?, tamanho=?, preco=?, foto=? WHERE idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'sssdsi', $nome, $tipo, $tamanho, $preco, $foto, $id);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function deletar_produto($conexao, $idproduto) {
    $sql = "DELETE FROM produto WHERE idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idproduto);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function listar_produtos($conexao) {
    $sql = "SELECT * FROM produto";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $produtos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $produtos;
}

function pesquisar_produto_por_id($conexao, $idproduto) {
    $sql = "SELECT * FROM produto WHERE idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idproduto);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $produto = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return $produto ?: null;
}

// ======================= CARRINHO =======================
function adicionar_ao_carrinho($conexao, $idcliente, $idproduto, $quantidade = 1) {
    $sql = "SELECT quantidade FROM carrinho WHERE idcliente=? AND idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ii', $idcliente, $idproduto);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);

    if ($linha = mysqli_fetch_assoc($res)) {
        $nova_qtd = $linha['quantidade'] + $quantidade;
        $sql_update = "UPDATE carrinho SET quantidade=? WHERE idcliente=? AND idproduto=?";
        $stmt2 = mysqli_prepare($conexao, $sql_update);
        mysqli_stmt_bind_param($stmt2, 'iii', $nova_qtd, $idcliente, $idproduto);
        $ok = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    } else {
        $sql_insert = "INSERT INTO carrinho (idcliente, idproduto, quantidade) VALUES (?, ?, ?)";
        $stmt2 = mysqli_prepare($conexao, $sql_insert);
        mysqli_stmt_bind_param($stmt2, 'iii', $idcliente, $idproduto, $quantidade);
        $ok = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }

    mysqli_stmt_close($comando);
    return $ok;
}

function remover_do_carrinho($conexao, $idcliente, $idproduto) {
    $sql = "DELETE FROM carrinho WHERE idcliente=? AND idproduto=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ii', $idcliente, $idproduto);
    $ok = mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    return $ok;
}

function contar_itens_carrinho($conexao, $idcliente) {
    $sql = "SELECT SUM(quantidade) AS total FROM carrinho WHERE idcliente=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $linha = mysqli_fetch_assoc($res);
    mysqli_stmt_close($comando);
    return intval($linha['total'] ?? 0);
}

function buscar_carrinho($conexao, $idcliente) {
    $sql = "SELECT c.idcarrinho, c.quantidade, p.nome AS nome_produto, p.preco, p.foto 
            FROM carrinho c
            JOIN produto p ON c.idproduto = p.idproduto
            WHERE c.idcliente=?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $itens;
}
// ======================= PEDIDOS =======================

// Buscar produtos do carrinho de um cliente (completo)
function buscar_carrinho_completo($conexao, $idcliente) {
    $sql = "SELECT c.idproduto, c.quantidade, p.nome, p.preco 
            FROM carrinho c 
            JOIN produto p ON c.idproduto = p.idproduto 
            WHERE c.idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);

    $carrinho = [];
    while ($item = mysqli_fetch_assoc($res)) {
        $carrinho[] = $item;
    }
    mysqli_stmt_close($comando);
    return $carrinho;
}

// Calcular total de um carrinho
function calcular_total_carrinho($carrinho, $taxa_entrega = 0) {
    $total = 0;
    foreach ($carrinho as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
    return $total + $taxa_entrega;
}

// Buscar endereços de entrega de um cliente
function buscar_enderecos_cliente($conexao, $idcliente) {
    $sql = "SELECT idendentrega, rua, numero, complemento, bairro 
            FROM endentrega 
            WHERE idcliente = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $idcliente);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);

    $enderecos = [];
    while ($end = mysqli_fetch_assoc($res)) {
        $enderecos[] = $end;
    }
    mysqli_stmt_close($comando);
    return $enderecos;
}
// ======================= PEDIDOS =======================
/**
 * Lista todos os pedidos com informações do cliente, valor total, status e delivery.
 * Para uso pelo administrador.
 */
function listar_pedidos($conexao) {
    $sql = "SELECT 
                p.idpedido,
                p.data_pedido,
                c.nome AS nome_cliente,
                p.status AS status_pedido,
                p.valortotal,
                pg.idpagamento,
                pg.status_pagamento,
                pg.valor AS valor_pagamento,
                d.iddelivery,
                d.status AS status_delivery,
                e.rua, e.numero, e.bairro, e.complemento
            FROM pedido p
            INNER JOIN cliente c ON p.idcliente = c.idcliente
            INNER JOIN endentrega e ON p.identrega = e.idendentrega
            LEFT JOIN pagamento pg ON pg.idpedido = p.idpedido
            LEFT JOIN delivery d ON d.pedido_id = p.idpedido
            ORDER BY p.idpedido DESC";

    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $pedidos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $pedidos;
}

/**
 * Busca os itens de um pedido específico.
 */
function buscar_itens_pedido($conexao, $idpedido) {
    $sql = "SELECT pr.nome, pp.preco_unit AS preco, pp.quantidade
            FROM pedido_produto pp
            INNER JOIN produto pr ON pp.idproduto = pr.idproduto
            WHERE pp.idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $idpedido);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);
    return $itens;
}
function atualizar_status_pedido($conexao, $idpedido, $status_pedido, $status_pagamento, $status_delivery) {
    $sql = "UPDATE pedido p
            INNER JOIN pagamento pg ON p.idpedido = pg.idpedido
            INNER JOIN delivery d ON p.idpedido = d.pedido_id
            SET p.status = ?, pg.status_pagamento = ?, d.status = ?
            WHERE p.idpedido = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "sssi", $status_pedido, $status_pagamento, $status_delivery, $idpedido);
    mysqli_stmt_execute($comando);
}
// Função para listar todos os pedidos para o administrador
function listar_pedidos_adm($conexao) {
    $sql = "SELECT 
                ped.idpedido, ped.data_pedido, ped.valortotal, ped.status AS status_pedido,
                cli.nome AS nome_cliente
            FROM pedido ped
            INNER JOIN cliente cli ON ped.idcliente = cli.idcliente
            ORDER BY ped.data_pedido DESC";

    $resultado = mysqli_query($conexao, $sql);

    if (!$resultado) {
        die("Erro ao listar pedidos: " . mysqli_error($conexao));
    }

    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
// Buscar um pedido pelo ID
function buscar_pedido($conexao, $idpedido) {
    $sql = "SELECT 
                ped.idpedido, ped.idcliente, ped.identrega, ped.valortotal, ped.data_pedido, ped.status AS status_pedido,
                cli.nome AS nome_cliente
            FROM pedido ped
            INNER JOIN cliente cli ON ped.idcliente = cli.idcliente
            WHERE ped.idpedido = ?";
    
    $comando = mysqli_prepare($conexao, $sql);
    if (!$comando) {
        die("Erro na preparação da query: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param($comando, "i", $idpedido);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);

    return mysqli_fetch_assoc($res);
}
function buscar_produto($conexao, $idproduto = 0, $nome = '') {
    $sql = "SELECT * FROM produto WHERE 1=1"; // Base para filtrar dinamicamente
    $params = [];
    $types = '';

    if ($idproduto > 0) {
        $sql .= " AND idproduto = ?";
        $types .= 'i';
        $params[] = $idproduto;
    }

    if (!empty($nome)) {
        $sql .= " AND nome LIKE ?";
        $types .= 's';
        $params[] = "%$nome%";
    }

    $comando = mysqli_prepare($conexao, $sql);

    if (!empty($params)) {
        mysqli_stmt_bind_param($comando, $types, ...$params);
    }

    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);

    return $produtos;
}
// Lista pagamentos, opcionalmente filtrando pelo método
function listar_pagamentos($conexao, $metodo = '') {
    $sql = "SELECT p.*, c.nome AS cliente_nome 
            FROM pagamento p
            JOIN cliente c ON p.idcliente = c.idcliente";
    
    $params = [];
    $types = '';
    
    if (!empty($metodo)) {
        $sql .= " WHERE p.metodo_pagamento = ?";
        $types .= 's';
        $params[] = $metodo;
    }

    $sql .= " ORDER BY p.data_pagamento DESC";

    $comando = mysqli_prepare($conexao, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($comando, $types, ...$params);
    }

    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $pagamentos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($comando);

    return $pagamentos;
}

// Retorna resumo: quantidade e total dos pagamentos
function resumo_pagamentos($conexao, $metodo = '') {
    $sql = "SELECT COUNT(*) AS qtd, COALESCE(SUM(valor),0) AS total 
            FROM pagamento";

    $params = [];
    $types = '';

    if (!empty($metodo)) {
        $sql .= " WHERE metodo_pagamento = ?";
        $types .= 's';
        $params[] = $metodo;
    }

    $comando = mysqli_prepare($conexao, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($comando, $types, ...$params);
    }

    mysqli_stmt_execute($comando);
    $resultado = mysqli_stmt_get_result($comando);
    $resumo = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($comando);

    return $resumo;
}
// ===========================
// DELIVERY - CRIAR
// ===========================
function criar_delivery($conexao, $pedido_id) {
    $sql = "INSERT INTO delivery (pedido_id, status) VALUES (?, 'atribuido')";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $pedido_id);
    $ok = mysqli_stmt_execute($comando);
    return $ok;
}

// ===========================
// DELIVERY - BUSCAR POR ID
// ===========================
function buscar_delivery($conexao, $iddelivery) {
    $sql = "SELECT * FROM delivery WHERE iddelivery = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "i", $iddelivery);
    mysqli_stmt_execute($comando);
    $res = mysqli_stmt_get_result($comando);
    return mysqli_fetch_assoc($res);
}


// ===========================
// DELIVERY - LISTAR TODOS
// ===========================
function listar_delivery($conexao) {
    $sql = "SELECT d.*, p.idcliente, p.valortotal  FROM delivery d 
    JOIN pedido p ON p.idpedido = d.pedido_id ORDER BY d.iddelivery DESC";
    $res = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

// ===========================
// DELIVERY - ATUALIZAR STATUS
// ===========================
function atualizar_delivery($conexao, $id, $status) {
    $sql = "UPDATE delivery SET status = ? WHERE iddelivery = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, "si", $status, $id);
    return mysqli_stmt_execute($comando);
}

function criar_feedback($conexao, $idpedido, $idcliente, $nota, $comentario) {
    $sql = "INSERT INTO feedback (idpedido, idcliente, nota, comentario) VALUES (?, ?, ?, ?)";
    $cmd = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($cmd, "iiis", $idpedido, $idcliente, $nota, $comentario);
    return mysqli_stmt_execute($cmd);
}

function buscar_feedback($conexao, $idfeedback) {
    $sql = "SELECT * FROM feedback WHERE idfeedback = ?";
    $cmd = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($cmd, "i", $idfeedback);
    mysqli_stmt_execute($cmd);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($cmd));
}

function buscar_feedback_por_pedido($conexao, $idpedido) {
    $sql = "SELECT * FROM feedback WHERE idpedido = ?";
    $cmd = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($cmd, "i", $idpedido);
    mysqli_stmt_execute($cmd);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($cmd));
}

function atualizar_feedback($conexao, $idfeedback, $nota, $comentario) {
    $sql = "UPDATE feedback SET nota=?, comentario=? WHERE idfeedback=?";
    $cmd = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($cmd, "isi", $nota, $comentario, $idfeedback);
    return mysqli_stmt_execute($cmd);
}

function excluir_feedback($conexao, $idfeedback) {
    $sql = "DELETE FROM feedback WHERE idfeedback = ?";
    $cmd = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($cmd, "i", $idfeedback);
    return mysqli_stmt_execute($cmd);
}
function buscar_cliente_por_pedido($conexao, $idpedido) {
    $sql = "SELECT idcliente FROM pedido WHERE idpedido = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idpedido);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultado)) {
        return $row['idcliente']; // retorna apenas o número
    }

    return null; // caso não encontre o pedido
}


?>