<?php
// protege.php
session_start();

// Se não estiver logado, redireciona para login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') {
    header("Location: ../login.php"); // ajuste o caminho conforme a estrutura
    exit;
}

// Disponibiliza os dados do usuário
$usuario = $_SESSION['usuario'] ?? 'Usuário';
$tipo = $_SESSION['tipo'] ?? ''; // ADM ou usuario

/**
 * Função para proteger páginas específicas por tipo
 * Uso: protegeTipo('ADM'); ou protegeTipo('usuario');
 */
function protegeTipo($tipoNecessario) {
    global $tipo;
    if ($tipo !== $tipoNecessario) {
        // Redireciona para a home do tipo correspondente
        if ($tipo === 'ADM') {
            header("Location: ../homeAdm.php");
        } else {
            header("Location: ../home_cliente.php");
        }
        exit;
    }
}
