<?php

require_once "./conexao.php";
require_once "./funcao.php";


$idpedido = 1;
$idproduto = 1;
$quantidade = 4;

salvarAtualizarItemPedido($conexao, $idpedido, $idproduto, $quantidade);
