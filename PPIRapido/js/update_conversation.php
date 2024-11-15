<?php
// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../database/conexion.php'; // Asegúrate de que la ruta sea correcta

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_conversacion']) && isset($data['id_asesor'])) {
        $id_conversacion = htmlspecialchars($data['id_conversacion'], ENT_QUOTES, 'UTF-8');
        $id_asesor = htmlspecialchars($data['id_asesor'], ENT_QUOTES, 'UTF-8');

        // Conexión a la base de datos
        $conexion = conection();

        // Actualizar el id_asesor en la conversación
        $stmt = $conexion->prepare("UPDATE conversaciones SET id_asesor = :id_asesor WHERE id_conversacion = :id_conversacion");
        $stmt->bindParam(':id_asesor', $id_asesor, PDO::PARAM_INT);
        $stmt->bindParam(':id_conversacion', $id_conversacion, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la conversación.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de conversación o asesor no proporcionados.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
