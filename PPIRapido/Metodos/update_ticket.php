<?php
// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye el archivo select.php para usar las funciones de conexión
require __DIR__ . "/select.php";

// Verifica si el formulario ha sido enviado y si existe un ID de ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_ticket'])) {
    // Obtén los datos del formulario
    $ticketId = $_POST['id_ticket'];
    $usuarioTicket = $_POST['usuario_ticket'] ?? '';
    $areaTicket = $_POST['area_ticket'] ?? '';
    $correoElectronico = $_POST['correo_electronico'] ?? '';
    $descripcionTicket = $_POST['descripcion_ticket'] ?? '';

    // Maneja la fecha de cierre, asegurándose de que sea null si está vacía
    $fechaCierreTicket = !empty($_POST['fecha_cierre_ticket']) ? $_POST['fecha_cierre_ticket'] : null;

    $agenteAsignadoTicket = $_POST['agente_asignado_ticket'] !== '' ? $_POST['agente_asignado_ticket'] : null;

    // Obtén una instancia de la conexión PDO
    $pdo = conection();

    // Prepara la consulta de actualización
    $query = "UPDATE tickets SET 
                usuario_ticket = :usuario_ticket,
                area_ticket = :area_ticket,
                correo_electronico = :correo_electronico,
                descripcion_ticket = :descripcion_ticket,
                fecha_cierre_ticket = :fecha_cierre_ticket,
                agente_asignado_ticket = :agente_asignado_ticket
              WHERE id_ticket = :id_ticket";

    // Prepara la declaración PDO
    $stmt = $pdo->prepare($query);

    // Ejecuta la declaración con los valores proporcionados
    $stmt->execute([
        ':usuario_ticket' => $usuarioTicket,
        ':area_ticket' => $areaTicket,
        ':correo_electronico' => $correoElectronico,
        ':descripcion_ticket' => $descripcionTicket,
        ':fecha_cierre_ticket' => $fechaCierreTicket,
        ':agente_asignado_ticket' => $agenteAsignadoTicket,
        ':id_ticket' => $ticketId,
    ]);

    header('Location: ../provideTickets.php');
    exit();
}
