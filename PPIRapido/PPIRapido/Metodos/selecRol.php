<?php
require_once __DIR__ . '/../database/conexion.php'; // Asegúrate de que la ruta es correcta

function rolesCh($pdo) {
    $sql = "SELECT id_rol, nombre_rol FROM roles"; // Asegúrate de que la consulta sea correcta
    try {
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener roles: ' . $e->getMessage();
        return [];
    }
}
?>