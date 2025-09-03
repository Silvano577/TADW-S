<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexao.php";

// 1) Receber e validar
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header("Location: login.php?erro=campos");
    exit;
}

// 2) Buscar usuário com prepared statement
$sql = "SELECT idusuario, usuario, email, senha, tipo FROM usuario WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
if (!$stmt) {
    // Em produção, logar erro; aqui só volta com mensagem genérica
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

// 3) Conferir senha (assumindo senha hash com password_hash)
if (!password_verify($senha, $linha['senha'])) {
    header("Location: login.php?erro=senha");
    exit;
}

// 4) Logar e redirecionar SEMPRE ao index
session_regenerate_id(true);
$_SESSION['logado']   = 'sim';
$_SESSION['idusuario']= $linha['idusuario'] ?? null;
$_SESSION['usuario']  = $linha['usuario'];   // nome do usuário
$_SESSION['tipo']     = $linha['tipo'];      // 'adm' ou 'cliente'

// Opcional: parâmetro só para dar um "bem-vindo" no index
header("Location: index.php?bemvindo=1");
exit;
