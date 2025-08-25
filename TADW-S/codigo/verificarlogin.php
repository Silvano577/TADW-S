<?php
require_once "conexao.php";

// Recebe dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica se os campos foram preenchidos
if (empty($email) || empty($senha)) {
    header("Location: index.php?erro=campos");
    exit;
}

// Prepara a query para evitar SQL Injection
$sql = "SELECT * FROM usuario WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Verifica se encontrou o usuário
$linha = mysqli_fetch_assoc($resultado);
if (!$linha) {
    // Email não encontrado
    header("Location: index.php?erro=email");
    exit;
}

$senha_banco = $linha['senha'];
$tipo = $linha['tipo'];
$usuario_nome = $linha['usuario']; // <-- coluna correta da tabela

// Verifica a senha
// Verifica a senha
if (password_verify($senha, $senha_banco)) {
    session_start();
    session_regenerate_id(true); // protege a sessão
    $_SESSION['logado'] = 'sim';
    $_SESSION['tipo'] = $tipo;
    $_SESSION['usuario'] = $usuario_nome;

    // Redireciona conforme o tipo
    if ($tipo === 'adm') {
        header("Location: homeAdm.php");
    } else {
        header("Location: index.php");
    }
    exit;
} else {
    // Senha incorreta
    header("Location: login.php?erro=senha");
    exit;
}

?>
