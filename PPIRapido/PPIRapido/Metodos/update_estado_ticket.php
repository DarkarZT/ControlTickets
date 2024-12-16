<?php
require __DIR__ . "/select.php";

// Obtén el ID del ticket y el nuevo estado desde el formulario POST
$ticketId = $_POST['id_ticket'] ?? null;
$nuevoEstado = $_POST['nuevo_estado'] ?? null;

if ($ticketId && $nuevoEstado) {
    // Obtén una instancia de la conexión PDO
    $pdo = conection(); // Asegúrate de que la función conection() está definida en conexion.php

    $sql = "UPDATE tickets SET estado_ticket = :nuevo_estado, fecha_cierre_ticket = NOW() WHERE id_ticket = :id_ticket";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nuevo_estado', $nuevoEstado, PDO::PARAM_INT);
        $stmt->bindParam(':id_ticket', $ticketId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirige a la página de detalles del ticket o a otra página deseada
        header("Location: ../index.php");
        exit;
    } catch (\PDOException $e) {
        echo "Error al actualizar el estado del ticket: " . $e->getMessage();
    }
} else {
    echo "Datos incompletos para actualizar el estado del ticket.";
}
?>
