<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexao.php";


$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header("Location: login.php?erro=campos");
    exit;
}


$sql = "SELECT idusuario, usuario, email, senha, tipo FROM usuario WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
if (!$stmt) {

    header("Location: login.php?erro=email");
    exit;
}
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$linha = $result ? mysqli_fetch_assoc($result) : null;

if (!$linha) {
    header("Location: login.php?erro=email");
    exit;
}


if (!password_verify($senha, $linha['senha'])) {
    header("Location: login.php?erro=senha");
    exit;
}


session_regenerate_id(true);
$_SESSION['logado']   = 'sim';
$_SESSION['idusuario']= $linha['idusuario'] ?? null;
$_SESSION['usuario']  = $linha['usuario'];  
$_SESSION['tipo']     = $linha['tipo'];     

header("Location: index.php?bemvindo=1");
exit;
