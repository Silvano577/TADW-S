<?php
require_once "../conexao.php";
require_once "../funcao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recebe os campos
$rua = trim($_POST['rua']);
$numero = trim($_POST['numero']);
$complemento = trim($_POST['complemento']);
$bairro = trim($_POST['bairro']);
$cidade = trim($_POST['cidade']);
$cep = trim($_POST['cep']);
$tempo_entrega_min = intval($_POST['tempo_entrega_min']);
$latitude = floatval($_POST['latitude']);
$longitude = floatval($_POST['longitude']);

// Validação
if(empty($rua) || empty($numero) || empty($bairro) || empty($cidade) || empty($cep) || $tempo_entrega_min <=0 || $latitude==0 || $longitude==0){
    die("Erro: Todos os campos obrigatórios devem ser preenchidos corretamente.");
}

// Cria ou atualiza
if($id > 0){
    if(atualizar_delivery($conexao, $id, $rua, $numero, $complemento, $bairro, $cidade, $cep, $tempo_entrega_min, $latitude, $longitude)){
        $mensagem = "Delivery atualizado com sucesso!";
    } else die("Erro ao atualizar delivery.");
}else{
    if(criar_delivery($conexao, $rua, $numero, $complemento, $bairro, $cidade, $cep, $tempo_entrega_min, $latitude, $longitude)){
        $mensagem = "Delivery cadastrado com sucesso!";
    } else die("Erro ao cadastrar delivery.");
}

header("Location: ../homeAdm.php?msg=".urlencode($mensagem));
exit;
?>
