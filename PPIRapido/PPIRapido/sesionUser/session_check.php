<?php
session_start();

// Definir las rutas que no requieren autenticación
$rutasPermitidas = [
    '/index.php',
    '/conocenos.php',
    '/contactanos.php',
    // Añade más rutas si es necesario
];

// Obtener la ruta solicitada
$rutaActual = $_SERVER['REQUEST_URI'];

// Verificar si el usuario está autenticado o la ruta está permitida
if (!isset($_SESSION['usuario_id']) && !in_array($rutaActual, $rutasPermitidas)) {
    // Si no hay sesión y la ruta no está en las permitidas, redirigir al login
    header("Location: /login.php");
    exit;
}

// Si la sesión está activa o la ruta es permitida, continuar con el código
if (isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id'];
    $usuarioNombre = $_SESSION['usuario_nombre'];
    $usuarioRol = $_SESSION['usuario_rol'];
    $esAgente = $_SESSION['es_agente'];

} 
?>
