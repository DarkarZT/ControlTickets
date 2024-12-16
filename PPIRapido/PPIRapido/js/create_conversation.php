<?php
// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo que contiene la verificación de sesión
require __DIR__ . '/../sesionUser/session_check.php';
require __DIR__ . '/conversaciones.php'; // Incluir las funciones necesarias

// Verifica si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica que se reciba el ID del cliente
    if (isset($data['id_cliente'])) {
        $id_cliente = htmlspecialchars($data['id_cliente'], ENT_QUOTES, 'UTF-8');

        // Lógica para iniciar la conversación sin un asesor asignado
        $pdo = conection(); // Obtener conexión PDO
        $id_conversacion = iniciarConversacion($id_cliente, null, $pdo); // Pasar null como ID de asesor

        if ($id_conversacion) {
            echo json_encode(['success' => true, 'id_conversacion' => $id_conversacion]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo iniciar la conversación.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de cliente no proporcionado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
