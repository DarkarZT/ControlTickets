<?php
require_once __DIR__ . '/../database/conexion.php';

function iniciarConversacion($id_cliente, $id_asesor, $pdo) {
    // Ejemplo de la consulta SQL para insertar una nueva conversación
    $sql = "INSERT INTO conversaciones (id_cliente, id_asesor, fecha_creacion) VALUES (:id_cliente, :id_asesor, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindParam(':id_asesor', $id_asesor, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        return $pdo->lastInsertId(); // Retorna el ID de la nueva conversación
    } else {
        return false; // Indica que hubo un error
    }
}


function cerrarConversacion($id_conversacion, $pdo) {
    $stmt = $pdo->prepare("UPDATE conversaciones SET estado = 'cerrada', fecha_cierre = NOW() WHERE id_conversacion = ?");
    $stmt->execute([$id_conversacion]);
}

function obtenerConversaciones($id_cliente, $id_asesor, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM conversaciones WHERE id_cliente = ? OR id_asesor = ? ORDER BY fecha_creacion DESC");
    $stmt->execute([$id_cliente, $id_asesor]);
    return $stmt->fetchAll();
}
?>
