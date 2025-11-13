<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "conexao.php";

// Captura os dados enviados pelo formulário
$login = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$senha = $_POST['senha'] ?? '';

// Verifica se os campos foram preenchidos
if ($login === '' || $senha === '') {
    header("Location: login.php?erro=campos");
    exit;
}

// Consulta que aceita tanto usuário quanto e-mail
$sql = "SELECT idusuario, usuario, email, senha, tipo 
        FROM usuario 
        WHERE usuario = ? OR email = ?";
$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    header("Location: login.php?erro=usuario");
    exit;
}

// Usa o mesmo valor ($login) para comparar com ambos (usuario e email)
mysqli_stmt_bind_param($stmt, "ss", $login, $login);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$linha = $result ? mysqli_fetch_assoc($result) : null;

// Verifica se encontrou registro
if (!$linha) {
    header("Location: login.php?erro=usuario");
    exit;
}

// Confere a senha
if (!password_verify($senha, $linha['senha'])) {
    header("Location: login.php?erro=senha");
    exit;
}

// Se deu certo, cria a sessão
session_regenerate_id(true);
$_SESSION['logado']    = 'sim';
$_SESSION['idusuario'] = $linha['idusuario'] ?? null;
$_SESSION['usuario']   = $linha['usuario'];
$_SESSION['tipo']      = $linha['tipo'];

// Redireciona conforme o tipo de conta
if ($linha['tipo'] === 'adm') {
    header("Location: homeAdm.php");
} else {
    header("Location: index.php?bemvindo=1");
}
exit;
?>
