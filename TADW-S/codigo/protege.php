<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$currentPage = basename($_SERVER['PHP_SELF']);


if ((!isset($_SESSION['logado']) || $_SESSION['logado'] !== 'sim') && $currentPage !== 'login.php') {
    header("Location: ../login.php");
    exit;
}


$usuario = $_SESSION['usuario'] ?? 'Usuário';
$tipo = $_SESSION['tipo'] ?? ''; 

function protegeTipo($tipoNecessario) {
    global $tipo;
    if ($tipo !== $tipoNecessario) {

        if ($tipo === 'adm') {
            header("Location: ../homeAdm.php");
        } else {
            header("Location: ../home_cliente.php");
        }
        exit;
    }
}
