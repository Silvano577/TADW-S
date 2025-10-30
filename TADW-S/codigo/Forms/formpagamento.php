<?php
session_start();
require_once "../conexao.php";
require_once "../funcao.php";

// Verifica login
if (empty($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['idusuario'] ?? 0;
$cliente = buscar_cliente_por_usuario($conexao, $usuario_id);
$idcliente = $cliente['idcliente'] ?? 0;

$idpedido = intval($_GET['idpedido'] ?? 0);
$total = floatval($_GET['valor_total'] ?? 0);
?>

<h2>Pagamento do Pedido #<?= $idpedido ?></h2>
<p>Valor total: R$ <?= number_format($total, 2, ',', '.') ?></p>

<form method="POST" action="../Salvar/salvarpagamento.php">
    <input type="hidden" name="idpedido" value="<?= $idpedido ?>">
    <input type="hidden" name="valor" value="<?= $total ?>">
    
    <label>Método de pagamento:</label>
    <select name="metodo" required>
        <option value="pix">PIX</option>
        <option value="cartao_debito">Cartão de Débito</option>
        <option value="cartao_credito">Cartão de Crédito</option>
        <option value="dinheiro">Dinheiro</option>
    </select>
    <br><br>
    <button type="submit">Registrar Pagamento</button>
</form>
