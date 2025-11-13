<?php
// funcao.php - versão padronizada e limpa

// ======================= USUÁRIO =======================
function criar_usuario($conexao, $usuario, $email, $senha) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'cliente';
    $sql = "INSERT INTO usuario (usuario, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $email, $senha_hash, $tipo);
    $ok = mysqli_stmt_execute($stmt);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($stmt);
    return $id;
}

function atualizar_usuario($conexao, $idusuario, $usuario, $email, $senha = null) {
    if ($senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET usuario=?, email=?, senha=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $usuario, $email, $senha_hash, $idusuario);
    } else {
        $sql = "UPDATE usuario SET usuario=?, email=? WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $usuario, $email, $idusuario);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deletar_usuario($conexao, $idusuario) {
    $sql = "DELETE FROM usuario WHERE idusuario=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idusuario);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function listar_usuarios($conexao) {
    $sql = "SELECT * FROM usuario";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $usuarios = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $usuarios;
}

function buscar_usuario($conexao, $idusuario = 0, $usuario = '') {
    if ($idusuario > 0) {
        $sql = "SELECT * FROM usuario WHERE idusuario=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $idusuario);
    } elseif ($usuario) {
        $sql = "SELECT * FROM usuario WHERE usuario LIKE ?";
        $stmt = mysqli_prepare($conexao, $sql);
        $like = "%$usuario%";
        mysqli_stmt_bind_param($stmt, 's', $like);
    } else {
        $sql = "SELECT * FROM usuario";
        $stmt = mysqli_prepare($conexao, $sql);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

// ======================= CLIENTE =======================
function criar_cliente($conexao, $nome, $data_ani, $telefone, $foto, $idusuario) {
    $sql = "INSERT INTO cliente (nome, data_ani, telefone, foto, idusuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $data_ani, $telefone, $foto, $idusuario);
    $ok = mysqli_stmt_execute($stmt);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($stmt);
    return $id;
}

function atualizar_cliente($conexao, $idcliente, $nome, $data_ani, $telefone, $foto = null) {
    if ($foto) {
        $sql = "UPDATE cliente SET nome=?, data_ani=?, telefone=?, foto=? WHERE idcliente=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $data_ani, $telefone, $foto, $idcliente);
    } else {
        $sql = "UPDATE cliente SET nome=?, data_ani=?, telefone=? WHERE idcliente=?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $nome, $data_ani, $telefone, $idcliente);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deletar_cliente($conexao, $idcliente) {
    $sql = "DELETE FROM cliente WHERE idcliente=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function listar_clientes($conexao) {
    $sql = "SELECT * FROM cliente";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $clientes = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $clientes;
}

function buscar_cliente_por_usuario($conexao, $idusuario) {
    $sql = "SELECT * FROM cliente WHERE idusuario=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idusuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $cliente = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $cliente ?: null;
}

function buscar_cliente_por_id($conexao, $idcliente) {
    $sql = "SELECT * FROM cliente WHERE idcliente=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $cliente = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $cliente ?: null;
}

// ======================= ENDEREÇOS =======================
function registrar_endereco($conexao, $rua, $numero, $complemento, $bairro, $idcliente) {
    $sql = "INSERT INTO endentrega (rua, numero, complemento, bairro, idcliente) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $rua, $numero, $complemento, $bairro, $idcliente);
    $ok = mysqli_stmt_execute($stmt);
    $id = $ok ? mysqli_insert_id($conexao) : false;
    mysqli_stmt_close($stmt);
    return $id;
}

function atualizar_endereco($conexao, $id, $rua, $numero, $complemento, $bairro, $idcliente) {
    $sql = "UPDATE endentrega SET rua=?, numero=?, complemento=?, bairro=?, idcliente=? WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $rua, $numero, $complemento, $bairro, $idcliente, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deletar_endereco($conexao, $idendentrega) {
    $sql = "DELETE FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idendentrega);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function buscar_endereco($conexao, $idendentrega) {
    $sql = "SELECT * FROM endentrega WHERE idendentrega=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idendentrega);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $endereco = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $endereco ?: null;
}

function buscar_enderecos_por_cliente($conexao, $idcliente) {
    $sql = "SELECT * FROM endentrega WHERE idcliente=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $enderecos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $enderecos;
}

// ======================= PRODUTOS =======================
function criar_produto($conexao, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "INSERT INTO produto (nome, tipo, tamanho, preco, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'sssds', $nome, $tipo, $tamanho, $preco, $foto);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function atualizar_produto($conexao, $id, $nome, $tipo, $tamanho, $preco, $foto) {
    $sql = "UPDATE produto SET nome=?, tipo=?, tamanho=?, preco=?, foto=? WHERE idproduto=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'sssdsi', $nome, $tipo, $tamanho, $preco, $foto, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deletar_produto($conexao, $idproduto) {
    $sql = "DELETE FROM produto WHERE idproduto=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idproduto);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function listar_produtos($conexao) {
    $sql = "SELECT * FROM produto";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $produtos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $produtos;
}

function pesquisar_produto_por_id($conexao, $idproduto) {
    $sql = "SELECT * FROM produto WHERE idproduto=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idproduto);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $produto = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $produto ?: null;
}

// ======================= CARRINHO =======================
function adicionar_ao_carrinho($conexao, $idcliente, $idproduto, $quantidade = 1) {
    $sql = "SELECT quantidade FROM carrinho WHERE idcliente=? AND idproduto=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $idcliente, $idproduto);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

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

    mysqli_stmt_close($stmt);
    return $ok;
}

function remover_do_carrinho($conexao, $idcliente, $idproduto) {
    $sql = "DELETE FROM carrinho WHERE idcliente=? AND idproduto=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $idcliente, $idproduto);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function contar_itens_carrinho($conexao, $idcliente) {
    $sql = "SELECT SUM(quantidade) AS total FROM carrinho WHERE idcliente=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $linha = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return intval($linha['total'] ?? 0);
}

function buscar_carrinho($conexao, $idcliente) {
    $sql = "SELECT c.idcarrinho, c.quantidade, p.nome AS nome_produto, p.preco, p.foto 
            FROM carrinho c
            JOIN produto p ON c.idproduto = p.idproduto
            WHERE c.idcliente=?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $itens;
}
// ======================= PEDIDOS =======================

// Buscar produtos do carrinho de um cliente (completo)
function buscar_carrinho_completo($conexao, $idcliente) {
    $sql = "SELECT c.idproduto, c.quantidade, p.nome, p.preco 
            FROM carrinho c 
            JOIN produto p ON c.idproduto = p.idproduto 
            WHERE c.idcliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $carrinho = [];
    while ($item = mysqli_fetch_assoc($res)) {
        $carrinho[] = $item;
    }
    mysqli_stmt_close($stmt);
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
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idcliente);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $enderecos = [];
    while ($end = mysqli_fetch_assoc($res)) {
        $enderecos[] = $end;
    }
    mysqli_stmt_close($stmt);
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

    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $pedidos = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
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
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idpedido);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $itens = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $itens;
}
function atualizar_status_pedido($conexao, $idpedido, $status_pedido, $status_pagamento, $status_delivery) {
    $sql = "UPDATE pedido p
            INNER JOIN pagamento pg ON p.idpedido = pg.idpedido
            INNER JOIN delivery d ON p.idpedido = d.pedido_id
            SET p.status = ?, pg.status_pagamento = ?, d.status = ?
            WHERE p.idpedido = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $status_pedido, $status_pagamento, $status_delivery, $idpedido);
    mysqli_stmt_execute($stmt);
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
    
    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) {
        die("Erro na preparação da query: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param($stmt, "i", $idpedido);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

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

    $stmt = mysqli_prepare($conexao, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $pagamentos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

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

    $stmt = mysqli_prepare($conexao, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $resumo = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    return $resumo;
}
?>